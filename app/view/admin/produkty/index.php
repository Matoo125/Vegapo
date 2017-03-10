
<?php include APP . DS . '/view/admin/header.php'; ?>


    <div class="row">
        <h2 class="display-4 text-center col-md-10">
            Produkty
        </h2>

        <div class="col-md-2">
            <a href="/admin/produkty/pridat"><button>Nový Produkt</button></a>
        </div>

    </div>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>

    <hr>

    <div class="row">
        <div class="col-md-12">
        <?php if(isset($data['categories'])): ?>
            <select id="select-category">
                <?php if( $data['current_category'] ): ?>
                    <option value="0"><?php echo $data['current_category']['category_name']; ?></option>
                <?php else: ?>
                    <option value="0">All Categories</option>
                <?php endif; ?>
                    
                <?php foreach($data['categories'] as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
                <?php if( $data['current_category'] ): ?>
                    <option value="0">All Categories</option>
                <?php endif; ?>
            </select>
        <?php endif; ?>

        <?php if(isset($data['supermarkets'])): ?>
            <select id="select-supermarket">

                 <?php if( $data['current_supermarket'] ): ?>
                    <option value="0"><?php echo $data['current_supermarket']['supermarket_name']; ?></option>
                <?php else: ?>
                    <option value="0">All supermarkets</option>
                <?php endif; ?>

                <?php foreach($data['supermarkets'] as $supermarket): ?>
                    <option value="<?php echo $supermarket['slug']; ?>"><?php echo $supermarket['supermarket_name']; ?></option>
                <?php endforeach; ?>
                <?php if( $data['current_supermarket'] ): ?>
                    <option value="0">All supermarkets</option>
                <?php endif; ?>

            </select>

        <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped sortable">
                    <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Názov</th>
                        <th>Kategória</th>
                        <th>Obchody</th>
                        <th>Približná cena</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($data['products'])): ?>
                        <?php foreach ($data['products'] as $item): ?>
                            <?php //if(preg_match('/Jednota/', $item['supermarket_names'])): ?>
                                <tr>

                            <td><a href="/admin/produkty/upravit/<?php echo $item['product_id']; ?>"><?php echo $item['product_id']; ?></a></td>
                            <td>
                              <?php if($item['product_image'] !== "none"): ?>
                                  <a rel="popover" data-img="<?php echo UPLOADS . "products" . DS . $item['product_image']; ?>-thumb"><?php echo $item['product_name']; ?></a>
                              <?php else: ?>
                                    <?php echo $item['product_name']; ?>
                              <?php endif; ?>
                            </td>
                            <td><?php echo $item['category']; ?></td>
                            <td><?php echo $item['supermarket_names']; ?></td>
                            <td><?php echo $item['expected_price']; ?></td>


                        </tr>
                                <?php// endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>





<?php include APP . DS . '/view/admin/footer.php'; ?>

