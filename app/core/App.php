<?php

namespace app\core;

use m4\m4mvc\core\App as FrameworkApp;

use app\helper\Redirect;
use app\controllers\api\Users;

class App extends FrameworkApp
{
    public function __construct ()
    {
        if (isset($_GET['url']) && substr($_GET['url'], 0, 5) == 'admin'){
           if (!Users::check_premission(30)) redirect('/users/login'); // if not admin redirect
        } 

        new \app\string\Url(); 
    }

}
