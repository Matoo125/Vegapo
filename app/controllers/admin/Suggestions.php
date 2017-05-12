<?php 

namespace app\controllers\admin;

use app\controllers\api\Suggestions as SuggestionsApiController;
use app\core\Session;

class Suggestions extends SuggestionsApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!check_user_premission(35)) redirect('/');
    }

    public function index()
    {
        // list all suggestions
        $this->data['suggestions'] = $this->model->getAll();

        $this->data['matchingType'] = [
            0 => 'supermarket',
            1 => 'kategoria',
            2 => 'tag',
            3 => 'obrazok',
            4 => 'ingrediencie obrazok',
            5 => 'iny obrazok',
            6 => 'poznamka',
            7 => 'zlozenie',
            8 => 'ciarovy kod',
            9 => 'nahlasenie',
            10 => 'nieco ine'
        ];
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