<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    // ...
    protected $table = "tblm_users";
    protected $primarykey = "userId";

    protected $allowedFields = ['userId','gender','roleId','name','username','password','is_active'];
}