<?php namespace App\Controllers;

use \App\Models\UsersModel;

class Auth extends BaseController
{
	public function __construct()
	{
		$this->request = service('request');
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
				$response = [
					'kamarApps' => [
						'query'	=> [
							'status'		=> 200,
							'description'	=> 'OK'
						],
						'results' => [
							"username"	=> $checkUsername['username'],
							"name"		=> $checkUsername['name'],
							"gender"	=> $checkUsername['gender'],
							"role"		=> $checkUsername['roleId']
						]
					]
				];
			} else {
				$response = [
					'success' => false,
					'message' => 'Password is Wrong !'
				];
			}
		}
		else
		{
			$response = [
                'success' => false,
                'message' => 'Username is not registered !'
            ];
		}

		return $this->response->setJSON($response);
	}

	//--------------------------------------------------------------------

}
