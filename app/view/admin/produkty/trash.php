
<?php include APP . DS . '/view/admin/header.php'; ?>


    <div class="row">
        <h2 class="display-4 text-center col-md-10">
            Trash
        </h2>


    </div>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>

    <hr>

    <div class="row">
        <div class="col-md-12">


            <div class="table-responsive">
                <table class="table table-striped sortable">
                    <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Názov</th>
                        <th>Kategória</th>
                        <th>Obchody</th>
                        <th>Približná cena</th>
                        <th>Odozva</th>
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
                            <td><a href="/admin/produkty/trash/recover/<?php echo $item['product_id']; ?>">Recover</a> | <a href="/admin/produkty/trash/delete/<?php echo $item['product_id'] . DS . $item['product_image']; ?>" id="deleteConfirm">Delete</a></td>


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

