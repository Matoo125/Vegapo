<?php

namespace app\core;

use m4\m4mvc\core\App as FrameworkApp;

use app\helper\Redirect;

class App extends FrameworkApp
{
    public function __construct ()
    {
        if (isset($_GET['url']) && substr($_GET['url'], 0, 5) == 'admin'){
           if (!check_user_premission(30)) redirect('/users/login'); // if not admin redirect
        } 
    }

}
