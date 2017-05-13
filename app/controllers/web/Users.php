<?php 

namespace app\controllers\web;

use app\controllers\api\Users as UsersApiController;
use app\core\Session;
use app\helper\Redirect;


class Users extends UsersApiController
{
	public function index()
	{
		if (! Session::get('user_id')) {
			
			 Redirect::toURL('LOGIN');
		}
	}

	public function zabudnute_heslo()
	{
		if (!$_POST) {
			return;
		}

		// check email in database
		if (!$user = $this->model->getByEmail($_POST['email'])) {
            Session::setFlash(getString('EMAIL_NOT_FOUND'), "warning");
            return;
		}
		// create token and store it in database
		$token = bin2hex(random_bytes(40));
		$this->model->set_forgotten_password_token($user['user_id'], $token);

		$link = "http://vegapo." . COUNTRY_CODE . "/users/obnovenie_hesla/" . $user['user_id'] . "/" . $token;

		// send token with email
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
		$mail->addAddress($user['email'], $user['username']);
		$mail->Subject 		=	getString("RECOVER_PASSWORD");
		$mail->Body 		=	getString("GREETING") . " " . $user['username'] . " " . getString("RECOVER_PASSWORD_MESSAGE") . " " . "<a href='".$link."'>Zmeniť heslo<a>";

		if(!$mail->send()) {
			Session::setFlash($mail->ErrorInfo, 'danger');
		} else {
			Session::setFlash(getString('RECOVERY_EMAIL_SENT'), 'success');
		}

		// return sucess message

	}

	public function obnovenie_hesla($user_id = null, $token = null)
	{
		if($_POST) {

			if ($_POST['password'] != $_POST['password2']) {
				Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'warrning');
				return;
			}

			if ($this->model->updatePassword(password_hash($_POST['password'], PASSWORD_DEFAULT), $user_id)) {
				Session::setFlash(getString('PASSWORD_CHANGED'), 'success');

				// delete token
				$this->model->delete_forgotten_password_token($user_id, $token);

				return;
			} else {
				Session::setFlash(getString('PASSWORD_UPDATE_ERROR'), 'danger');
			}
		}

		if ($this->model->check_forgotten_password_token($user_id, $token)) {
			$this->data['token'] = true;
		} else {
			Session::setFlash(getString('INVALID_TOKEN'), 'danger');
		}
	}

}