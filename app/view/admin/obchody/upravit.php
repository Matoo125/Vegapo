<?php include APP . DS . '/view/admin/header.php'; ?>

<div class="row">
    <h2 class="display-4 text-center col-md-10">
        Nová Kategória
    </h2>
</div>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>

<hr>

<form class="row" enctype="multipart/form-data" method="post">
    <div class="col-md-6">

        <div class="form-group">
            <label for="supermarketName">Upraviť kategóriu</label>
            <input type="text" class="form-control" name="supermarketName" id="supermarketName" value="<?php echo $data['supermarket']['supermarket_name']; ?>" required>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="file">Zmeň Obrázok </label>
                    <input type="file" id="file" name="file" class="form-control-file">
                </div>
            </div>
            <div class="col-md-6">
                <img src="/uploads/supermarkets/<?php echo $data['supermarket']['supermarket_image'];?>" alt="" width="100px">
                <input type="hidden" value="<?php echo $data['supermarket']['supermarket_image']; ?>" name="image_old">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="/admin/obchody/vymazat/<?php echo $data['supermarket']['supermarket_id'] . DS . $data['supermarket']['supermarket_image']; ?>" class="btn btn-danger" id="deleteConfirm">Delete</a>

    </div>

</form>

<hr>

<?php include APP . DS . '/view/admin/footer.php'; ?>
