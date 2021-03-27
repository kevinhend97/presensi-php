<?php namespace App\Controllers;

class Dashboard extends BaseController
{
	public function index()
	{
        $data['pages'] = "Dashboard";
		return view('dashboard/index', $data);
	}

	//--------------------------------------------------------------------

}
