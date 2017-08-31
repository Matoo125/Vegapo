<?php

namespace app\controllers\admin;

use app\controllers\api\Suggestions as SuggestionsApiController;
use m4\m4mvc\helper\Session;
use app\controllers\api\Users;

class Suggestions extends SuggestionsApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!Users::check_premission(30)) redirect('/');
    }

    public function index()
    {
        // list all suggestions
        $this->data['suggestions'] = $this->model->getAll();

    }

    public function solved($action, $suggestion_id)
    {
        if ($action == 'accepted') {
            $state = 1;
        } else {
            $state = 2;
        }

        $this->model->updateState($suggestion_id, $state);

        redirect('/admin/suggestions');

    }


}
