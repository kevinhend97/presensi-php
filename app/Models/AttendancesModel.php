<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendancesModel extends Model
{
	protected $table                = 'attendances';
	protected $primaryKey           = 'id';
	protected $allowedFields        = ['attendanceId', 'userId', 'eventId', 'attendanceTatus', 'attachment', 'message'];

}
