<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Models\AttendancesModel;
use \App\Models\EventsModel;
use \App\Models\UsersModel;
use \App\Models\ServersideModel;

class Attendance extends BaseController
{

	public function __construct()
	{
		$this->db = \Config\Database::connect();
	}

	public function index()
	{

		if(session('role') == 3)
		{
			return view('errors/html/error_404');
		}

		$data['pages'] = "Attenance";

		return view('attendance/index', $data);
	}

	public function scan()
	{
		if(session('role') != 3)
		{
			return view('errors/html/error_404');
		}

		$data['pages'] = "Attenance";

		return view('mobile/scan', $data);
	}

	public function list()
	{
		if(session()->get('role') == 3)
		{
			$attendance = $this->db->table('vw_attendanceuser')->where('userId', session()->get('userId'))->orderBy('attendanceId', 'DESC')->get()->getResultArray();	
		}
		else
		{
			$attendance = $this->db->table('vw_attendanceuser')->orderBy('attendanceId', 'DESC')->get()->getResultArray();	
		}

		if($attendance)
		{
			$arr['kamarApps']['status_code'] = 201;
			$arr['kamarApps']['success'] = true;
			$arr['kamarApps']['message'] = "Success get Data";
			$arr['kamarApps']['count'] = count($attendance);
			$arr['kamarApps']['information'] = array(
				"apps"				=> "Presensi",
				"access_date"		=> date('d-m-Y') 
			);
			$arr['kamarApps']['results'] = $attendance;
		}
		else
        {
            $arr['kamarApps']['status_code'] = 404;
            $arr['kamarApps']['success'] = false;
            $arr['kamarApps']['message'] = "Data is not found";
        }

		return $this->response->setJSON($arr);        
	}

	public function listPerYear()
	{
		$attendance = $this->db->table('vw_countPerYear')->get()->getRowArray();	

		if($attendance)
		{
			$arr['kamarApps']['status_code'] = 201;
			$arr['kamarApps']['success'] = true;
			$arr['kamarApps']['message'] = "Success get Data";
			$arr['kamarApps']['count'] = count($attendance);
			$arr['kamarApps']['information'] = array(
				"apps"				=> "Presensi",
				"access_date"		=> date('d-m-Y') 
			);
			$arr['kamarApps']['results'] = $attendance;
		}
		else
        {
            $arr['kamarApps']['status_code'] = 404;
            $arr['kamarApps']['success'] = false;
            $arr['kamarApps']['message'] = "Data is not found";
        }

		return $this->response->setJSON($arr);        
	}

	public function listData()
	{
		
        $request = \Config\Services::request();

        $list_data = new ServersideModel();
        $where = null;
       
        //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
        //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama

        $column_order = array('timestamp','name','event_name', 'location', 'status');
        $column_search = array('timestamp','name','event_name', 'location', 'status');
        $order = array('timestamp' => 'DESC');
        $list = $list_data->get_datatables('vw_attendanceuser', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->getPost("start");

        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = date('d/m/Y H:i:s', strtotime($lists->timestamp)). " WIB";
            $row[] = $lists->name;
            $row[] = $lists->event_name;
            $row[] = $lists->location;
            $row[] = $lists->status == "PRESENCE" ? "<p class='text-success'>".$lists->status."</p>" : "<p class='text-danger'>".$lists->status."</p>";
			$row[] = '<button type="button" onclick="show('.$lists->attendanceId.')" class="btn btn-info text-light"><i class="far fa-eye"></i></button>';
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all('vw_attendanceuser', $where),
            "recordsFiltered" => $list_data->count_filtered('vw_attendanceuser', $column_order, $column_search, $order, $where),
            "data" => $data,
        );
 
        return json_encode($output);  

	}

	public function attendanceStatus()
	{
		$data = array(
			array(
				"id"	=> "SICK",
				"text"	=> "Sick"
			),
			array(
				"id"	=> "ATTENDANCE",
				"text"	=> "Attendance"
			),
			array(
				"id"	=> "ALPHA",
				"text"	=> "Alpha"
			)
		);

		return $this->response->setJSON($data);   
	}

	public function getById($id)
	{
		$attendance = $this->db->table('tblt_attendance')->where('attendanceId', $id)->get()->getRow();
		
		return $this->response->setJSON($attendance);   
	}

