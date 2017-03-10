<?php include APP . DS . '/view/admin/header.php'; ?>

<div class="row">
    <h2 class="display-4 text-center col-md-10">
        Upraviť Kategóriu
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
            <label for="categoryName">Upraviť kategóriu</label>
            <input type="text" class="form-control" name="categoryName" id="categoryName" value="<?php echo $data['category']['category_name']; ?>" required>
        </div>

        <div class="form-group">
            <label for="categoryParent">Podraďené k</label>
            <select class="form-control" name="categoryParent" id="categoryParent" required>

                <?php if ($data['category']['category_parent'] == 0) : ?>
                    <option value="0">Hlavná kategória</option>

                <?php else: ?>
                    <option value="<?php echo $data['category']['category_parent']; ?>">
                        <?php echo $data['categories'][$data['category']['category_parent'] - 1]['category_name']; ?>
                    </option>

                <?php endif; ?>



                <?php foreach($data['categories'] as $category): ?>
                    <?php if ($category['category_id'] !== $data['category']['category_parent'] && $category['category_id'] !== $data['category']['category_id']): ?>
                      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (!$data['category']['category_parent'] == 0) : ?>
                    <option value="0">Hlavná kategória</option>
                <?php endif; ?>

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
                <img src="/uploads/categories/<?php echo $data['category']['category_image'];?>" alt="" width="100px">
                <input type="hidden" value="<?php echo $data['category']['category_image']; ?>" name="image_old">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="/admin/kategorie/vymazat/<?php echo $data['category']['category_id'] . DS . $data['category']['category_image']; ?>" class="btn btn-danger" id="deleteConfirm">Delete</a>

    </div>

</form>

<hr>

<?php include APP . DS . '/view/admin/footer.php'; ?>
