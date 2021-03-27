<?php

namespace App\Models;

use CodeIgniter\Model;

class EventsModel extends Model
{
	protected $table                = 'tblm_events';
	protected $primaryKey           = 'eventId';
	protected $allowedFields        = ['event_name', 'location', 'description', 'date', 'start_time', 'end_time', 'is_active'];
}