	public function store()
	{
		try{
			$attendance = new AttendancesModel();

			$data = [
				// "userId"			=> session()->get('userId'),
				"userId"			=> $this->request->getPost('user'),
				"eventId"			=> $this->request->getPost('event'),
				"attendanceStatus"	=> $this->request->getPost('status'),
				"message"			=> $this->request->getPost('message'),
			];

			if($_FILES['upload']['name'])
			{
				$attachment = $this->upload();
			}
			else
			{
				$attachment = null;
			}

			if(is_array($attachment))
			{ 	
				$response = [
					'success'   => false,
					'message'   => $attachment['message_apps'] 
				];
			}
			else{
				$data['attachment'] = $attachment;
	
				$attendance->insert($data);
	
				$response = [
					'success'   => true,
					'message'   => 'Data has been save' 
				];
			}
		
			return $this->response->setJSON($response);
		}
		catch(Exception $e)
		{
			$data = array(
                "status"            => 500,
                "message_error"     => "Internal Server Error",
                "message_apps"      => $e->getMessage()
            );
            
			return $this->response->setJSON($data);

            die();
		}

	}

	public function presence()
	{
		try{
			
			$attendance = new AttendancesModel();
			$event = new EventsModel();
			$id = $this->request->getGet('p');

			// Cek Tanggal Event
			$checkEvent = $event->where(array("eventId" => base64_decode($this->request->getGet('p')), "date" => date('Y-m-d')))->get()->getRow();
			
			if($checkEvent)
			{
				$data = [
					"userId"			=> session()->get('userId'),
					"eventId"			=> base64_decode($id),
					"attendanceStatus"	=> "PRESENCE",
					"message"			=> 'ACCESS THIS EVENT',
				];	
		
				$attendance->insert($data);
		
				$response = [
					'success'   => true,
					'message'   => 'Successfull for Attendance' 
				];
			}
			else
			{
				$response = [
					'success'   => false,
					'message'   => 'Date is not available now' 
				];
			}
			
			// $newEventId = $encrypter->decrypt($this->request->getGet('p'));
			

	
			return $this->response->setJSON($response);
		}
		catch(Exception $e)
		{
			$data = array(
                "status"            => 500,
                "message_error"     => "Internal Server Error",
                "message_apps"      => $e->getMessage()
            );
            
			return $this->response->setJSON($data);

            die();	
		}
	}

	public function upload()
	{
		// check directory
		if(!file_exists(ROOTPATH.'/public/upload'))
        {
            mkdir(ROOTPATH.'/public/upload', 0777, true);
        }

		// make upload command
		// Check validation File
		if(!$this->validate([
			'upload'		=> [
				'rules'		=> 'uploaded[upload]|mime_in[upload,image/jpg,image/png,image/jpeg,application/pdf]|max_size[upload,2048]',
				'errors' 	=> [
					'uploaded' => 'Harus Ada File yang diupload',
					'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
					'max_size' => 'Ukuran File Maksimal 2 MB'
				]
			]
		]))
		{

			$data = array(
                "status"            => 500,
                "message_error"     => "Internal Server Error",
                "message_apps"      => $this->validator->getError('upload')
            );
			// return $this->response->setJSON($data);
			return $data;
			die();
		}

		$attachment = $this->request->getFile('upload');
		$fileName = $attachment->getRandomName();
		$attachment->move('upload/',$fileName);

		return$fileName;
		// return $this->response->setJSON($fileName);
	}

	public function report()
	{
		$attendance = new AttendancesModel();

		$startDate = date('Y-m-d',strtotime($this->request->getGet('start')));
		$endDate = date('Y-m-d',strtotime($this->request->getGet('end')));
		
		$user = new UsersModel();

        $userList =  $user->select('userId,name')
                        ->where(['is_active' => 1, 'roleId' => 3])
                        ->orderBy('name', 'ASC')->get()->getResult();

		
		$event = new EventsModel();
		$eventList =  $event->select('eventId, event_name')
		->where('is_active', 1)
		->where("date BETWEEN '$startDate' AND '$endDate'")
		->orderBy('date', 'ASC')->get()->getResult();

		$data = [
			"title"		=> strtoupper("Periode ".date('d M Y',strtotime($this->request->getGet('start'))). ' s/d '.date('d M Y',strtotime($this->request->getGet('end')))),
			"users"		=> $userList,
			"visitor"	=> $this->db->query("CALL sp_eventVisitor('$startDate','$endDate')")->getResult(),
			"events" 	=> $eventList
		];

		foreach($data['events'] as $eventList)
		{
			foreach($data['users'] as $user)
			{
				$data['list'][$user->name][] = !$attendance->where(['userId' => $user->userId, 'eventId' => $eventList->eventId])->get()->getRow() ? "none" : $attendance->where('userId', $user->userId)->get()->getRow()->attendanceStatus;
				// $data['list'][$eventList->event_name][] = !$attendance->where('userId', $user->userId)->get()->getRow() ? "none" : $attendance->where('userId', $user->userId)->get()->getRow()->attendanceStatus;
			}
		}


		return view('attendance/report', $data);
	}
}
