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
            <label for="productName">Názov Kategórie</label>
            <input type="text" class="form-control" name="categoryName" id="productName" autofocus required>
        </div>

        <div class="form-group">
            <label for="categoryParent">Podraďené k</label>
            <select class="form-control" name="categoryParent" id="categoryParent" required>
                <option value="0">Hlavná kategória</option>
                <?php foreach($data['categories'] as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>


        <div class="form-group">
            <label for="file">Nahraj Obrázok </label>
            <input type="file" id="file" name="file" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</form>

<hr>

<?php include APP . DS . '/view/admin/footer.php'; ?>
