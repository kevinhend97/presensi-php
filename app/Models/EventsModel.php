<?php

namespace App\Models;

use CodeIgniter\Model;

class EventsModel extends Model
{
	public $db;
	public $builder;

	protected $table                = 'tblm_events';
	protected $primaryKey           = 'eventId';
	protected $allowedFields        = ['event_name', 'location', 'description', 'date', 'start_time', 'end_time', 'is_active'];

	public function __construct()
	{
		parent::__construct();
	}

	protected function _get_datatables_query($table, $column_order,$column_search,$order)
	{
		// variable form search
		$event = $this->request->getPost('event');
		$location = $this->request->getPost('location');
		$date = date('Y-m-d', strtotime($this->request->getPost('date')));

		$this->builder = $this->db->table($table);
		// kamu dapat menambahkan Join disini

		$i = 0;
		foreach($column_search as $item)
		{
			if($_POST['search']['value'])
			{
				if($i === 0)
				{
					$this->builder->groupStart();
					$this->builder->like($item,$_POST['search']['value']);
				}
				else
				{
					$this->builder->orLike($item,$_POST['search']['value']);
				}

				if(count($column_search) - 1 == $i)
					$this->builder->groupEnd();
			}	
			$i++;
		}

		if(isset($_POST['order']))
		{
			$this->builder->orderBy($column_order[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}
		else if(isset($order))
		{
			$order = $order;
			$this->builder->orderBy(key($order),$order[key($order)]);
		}
	}

	public function get_datatables($table, $column_order, $column_search,$order,$data = '',$like = '')
	{
		$this->_get_datatables_query($table, $column_order, $column_search,$order);

		if($_POST['length'] != -1)
			$this->builder->limit($_POST['length'],$_POST['start']);

		$event = $this->request->getPost('event');
		$location = $this->request->getPost('location');
		$date = date('Y-m-d', strtotime($this->request->getPost('date')));

		if($data)
		{
			$this->builder->where($data);
		}

		if($like)
		{
			if($like[0])
			{
				$this->builder->like('event_name',$like[0]);
			}

			if($like[1])
			{
				$this->builder->like('location',$like[1]);
			}

			if($like[2])
			{
				$this->builder->like('location',$like[2]);
			}
		}
		
		$query = $this->builder->get();
		return $query->getResult();
	}

	public function count_filtered($table, $column_order, $column_search,$order,$data = '',$like = '')
	{
		$this->_get_datatables_query($table, $column_order, $column_search,$order);

		$event = $this->request->getPost('event');
		$location = $this->request->getPost('location');
		$date = date('Y-m-d', strtotime($this->request->getPost('date')));
		
		if($data)
		{
			$this->builder->where($data);
		}

		if($like)
		{
			if($like[0])
			{
				$this->builder->like('event_name',$like[0]);
			}

			if($like[1])
			{
				$this->builder->like('location',$like[1]);
			}

			if($like[2])
			{
				$this->builder->like('location',$like[2]);
			}
		}

		$this->builder->get();

		return $this->builder->countAll();
	}

	public function count_all($table, $data='',$like = '')
	{
		$event = $this->request->getPost('event');
		$location = $this->request->getPost('location');
		$date = date('Y-m-d', strtotime($this->request->getPost('date')));

		if($data)
		{
			$this->builder->where($data);
		}

		if($like)
		{
			if($like[0])
			{
				$this->builder->like('event_name',$like[0]);
			}

			if($like[1])
			{
				$this->builder->like('location',$like[1]);
			}

			if($like[2])
			{
				$this->builder->like('location',$like[2]);
			}
		}

		$this->builder->from($table);

		return $this->builder->countAll();
	}


}
