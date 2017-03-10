<?php include APP . DS . '/view/admin/header.php'; ?>

<div class="row">
    <h2 class="display-4 text-center col-md-10">
        Upraviť Produkt
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
            <label for="productName">Názov Produktu</label>
            <input type="text" class="form-control" name="productName" id="productName" value="<?php echo $data['product']['product_name']; ?>" required>
        </div>

        <div class="form-group">
            <label for="productPrice">Približná cena</label>
            <input type="number" class="form-control" name="productPrice" id="productPrice" step="any" value="<?php echo $data['product']['expected_price']; ?>" required>
        </div>

        <div class="form-group">
            <label for="selectCategory">Kategória</label>
            <select class="form-control" name="selectCategory" id="selectCategory" required>
                <option value="<?php echo $data['product']['category_id']; ?>" ><?php echo $data['product']['category_name']; ?></option>
                <?php foreach($data['categories'] as $category): ?>
                    <?php if($category['category_id'] !== $data['product']['category_id']): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>

            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="file">Zmeň Obrázok </label>
                    <input type="file" id="file" name="file" class="form-control-file">
                </div>
            </div>
            <div class="col-md-6">
                <img src="/uploads/products/<?php echo $data['product']['product_image'];?>" alt="" width="100px">
                <input type="hidden" value="<?php echo $data['product']['product_image']; ?>" name="image_old">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="/admin/produkty/trash/move/<?php echo $data['product']['product_id']; ?>" class="btn btn-danger" id="deleteConfirm">Trash</a>

    </div>

    <div class="col-md-5 offset-md-1">
        <p>Obchody</p>
        <?php foreach($data['supermarkets'] as $supermarket): ?>
            <div class="form-group">
                <label class="custom-control custom-checkbox">

                    <input type="checkbox"
                           id="supermarket"
                           name="supermarket[]"
                           class="custom-control-input"
                           value="<?php echo $supermarket['supermarket_id']; ?>"

                           <?php if (strpos($data['product']['supermarket_names'], $supermarket['supermarket_name']) !== false) echo 'checked'; ?> >
                    
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description"><?php echo $supermarket['supermarket_name']; ?></span>
                </label>
            </div>
        <?php endforeach; ?>
<?php //echo "<pre>"; print_r($data['supermarkets']); ?>
        <input type="hidden" name="supermarkets_old" value="<?php echo $data['product']['supermarket_ids']; ?>">

    </div>

</form>

<hr>

<?php include APP . DS . '/view/admin/footer.php'; ?>
