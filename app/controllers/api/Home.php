<?php 

namespace app\controllers\api; 

use app\core\Controller;
/*
 * Home API Controller
 * extends base Controller
 * use Dashboard model
 */
class Home extends Controller
{
	public function __construct()
    {
        $this->model = $this->model('Dashboard');
    }

    public function index()
    {
    	$testimonial = $this->model('Testimonial');
        $this->data['testimonials'] = $testimonial->getAll();
        
    }


    public function contactAjax()
    {
        if ($_POST) {
            if ($this->model->insertMessage($_POST)) {
                echo "Your message has been send. ";
            } else {
                echo "There was problem with your message. Please try again. ";
            }
        }

    }

    public function subscribe()
    {

        //print_r($_POST);
        if ($_POST) {
            // insert email to database
            $email = $_POST['email'];
           
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               echo "Sorry, but this email is not valid";
               return;
           }           
           if ($this->model->findEmailInNewsletterList($email)) {
               echo "This email is already in our subscribtion list. ";
               return;
           }

           if ($this->model->insertEmailToNewsletter($email)) {
               echo "Thank you!";
           }

        }
    }


}