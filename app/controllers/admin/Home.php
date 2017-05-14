<?php

namespace app\controllers\admin;

use app\controllers\api\Home as ApiHomeController;

/*
 * Home Admin Controller
 * extends Home API Controller
 * use Dashboard model
 */
class Home extends ApiHomeController
{
	/*
	 * Binds statistics to Dashboard view
	 */
	public function index()
	{
        $this->data['numberOfProducts'] = $this->model->countTable('products', array('visibility' => 1));
        $this->data['numberOfRequests'] = $this->model->countTable('products', array('visibility' => 2));
        $this->data['numberOfStores'] = $this->model->countTable('supermarkets');
        $this->data['numberOfCategories'] = $this->model->countTable('categories');
        $this->data['numberOfUsers'] = $this->model->countTable('users');
        $this->data['numberOfEmails'] = $this->model->countTable('newsletter');
        $this->data['numberOfMessages'] = $this->model->countTable('contact');
				$this->data['numberOfMessagesAnswered'] = $this->model->countTable('contact', array('type' => 1));
        $this->data['numberOfSuggestions'] = $this->model->countTable('suggestions');
				$this->data['numberOfSuggestionsHandled'] = $this->model->countTable('suggestions', array('state' => 1, 'state' => 2));
   	}

   	public function changelog()
   	{
        $mdFile = file_get_contents(ROOT . DS . "changelog.md");
        $Parsedown = new \Parsedown();
        $this->data['parsedown'] = $Parsedown->text($mdFile);
        $this->data['title'] = "Version Control";
   	}
}
