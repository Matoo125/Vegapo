<?php 

namespace app\controllers\admin;

use app\core\Controller; 

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
        rename($path, ROOT.DS.'pages'.DS.COUNTRY_CODE.DS.'backup'.DS.$_POST['page'].'-'.date('Y-m-d H-i-s').'.md');
        if (file_put_contents($path, $_POST['content'])) {
            $this->data['response'] = "Page has been saved";      
        } else {
            $this->data['response'] = "Error while saving";
        }
    }

}