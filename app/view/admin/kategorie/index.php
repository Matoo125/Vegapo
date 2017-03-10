<?php include APP . DS . '/view/admin/header.php'; ?>
<div class="row">
    <h2 class="display-4 text-center col-md-10">
        Kategórie
    </h2>

    <div class="col-md-2">
        <a href="/admin/kategorie/pridat"><button>Nová Kategória</button></a>
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
        <div class="table-responsive">
            <table class="table table-striped sortable">
                <thead class="thead-inverse">
                <tr>
                    <th>#</th>
                    <th>Názov</th>
                    <th>Rodičovská Kategória</th>
                    <th>Počet produktov</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data['categories'])): ?>
                    <?php foreach ($data['categories'] as $item): ?>
                        <tr>

                            <td><a href="/admin/kategorie/upravit/<?php echo $item['category_id']; ?>"><?php echo $item['category_id']; ?></a></td>
                            <td>
                                <?php if($item['category_image'] !== "none"): ?>
                                  <a rel="popover" data-img="<?php echo UPLOADS . "categories" . DS . $item['category_image']; ?>-thumb"><?php echo $item['category_name']; ?></a>
                               <?php else: ?>
                                  <?php echo $item['category_name']; ?>
                               <?php endif; ?>
                            </td>
                            <td><?php if ($item['category_parent'] > 0) echo $data['categories'][$item['category_parent'] - 1]['category_name']; ?></td>
                            <td><a href="/admin/produkty/<?php echo $item['category_id']; ?>"><?php echo $item['numberOfProducts']; ?></a></td>


                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<hr>



<?php include APP . DS . '/view/admin/footer.php'; ?>
