<?php

namespace app\controllers\admin;

use app\controllers\api\Newsletter as Controller;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Redirect;
use app\model\Newsletter as Model;
use app\helper\Email;

class Newsletter extends Controller
{

  public $path = null;

  public function __construct()
  {
    $this->path['base'] = ROOT . DS . UPLOADS . DS . 'newsletter';
    $this->path['new'] = $this->path['base'] . DS . 'new';
    $this->path['send'] = $this->path['base'] . DS . 'send';
    $this->path['processing'] =$this->path['base'] . DS . 'processing';

    foreach ($this->path as $path) {
      if (!file_exists($path)) mkdir($path, 0755, true);
    }

    parent::__construct();
  }

  public function index ()
  {
    $folders = $this->path;
    foreach ($folders as $state => $folder) {
      if ($state === 'base') continue;
      $files = array_diff(scandir($folder), ['.','..']);
      foreach ($files as $file) {
        $this->data['newsletters'][] = [
          'name'  =>  $file,
          'state' =>  $state
        ];
      }
    }
  }

  public function upload ()
  {
    if (!$_POST && !$_FILES && !isset($_FILES['newsletter'])) return;

    $newsletter = $_FILES['newsletter'];

    $upload = move_uploaded_file(
      $newsletter['tmp_name'],
      $this->path['new'] . DS . $newsletter['name']
    );

    if ($upload) {
      // log edit
      $this->model->createEdit($newsletter['name']);

      Session::setFlash('Newsletter uploaded successfully', "success");
    } else {
      Session::setFlash('Error while uploading newsletter', 'danger');
    }

  }

  public function detail ($newsletter)
  {
    // get all from history
    $this->data['history'] = Model::history($newsletter);
    // get those who has not received email
    $this->data['notReceived'] = Model::notReceived($newsletter);
    // get html of an email
    foreach ($this->path as $state => $path) {
      if (file_exists($path . DS . $newsletter)) {
        $this->data['link'] = $state . '/' . $newsletter;
      }
    }
  }

  public function send ($newsletter, $products = false)
  {
    $recipients = Model::notReceived($newsletter);
    if (!$recipients) {  echo 'no recipients';die; }

    if (file_exists($this->path['new'] . DS . $newsletter)) {
      rename(
        $this->path['new'] . DS . $newsletter,
        $this->path['processing'] . DS . $newsletter
      );
    }

    if (file_exists($p = $this->path['processing'] . DS . $newsletter)) {
      $content = file_get_contents($p);
    }

    else {
      Session::setFlash('File does not exists', 'danger', 1);
      Redirect::to('/admin/newsletter/index');
    }

    $loader = new \Twig_Loader_Filesystem($this->path['processing']);
    $twig = new \Twig_Environment($loader, []);

    foreach ($recipients as $recipient) {

      $template = $twig->loadTemplate($newsletter);
      $parameters = [
        'user' => ['name' => ''],
        'email' => $recipient['email'],
        'id'    => $recipient['id'],
        'country' =>  $recipient['country'],
        'base_url' => 'https://vegapo.' . $recipient['country']
      ];

      if ($products) {
        // get products
        $product = new \app\model\Product;
        $parameters['products'] = $product->list(
          ['country' => $recipient['country']]
        );
      }

      $subject  = $template->renderBlock('subject',   $parameters);
      $body['html'] = $template->renderBlock('body_html', $parameters);
      $body['text'] = $template->renderBlock('body_text', $parameters);

      $recipient['name'] = 'username';
      // send email
      Email::send($subject, $body, $recipient);
      // add row to newsletter history
      Model::addToHistory($newsletter, $recipient['email']);
    }

    rename(
      $this->path['processing'] . DS . $newsletter,
      $this->path['send'] . DS . $newsletter
    );

    Session::setFlash('Newsletter has been sent to everyone.', "success", 1);
    Redirect::to('/admin/newsletter/detail/' . $newsletter);

  }

}
