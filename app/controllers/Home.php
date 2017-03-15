<?php

class Home extends Controller
{
    private $model;
    private $view;
    private $data;

    public function __construct()
    {
        $this->model = $this->model('Dashboard');
    }

    public function index()
    {
        $this->view = 'public/home/index';

    }

    public function admin_index()
    {
        $this->view = 'admin/home/index';

        $this->data['numberOfProducts'] = $this->model->countTable('products', array('visibility' => 1));
        $this->data['numberOfRequests'] = $this->model->countTable('products', array('visibility' => 2));
        $this->data['numberOfStores'] = $this->model->countTable('supermarkets');
        $this->data['numberOfCategories'] = $this->model->countTable('categories');

    }

    public function admin_settings()
    {
        $this->view = 'admin/home/settings';
        $this->data['title'] = "Settings";

    }


    public function admin_generate_missing_thumbnails()
    {
        $this->admin_settings();

        $directories = array_slice(scandir(ROOT . UPLOADS), 2);

        $files_to_resize = [];


        foreach ($directories as $directory) {
            $dir_content = array_slice(scandir(ROOT . UPLOADS . $directory), 2);

            foreach ($dir_content as $item => $value) {
                if (substr($value, -5) == "thumb") {
                    unset($dir_content[$item]);
                } else {
                    // resize here
                    $path = ROOT . UPLOADS . $directory . DS . $value;

                    if (file_exists($path)) {
                        //  generateThumbnail($path, 450, 450, "450x450");
                        array_push($files_to_resize, $path);
                    }
                }
            }

        }

        $count = 0;

        foreach ($files_to_resize as $file_to_resize) {
            if (substr($file_to_resize, -7) != "450x450" && !file_exists($file_to_resize . "-thumb450x450")) {
                $this->model->generateThumbnail($file_to_resize, 450, 450, "450x450");
                $count++;
            }

        }

        if ($count === 0) {
            Session::setFlash("All images are already resized", 'success');
        } else {
            Session::setFlash($count . " images resized", 'success');
        }

        $this->data['title'] = "Generate missing thumbnails";
        $this->data['action'] = "generate_missing_thumbnails";
        //redirect('admin/home/settings');

    }

    public function admin_changelog()
    {
        $this->view = "/admin/home/changelog";

        $mdFile = file_get_contents(ROOT . DS . "changelog.md");

        $Parsedown = new Parsedown();
        $this->data['parsedown'] = $Parsedown->text($mdFile);
        $this->data['title'] = "Version Control";
    }

    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }


}
