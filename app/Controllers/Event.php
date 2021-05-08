<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use \App\Models\EventsModel;
use \App\Models\ServersideModel;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class Event extends BaseController
{
	public function index()
	{
		$data['pages'] = "Events";

		return view('event/index', $data);
	}

	public function listdata()
    {
 
        $request = \Config\Services::request();

        $list_data = new ServersideModel();
        $where = array("is_active" => 1);

        //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
        //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama

        $column_order = array('timestamp','event_name','location', 'date');
        $column_search = array('event_name','location', 'date');
        $order = array('eventId' => 'desc');
        $list = $list_data->get_datatables('tblm_events', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->getPost("start");

        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = date('d/m/Y H:i:s', strtotime($lists->timestamp)). " WIB";
            $row[] = '<a href="javascript:void(0)" onclick="edit('.$lists->eventId.')">'.$lists->event_name."</a>";
            $row[] = $lists->location;
            $row[] = date('d/m/Y', strtotime($lists->date));
            $row[] = '<button type="button" onclick="destroy('.$lists->eventId.')" class="btn btn-danger text-light"><i class="cil-ban"></i></button><button type="button" onclick="qrgenerate('.$lists->eventId.')" class="btn btn-info text-light"><i class="cil-qr-code"></i></button>';
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all('tblm_events', $where),
            "recordsFiltered" => $list_data->count_filtered('tblm_events', $column_order, $column_search, $order, $where),
            "data" => $data,
        );
 
        return json_encode($output);
    }

	public function list()
    {
        $event = new EventsModel();

        $eventList =  $event->select('eventId, event_name, location, description, date, start_time, end_time')
                        ->where('is_active', 1)
                        ->orderBy('eventId', 'DESC')->get()->getResult();

        $arr['kamarApps'] = array();
		$arr['kamarApps']['information'] = array(
			"apps"				=> "Presensi",
			"development_date"	=> "2021-03-10" 
		);

        if($eventList)
        {
            foreach($eventList as $list)
            {
                $arr['kamarApps']['results'][] = array(
                        "eventId"   		=> $list->eventId,
                        "event_name"     	=> $list->event_name,
						"location"			=> $list->location,
						"description"		=> $list->description,
						"date"				=> date('Y-m-d', strtotime($list->date)),
						"start_time"		=> date('H:i', strtotime($list->start_time)),
						"end_time"			=> date('H:i', strtotime($list->end_time))
                );
            }
            $arr['kamarApps']['count'] = count($eventList);
        }
        else
        {
            $arr['kamarApps']['results'] = "Data is NULL";
        }
        

        return $this->response->setJSON($arr);               
    }

	public function store()
	{
		$event = new EventsModel();

		$data = [
			'event_name'  	=> $this->request->getPost('eventName'),
			'location'		=> $this->request->getPost('location'),
			'description'	=> $this->request->getPost('description'),
			'date'			=> date('Y-m-d', strtotime($this->request->getPost('date'))),
			'start_time'	=> date('H:i', strtotime($this->request->getPost('startTime'))),
			'end_time'		=> date('H:i', strtotime($this->request->getPost('endTime')))	
		];

		$event->insert($data);

		$response = [
			'success'   => true,
			'message'   => 'Data has been save' 
		];
        

        return $this->response->setJSON($response);
	}

    public function edit()
    {
        $event = new EventsModel();
        $eventId = $this->request->getPost('eventId');

        $eventDetail = $event->select('eventId, event_name, location, description, date, start_time, end_time')
        ->where(['eventId' => $eventId, 'is_active' => 1])->get()->getRow();

        if($eventDetail)
        {
            $arr['kamarApps']['status_code'] = 201;
            $arr['kamarApps']['success'] = true;
            $arr['kamarApps']['message'] = "Success get Data";
            $arr['kamarApps']['results'] = $eventDetail;
        }
        else
        {
            $arr['kamarApps']['status_code'] = 404;
            $arr['kamarApps']['success'] = false;
            $arr['kamarApps']['message'] = "Data is not found";
        }

        return $this->response->setJSON($arr);
    } 

	public function update()
    {
        $event = new EventsModel();
        
		$data = [
			'event_name'  	=> $this->request->getPost('eventName'),
			'location'		=> $this->request->getPost('location'),
			'description'	=> $this->request->getPost('description'),
			'date'			=> date('Y-m-d', strtotime($this->request->getPost('date'))),
			'start_time'	=> date('H:i', strtotime($this->request->getPost('startTime'))),
			'end_time'		=> date('H:i', strtotime($this->request->getPost('endTime')))	
		];


		$event->set($data);
		$event->where('eventId', $this->request->getPost('eventId'));
		$event->update();

		$response = [
			'success' => true,
			'message' => 'Data has been save'
		];
	
		return $this->response->setJSON($response);
	}

    public function destroy()
    {
        $event = new EventsModel();

        $eventId = $this->request->getPost('eventId');
  
        $data = [
            'is_active'  => 0
        ];

        $event->set($data);
        $event->where('eventId', $eventId);
        $event->update();

        $response = [
            'success' => true,
            'message' => 'Data has been deleted'
        ];
      
        return $this->response->setJSON($response);
    }

    public function qrcode()
    {
        $eventId = $this->request->getPost('eventId');

        // Check QR Code PNG on Folder Qr Code

        if(!file_exists(ROOTPATH.'/public/qrcode'))
        {
            mkdir(ROOTPATH.'/public/qrcode', 0777, true);
           
        }
       
        if(!file_exists(ROOTPATH.'/public/qrcode/'.$eventId.'.png'))
        {
            $this->makeQrCode($eventId);
        }

        $response = [
            'apps'      => 'presensi',
            'status_code' => 201,
            'success' => true,
            'data'  => [
                'image' => 'qrcode/'.$eventId.'.png'
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->response->setJSON($response);
    }

    private function makeQrCode($id)
    {
        $writer = new PngWriter();

        $config         = new \Config\Encryption();
        $config->key    = 'aBigsecret_ofAtleast32Characters';
        $config->driver = 'OpenSSL';
        $config->digest = 'SHA512';

        $encrypter = \Config\Services::encrypter($config);

        $enkripsiData = $encrypter->encrypt(strval($id));

        $base64Encrypt = base64_encode($enkripsiData);
        // echo json_encode(array("password_hash"  => $base64Encrypt, "password_decrypt" => $encrypter->decrypt(base64_decode($base64Encrypt))));
      
        // Create QR code
        $qrCode = QrCode::create($base64Encrypt)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->setSize(300)
        ->setMargin(10)
        ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);

        header('Content-Type: '.$result->getMimeType());
        // echo $result->getString();

        // Save it to a file
        $result->saveToFile(ROOTPATH.'/public/qrcode/'.$id.'.png');

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();

        return true;
    }


}
