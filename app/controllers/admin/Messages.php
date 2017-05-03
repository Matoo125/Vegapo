<?php 

namespace app\controllers\admin;

use app\core\Controller; 
use app\core\Session;
use app\helper\Redirect;

/*
 *  25.4.2017 Matej Vrzala
 *  Controller to handle messages
 */

class Messages extends Controller
{
    public function __construct()
    {
        $this->model = $this->model('Message');
    }

	public function index()
	{
        $this->data['messages'] = $this->model->getAll();
	}

    public function read($id)
    {
        $this->data['message'] = $this->model->getById($id);
        if ($this->data['message']['type'] == 1) {
            $this->data['answer'] = $this->model->getAnswerById($this->data['message']['id']);
        }
    }

    public function answer()
    {
        if (!$_POST) return false;
        $data['user_id'] = Session::get('user_id');
        $data['answer'] = $_POST['answer'];

        $data['subject'] = 'RE: ' . $_POST['subject'];
        $data['email_to_respond'] = $_POST['email'];
        $data['original_message'] = $_POST['message'];
        $data['question_author'] = $_POST['author'];
        $data['message_id'] = $_POST['message_id'];

        // send answer with the email
        $mail = new \PHPMailer;

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'veganpotraviny@gmail.com';
        $mail->Password = EMAIL_PASSWORD;
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('vegapo@vegapo.sk', 'Vegapo');
        $mail->addAddress($data['email_to_respond'], $data['question_author']);
        $mail->Subject      =   $data['subject'];
        $mail->Body         =   $data['answer'] . '<br><hr><br>' . $data['original_message'];

        if(!$mail->send()) {
            Session::setFlash($mail->ErrorInfo, 'danger', 1);
        } else {
            // store answer in database
            $this->model->insertAnswer($data['message_id'] ,$data['answer'], $data['user_id']);
            $this->model->toggleMessageAnswer($data['message_id']);

            // yes you did it
            Session::setFlash('YOU ANSWERED! thanks.', 'success', 1);
            Redirect::toURL("ADMIN_MESSAGES");

        }

        print_r($data);
    }

}