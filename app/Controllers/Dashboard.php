<?php namespace App\Controllers;

class Dashboard extends BaseController
{
	public function index()
	{
		if(session('role') == 3)
		{
			return view('errors/html/error_404');
		}
		
        $data['pages'] = "Dashboard";
		return view('dashboard/index', $data);
	}

	//--------------------------------------------------------------------

}
