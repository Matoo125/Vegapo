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
        $this->data['numberOfProducts'] = $this->model->countTableCS('products', array('visibility' => 1));
        $this->data['numberOfRequests'] = $this->model->countTableCS('products', array('visibility' => 2));
        $this->data['numberOfTrashProducts'] = $this->model->countTableCS('products', array('visibility' => 3));
        $this->data['numberOfStores'] = $this->model->countTableCS('supermarkets');
        $this->data['numberOfCategories'] = $this->model->countTableCS('categories');
        $this->data['numberOfUsers'] = $this->model->countTableCS('users');
        $this->data['numberOfEmails'] = $this->model->countTable('newsletter');
        $this->data['numberOfTestimonials'] = $this->model->countTable('testimonials');
        $this->data['numberOfMessages'] = $this->model->countTableCS('contact');
				$this->data['numberOfMessagesAnswered'] = $this->model->countTableCS('contact', array('type' => 1));
        $this->data['numberOfSuggestions'] = $this->model->countTableCS('suggestions');
				$this->data['numberOfSuggestionsHandled'] = $this->model->countTableCS('suggestions', array(), " AND (state = 1 OR state = 2)");


   	}

   	public function changelog()
   	{
        $mdFile = file_get_contents(ROOT . DS . "changelog.md");
        $Parsedown = new \Parsedown();
        $this->data['parsedown'] = $Parsedown->text($mdFile);
        $this->data['title'] = "Version Control";
   	}

    public function hosting()
    {
      if (PATH_TO_LOGS and file_exists(PATH_TO_LOGS)) {
        $this->data['log'] = file_get_contents(PATH_TO_LOGS);
      } else {
        $this->data['log'] = 'I cannot access log file';
      }

      if (HOSTING_API_KEY) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_URL, 'https://webadmin.endora.cz/api/xml/key/'.HOSTING_API_KEY);
        $result = curl_exec($c);
        curl_close($c);
        $this->data['hosting'] = json_decode($result, true);
      }
    }


}
