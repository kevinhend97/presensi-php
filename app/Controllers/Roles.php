<?php namespace App\Controllers;

use Codeigniter\HTTP\IncomingRequest;
use \App\Models\RolesModel;

class Roles extends BaseController
{
    public function index()
    {
        return view('roles/index');
    }

    public function list()
    {
        $role = new RolesModel();

        $roleList =  $role->select('roleId, role')
                        ->where('is_active', 1)
                        ->orderBy('roleId', 'ASC')->get()->getResult();

        $arr['kamarApps'] = array();

        if($roleList)
        {
            foreach($roleList as $list)
            {
                $arr['kamarApps']['results'][] = array(
                        "roleId"   => $list->roleId,
                        "role"     => $list->role
                );
            }
            $arr['kamarApps']['count'] = count($roleList);
        }
        else
        {
            $arr['kamarApps']['results'] = "Data is NULL";
        }
        

        return $this->response->setJSON($arr);
                    
    }
	
    public function store()
    {
        $role = new RolesModel();

        $roleName = $this->request->getPost('roleName');

        $checkRole = $role->where('role', strtoupper($roleName))->first();

        if($checkRole)
        {
            $response = [
                'success' => false,
                'message' => 'Data is Existing'
            ];
        }
        else
        {
            $data = [
                'role'  => strtoupper($roleName)
            ];

            $role->insert($data);

            $response = [
                'success'   => true,
                'message'   => 'Data has been save' 
            ];
        }

        return $this->response->setJSON($response);
    }

    public function update()
    {
        $role = new RolesModel();

        $roleId = $this->request->getPost('roleId');

        $roleName = $this->request->getPost('roleName');

        $checkRole = $role->where('role', strtoupper($roleName))->first();

        if($checkRole)
        {
            $response = [
                'success' => false,
                'message' => 'Data is Existing'
            ];
        }
        else
        {
            $data = [
                'role'  => strtoupper($roleName)
            ];

            $role->set($data);
            $role->where('roleId', $roleId);
            $role->update();

            $response = [
                'success' => true,
                'message' => 'Data has been save'
            ];
        }

        return $this->response->setJSON($response);
    }

    public function destroy()
    {
        $role = new RolesModel();

        $roleId = $this->request->getPost('roleId');
  
        $data = [
            'is_active'  => 0
        ];

        $role->set($data);
        $role->where('roleId', $roleId);
        $role->update();

        $response = [
            'success' => true,
            'message' => 'Data has been deleted'
        ];
      

        return $this->response->setJSON($response);
    }
	//--------------------------------------------------------------------

}
