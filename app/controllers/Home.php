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

    public function admin_rename_images()
    {
        // get all products
        $products = $this->model->runQuery("SELECT * FROM products", array(), "get");
        $dircontent = array_slice(scandir(ROOT . DS . "uploads" . DS . "products"), 2);

        foreach ($dircontent as $key => $value) {
            $image = $value;
            $explode = explode(".", $image);
            $extension = end($explode);
            $filename = substr($image, 0, strlen($image) - (strlen($extension) + 1));
            $slugname = slugify($filename) . "." . $extension;
            rename((ROOT . DS . "uploads" . DS . "products" . DS . $image), (ROOT . DS . "uploads" . DS . "products" . DS . strtolower($slugname)));
        }


        foreach ($products as $product) {
            # code
            $image = strtolower($product['image']);
            $explode = explode(".", $image);
            $extension = end($explode);
            $filename = substr($image, 0, strlen($image) - (strlen($extension) + 1));
            $slugname = slugify($filename) . "." . $extension;

            if ($slugname == "none.none") {
                $slugname = NULL;
            }
            //update database records
            $sql = "UPDATE products SET image = :image WHERE id = :id";
            $array = array("image" => $slugname, "id" => $product['id']);
            $this->model->runQuery($sql, $array, "post");
            //rename files
            // rename((APP . DS . "uploads" . DS . "products" . DS . $image), (APP .DS. "uploads" . DS . "products" . DS . $slugname) );

        }
    }

    public function admin_create_matching_table()
    {
        // get all products
        $products = $this->model->runQuery("SELECT * FROM products", array(), "get");
        $supermarkets = $this->model->runQuery("SELECT * FROM supermarkets", array(), "get");

        foreach ($products as $product) {
            $product_supermarkets_string = $product['supermarkets'];
            $product_supermarkets_array = explode(", ", $product_supermarkets_string);

            foreach ($product_supermarkets_array as $product_name) {
                //echo $product_supermarket;
                foreach ($supermarkets as $supermarket) {

                    if ($product_name == $supermarket['name']) {
                        // create matching table
                        $sql = "INSERT INTO `matching_supermarkets` (id, id,country) VALUES (:id, :id,:country)";
                        $params = array(
                            ":id" => $product['id'],
                            ":id" => $supermarket['id'],
                            ":country" => $product['country']
                        );
                        if ($this->model->runQuery($sql, $params, "post")) {
                            unset($products[$product['id']]);
                        }

                    }

                }

            }

            //echo "<pre>";
            // print_r($supermarkets);
            //print_r($product_supermarkets_array);
        }
    }

    public function admin_generate_missing_slugs()
    {
        $db_tables = array(
            'products' => 'name',
            'categories' => 'name',
            'supermarkets' => 'name'
        );

        foreach ($db_tables as $table => $column) {
            $params = array();
            $query = "SELECT * FROM " . $table . " WHERE slug IS NULL";

            $rows = $this->model->runQuery($query, $params, "get");

            foreach ($rows as $row) {
                $slug = slugify($row[$column]);
                //echo "<br>" . $slug;
                $query = "UPDATE " . $table . " SET slug = :slug WHERE " . $column . " = :" . $column;
                $params = array(
                    ':slug' => $slug,
                    ':' . $column => $row[$column]
                );
                $this->model->runQuery($query, $params, "post");
            }


        }
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

        $mdFile = file_get_contents(ROOT . DS . "version-control.md");

        $Parsedown = new Parsedown();
        $this->data['parsedown'] = $Parsedown->text($mdFile);
        $this->data['title'] = "Version Control";
    }

    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }


}
