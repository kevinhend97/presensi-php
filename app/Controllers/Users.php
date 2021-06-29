<?php namespace App\Controllers;

use \App\Models\UsersModel;
use \App\Models\RolesModel;
use \App\Models\ServersideModel;

class Users extends BaseController
{

    public function index()
    {
        if(session('role') == 3)
		{
			return view('errors/html/error_404');
		}

        $data['pages'] = "Member";
        
        return view('user/index', $data);
    }

    public function listdata()
    {
 
        $request = \Config\Services::request();

        $list_data = new ServersideModel();
        $where = ['is_active' => 1, "roleId" => 3];
        //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
        //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama

        $column_order = array('timestamp','name','username', 'gender');
        $column_search = array('name','username');
        $order = array('userId' => 'desc');
        $list = $list_data->get_datatables('tblm_users', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->getPost("start");

        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = date('d/m/Y H:i:s', strtotime($lists->timestamp)). " WIB";
            $row[] = '<a href="javascript:void(0)" onclick="edit('.$lists->userId.')">'.$lists->name."</a>";
            $row[] = $lists->username;
            $row[] = $lists->gender == 1 ? "MALE" : "FEMALE";
            $row[] = '<button type="button" onclick="destroy('.$lists->userId.')" class="btn btn-danger text-light"><i class="cil-ban"></i></button>';
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all('tblm_users', $where),
            "recordsFiltered" => $list_data->count_filtered('tblm_users', $column_order, $column_search, $order, $where),
            "data" => $data,
        );
 
        return json_encode($output);
    }

	public function list()
    {
        $user = new UsersModel();

        $userList =  $user->select('userId,name, username,password,gender')
                        ->where(['is_active' => 1, 'roleId' => 3])
                        ->orderBy('roleId', 'ASC')->get()->getResult();

        $arr['kamarApps'] = array();
		
        if($userList)
        {
            $arr['kamarApps']['status_code'] = 201;
			$arr['kamarApps']['success'] = true;
			$arr['kamarApps']['message'] = "Success get Data";
			$arr['kamarApps']['count'] = count($userList);
			$arr['kamarApps']['information'] = array(
				"apps"				=> "Presensi",
				"access_date"		=> date('d-m-Y') 
			);
            foreach($userList as $list)
            {
                $arr['kamarApps']['results'][] = array(
                        "userId"   	=> $list->userId,
                        "name"     	=> $list->name,
						"username"	=> $list->username,
                        "gender"    => $list->gender == 1 ? "MALE" : "FEMALE",
                );
            }
            $arr['kamarApps']['count'] = count($userList);
        }
        else
        {
            $arr['kamarApps']['results'] = "Data is NULL";
        }
        

        return $this->response->setJSON($arr);
                    
    }

    public function listSelect()
    {
        $user = new UsersModel();

        $userList =  $user->select('userId,name, username,password')
                        ->where(['is_active' => 1, 'roleId' => 3])
                        ->orderBy('roleId', 'ASC')->get()->getResult();   
        
        if($userList)
        {
            foreach($userList as $list)
            {
                $arr[] = array(
                    "id"   	=> $list->userId,
                    "text"     	=> $list->name,
                );
            }
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
				'roleId'	=> 3,
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

    public function edit()
    {
        $users = new UsersModel();
        $userId = $this->request->getPost('userId');

        $userDetail = $users->select('userId, name, gender, username')
        ->where(['userId' => $userId, 'is_active' => 1])->get()->getRow();

        if($userDetail)
        {
            $arr['kamarApps']['status_code'] = 201;
            $arr['kamarApps']['success'] = true;
            $arr['kamarApps']['message'] = "Success get Data";
            $arr['kamarApps']['results'] = $userDetail;
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
        $user = new UsersModel();

        $username = $this->request->getPost('username');

        $checkUsername = $user->where('username', $username)->first();

        $data = [
            'name'		=> $this->request->getPost('name'),
            'gender'		=> $this->request->getPost('gender'),
            'password'	=> password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        if(!$checkUsername)
        {
            $data['username'] = $username;     
        }
       

        $user->set($data);
        $user->where('userId', $this->request->getPost('userId'));
        $user->update();

        $response = [
            'success' => true,
            'message' => 'Data has been save'
        ];

        return $this->response->setJSON($response);
    }

    public function reset($username)
    {
        $user = new UsersModel();

        $checkUsername = $user->where('username', $username)->first();

        $data = [
            'password'	=> password_hash('12345678', PASSWORD_DEFAULT)
        ];

        if(!$checkUsername)
        {
            $response = [
                'success' => false,
                'message' => 'Username Not Exist on database'
            ];

            return $this->response->setJSON($response);
            die();
        }
       

        $user->set($data);
        $user->where('username', $username);
        $user->update();

        $response = [
            'success' => true,
            'message' => 'Data has been save'
        ];

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
