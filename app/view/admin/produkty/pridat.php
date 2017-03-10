<?php include APP . DS . '/view/admin/header.php'; ?>

<div class="row">
    <h2 class="display-4 text-center col-md-10">
        Nový Produkt
    </h2>
</div>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>

<hr>
<?php if(isset($data['supermarkets']) && isset($data['categories'])): ?>
<form class="row" enctype="multipart/form-data" method="post">
    <div class="col-md-6">

        <div class="form-group">
            <label for="productName">Názov Produktu</label>
            <input type="text" class="form-control" name="productName" id="productName" autofocus required>
        </div>

        <div class="form-group">
            <label for="productPrice">Približná cena</label>
            <input type="number" class="form-control" name="productPrice" id="productPrice" step="any" required>
        </div>

        <?php if (isset($data['categories'])): ?>
        <div class="form-group">
            <label for="selectCategory">Kategória</label>
            <select class="form-control" name="selectCategory" id="selectCategory" required>

                <?php foreach($data['categories'] as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>

            </select>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="file">Nahraj Obrázok </label>
            <input type="file" id="file" name="file" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

    <?php if (isset($data['supermarkets'])): ?>
    <div class="col-md-5 offset-md-1">
        <p>Obchody</p>
        <?php foreach($data['supermarkets'] as $supermarket): ?>
            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="supermarket" name="supermarket[]" class="custom-control-input" value="<?php echo $supermarket['supermarket_id']; ?>">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description"><?php echo $supermarket['supermarket_name']; ?></span>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</form>
<?php else: ?>
    <h3>Please Create Supermarket and Category before product</h3>
<?php endif; ?>
<hr>

<?php include APP . DS . '/view/admin/footer.php'; ?>
