<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model
{
    // ...
    protected $table = "tblm_roles";
    protected $primarykey = "roleId";

    protected $allowedFields = ['role', 'is_active'];
}