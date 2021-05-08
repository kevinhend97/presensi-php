<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Models\AttendancesModel;
use \App\Models\ServersideModel;

class Attendance extends BaseController
{

	public function __construct()
	{
		$this->db = \Config\Database::connect();
	}

	public function index()
	{
		$data['pages'] = "Attenance";

		return view('attendance/index', $data);
	}

	public function list()
	{
		$attendance = $this->db->table('vw_attendanceuser')->orderBy('attendanceId', 'DESC')->get()->getResultArray();	

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
        $order = array('attendanceId' => 'desc');
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
            $row[] = $lists->status;
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

			
			$attachment = $this->upload();
			
			if(decode($attachment, true))
			{ 
				return $this->response->setJSON($response);
				die();	
			}
		

			$data['attachment'] = $attachment;

			// $attendance->insert($data);

			$response = [
				'success'   => true,
				'message'   => 'Data has been save' 
			];
			
	
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
			return $this->response->setJSON($data);
			die();
		}

		$attachment = $this->request->getFile('upload');
		$fileName = $attachment->getRandomName();
		$attachment->move('upload/',$fileName);

		return $this->response->setJSON($fileName);
	}
}
