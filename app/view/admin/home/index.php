
<?php include APP . DS . '/view/admin/header.php'; ?>

        <!--toggle sidebar button-->
        <p class="hidden-md-up">
            <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
        </p>

        <h2 class="display-3 hidden-xs-down">
            Prehľad
        </h2>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>


        <div class="row mb-3">
            <div class="col-lg-3 col-md-6">
                <div class="card card-inverse card-success">
                    <div class="card-block bg-success">
                            <div class="rotate">
                                <i class="fa fa-cutlery fa-4x"></i>
                            </div>
                        <h6 class="text-uppercase">Produktov</h6>
                        <h1 class="display-1"><?php echo $data['numberOfProducts']; ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-inverse card-danger">
                    <div class="card-block bg-danger">
                            <div class="rotate">
                                <i class="fa fa-folder fa-4x"></i>
                            </div>
                        <h6 class="text-uppercase">Kategórií</h6>
                        <h1 class="display-1"><?php echo $data['numberOfCategories']; ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-inverse card-info">
                    <div class="card-block bg-info">
                            <div class="rotate">
                                <i class="fa fa-shopping-cart fa-4x"></i>
                            </div>
                        <h6 class="text-uppercase">Obchodov</h6>
                        <h1 class="display-1"><?php echo $data['numberOfStores']; ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-inverse card-warning ">
                    <div class="card-block bg-warning">
                            <div class="rotate">
                                <i class="fa fa-check-square-o fa-4x"></i>
                            </div>
                        <h6 class="text-uppercase">Na schválenie</h6>
                        <h1 class="display-1"><?php echo $data['numberOfRequests']; ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <!--/row--><br> <hr>
        <div class="row">
            <div class="col">
                Ak sa vám zobrazí nejaká "error message", ktorej nerozumiete, prosím spravte screenshot.
            </div>
        </div>
        <hr>


<?php // echo COUNTRY_CODE; ?>

<?php include APP . DS . '/view/admin/footer.php'; ?>
