<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendancesModel extends Model
{
	protected $table                = 'tblt_attendance';
	protected $primaryKey           = 'id';
	protected $allowedFields        = ['attendanceId', 'userId', 'eventId', 'attendanceStatus', 'attachment', 'message'];

}
