<?php namespace App\Controllers;

use \App\Models\UsersModel;

class Users extends BaseController
{
	public function list()
    {
        $user = new UsersModel();

        $userList =  $user->select('userId,name, username,password')
                        ->where('is_active', 1)
                        ->orderBy('roleId', 'ASC')->get()->getResult();

        $arr['kamarApps'] = array();

        if($userList)
        {
            foreach($userList as $list)
            {
                $arr['kamarApps']['results'][] = array(
                        "userId"   	=> $list->userId,
                        "name"     	=> $list->name,
						"username"	=> $list->username
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
        $user = new UsersModel();

        $username = $this->request->getPost('username');

        $checkUsername = $user->where('username', $username)->first();

        if($checkUsername)
        {
            $response = [
                'success' => false,
                'message' => 'Username is Existing'
            ];
        }
        else
        {
            $data = [
				'roleId'	=> $this->request->getPost('roleId'),
                'username'  => $username,
				'name'		=> $this->request->getPost('name'),
				'gender'	=> $this->request->getPost('gender'),
				'password'	=> password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ];

            $user->insert($data);

            $response = [
                'success'   => true,
                'message'   => 'Data has been save' 
            ];
        }

        return $this->response->setJSON($response);
    }

    public function update()
    {
        $user = new UsersModel();

        $userId = $this->request->getPost('userId');

        $checkUsername = $username->where('username', $username)->first();

        if($checkUsername)
        {
            $response = [
                'success' => false,
                'message' => 'Username is Existing'
            ];
        }
        else
        {
            $data = [
				'roleId'	=> $this->request->getPost('roleId'),
                'username'  => $username,
				'name'		=> $this->request->getPost('name'),
				'password'	=> password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ];

            $role->set($data);
            $role->where('user', $userId);
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
        $user = new UsersModel();

        $userId = $this->request->getPost('userId');
  
        $data = [
            'is_active'  => 0
        ];

        $user->set($data);
        $user->where('userId', $userId);
        $user->update();

        $response = [
            'success' => true,
            'message' => 'Data has been deleted'
        ];
      

        return $this->response->setJSON($response);
    }

	//--------------------------------------------------------------------

}
