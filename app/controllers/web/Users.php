<?php 

namespace app\controllers\web;

use app\controllers\api\Users as UsersApiController;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Redirect;


class Users extends UsersApiController
{
	public function index()
	{
		if (! Session::get('user_id')) {
			 Redirect::toURL('LOGIN');
		}
	}

}