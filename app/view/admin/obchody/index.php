<?php include APP . DS . '/view/admin/header.php'; ?>


<div class="row">
    <h2 class="display-4 text-center col-md-10">
        Obchody
    </h2>

    <div class="col-md-2">
        <a href="/admin/obchody/pridat/"><button>Nový Obchod</button></a>
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
                    <th>Počet produktov</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data['stores'])): ?>
                    <?php foreach ($data['stores'] as $item): ?>
                        <tr>
                            <td><a href="/admin/obchody/upravit/<?php echo $item['supermarket_id']; ?>"><?php echo $item['supermarket_id']; ?></a></td>
                            <td>
                             <?php if($item['supermarket_image'] !== "none"): ?>
                                  <a rel="popover" data-img="<?php echo UPLOADS . "supermarkets" . DS . $item['supermarket_image']; ?>-thumb"><?php echo $item['supermarket_name']; ?></a>
                             <?php else: ?>
                               <p> <?php echo $item['supermarket_name']; ?></p>
                             <?php endif; ?>
                            </td>
                            <td><a href="/admin/produkty/0/<?php echo $item['slug']; ?>"><?php echo $item['numberOfProducts']; ?></a></td>

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
