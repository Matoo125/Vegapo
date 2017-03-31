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
    	
    }

    public function test(){}

    public function kontakt()
    {
        echo 'contact';
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



    public function separate_files()
    {
        if (!file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk')) {
            mkdir(ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk', 0777, true);
        }

        if (!file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk' . DS . '150x150')) {
            mkdir(ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk' . DS . '150x150', 0777, true);            
        }

        if (!file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk' . DS . '450x450')) {
            mkdir(ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk' . DS . '450x450', 0777, true);            
        }

        if (!file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz')) {
            mkdir(ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz', 0777, true);
        }

        if (!file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz' . DS . '150x150')) {
            mkdir(ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz' . DS . '150x150', 0777, true);            
        }

        if (!file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz' . DS . '450x450')) {
            mkdir(ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz' . DS . '450x450', 0777, true);            
        }
        

        // get all filenames from database

        $sql = "SELECT image FROM products WHERE country = 'sk'";
        $skFileNames = $this->model->runQuery($sql, null, "get");
        $sql = "SELECT image FROM products WHERE country = 'cz'";
        $czFileNames = $this->model->runQuery($sql, null, 'get');
        //echo '<pre>'; print_r($skFileNames); print_r($czFileNames);

        // move them to new folder

        foreach ($skFileNames as $filename) {
            if (file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'])) {
                rename(
                    ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'], 
                    ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk' . DS . $filename['image']
                );
            }

            if (file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb')) {

                rename(
                    ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb', 
                    ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk' . DS . '150x150' . DS . $filename['image']
                );
            }

            if (file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb450x450')) {

                rename(
                    ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb450x450', 
                    ROOT . DS . 'uploads' . DS . 'products' . DS . 'sk' . DS . '450x450' . DS . $filename['image']
                );
            }

        }


        foreach ($czFileNames as $filename) {
            if (file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'])) {
                rename(
                    ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'], 
                    ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz' . DS . $filename['image']
                );
            }


            if (file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb')) {

                rename(
                    ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb', 
                    ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz' . DS . '150x150' . DS . $filename['image']
                );
            }

            if (file_exists(ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb450x450')) {

                rename(
                    ROOT . DS . 'uploads' . DS . 'products' . DS . $filename['image'] . '-thumb450x450', 
                    ROOT . DS . 'uploads' . DS . 'products' . DS . 'cz' . DS . '450x450' . DS . $filename['image']
                );
            }


        }

        // move thumbnails

    }


}