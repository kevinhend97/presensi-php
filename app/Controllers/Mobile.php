<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Mobile extends BaseController
{
	public function index()
	{
		if(session('role') != 3)
		{
			return view('errors/html/error_404');
		}

		$data['pages'] = "Home";

		return view('mobile/home', $data);
	}
}
