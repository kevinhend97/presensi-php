<?php namespace App\Controllers;

use \App\Models\UsersModel;

class Auth extends BaseController
{
	public function __construct()
	{
		$this->request = service('request');
		$this->session = \Config\Services::session();
	}

	public function index()
	{
		return view('auth/login');
	}

	public function auth()
	{
		$user = new UsersModel();

		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		$checkUsername = $user->where('username', $username)->first();

		if($checkUsername)
		{
			if (password_verify($password, $checkUsername['password'])){
				if($checkUsername['roleId'] == 1)
				{
					$userData = [
						"userId"	=> $checkUsername['userId'],
						"username"	=> $checkUsername['username'],
						"name"		=> $checkUsername['name'],
						"gender"	=> $checkUsername['gender'],
						"role"		=> $checkUsername['roleId'],
						"loggedIn"	=> true
					];

					$this->session->set($userData);

					$response = [
						'status'		=> 200,
						'message'		=> 'Success'
					];
				}
				else
				{
					$response = [
						'status' => 403,
						'message' => 'You dont have access !'
					];	
				}
			} else {
				$response = [
					'status' => 505,
					'message' => 'Password is Wrong !'
				];
			}
		}
		else
		{
			$response = [
                'status' => 404,
                'message' => 'Username is not registered !'
            ];
		}

		return $this->response->setJSON($response);
	}

	//--------------------------------------------------------------------

}
