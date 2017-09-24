<?php

namespace app\controllers\admin;

use app\core\Controller;
use app\model\Edit;
use mrkovec\sdiff\SDiff;

class Info extends Controller
{

    public function index()
    {
        $pages = array_slice(scandir(ROOT.DS.'pages'.DS.COUNTRY_CODE), 3);
        $this->data['pages'] = $pages;
    }

    public function edit($page)
    {
        $path = ROOT.DS.'pages'.DS.COUNTRY_CODE.DS.$page;
        if (file_exists($path)) {
            $this->data['content'] = file_get_contents($path);
            $this->data['page'] = $page;
        } else {
            $this->data['content'] = "Page not found. ";
        }
    }

    public function saveAjax()
    {
        if (!$_POST) return null;
        $path = ROOT.DS.'pages'.DS.COUNTRY_CODE.DS.$_POST['page'];
        // backup current page
        $backup_path = ROOT.DS.'pages'.DS.COUNTRY_CODE.DS.'backup'.DS.$_POST['page'].'-'.date('Y-m-d H-i-s').'.md';
        rename($path, $backup_path);

        if (file_put_contents($path, $_POST['content'])) {
            $this->data['response'] = "Page has been saved";

            // log edit
            $edit = new Edit();
            $data['type'] = 'update';
            $data['object_type'] = "info";
            $edit_id = $edit->newEdit($data);
            $diff = SDiff::getClauseDiff(
              file_get_contents($backup_path),
              file_get_contents($path),
              False
            );
            $edit->closeEdit($edit_id, $_POST['page'], $diff["diff"]);

        } else {
            $this->data['response'] = "Error while saving";
        }
    }

}
