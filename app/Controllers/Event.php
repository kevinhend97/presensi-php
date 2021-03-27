<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use \App\Models\EventsModel;
use \App\Models\ServersideModel;

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
        $where = ['is_active' => 1];
                //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
                //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama
        $column_order = array('timestamp','event_name','location', 'date');
        $column_search = array('event_name','location', 'date');
        $order = array('timestamp' => 'desc');
        $list = $list_data->get_datatables('tblm_events', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = date('Y-m-d H:i:s', strtotime($lists->timestamp));
            $row[] = $lists->event_name;
            $row[] = $lists->location;
            $row[] = date('Y-m-d', strtotime($lists->date));
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
        $role->update();

        $response = [
            'success' => true,
            'message' => 'Data has been deleted'
        ];
      

        return $this->response->setJSON($response);
    }


}
