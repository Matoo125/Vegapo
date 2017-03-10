
<?php include APP . DS . '/view/admin/header.php'; ?>

<!--toggle sidebar button-->
<p class="hidden-md-up">
    <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
</p>

<h2 class="display-3 hidden-xs-down">
    <?php echo $data['title']; ?>
</h2>

<hr>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-md-3"></div>
</div>
<!--/row-->
<?php if(isset($data['parsedown'])): ?>
<?php echo $data['parsedown']; ?>
<?php elseif( isset( $data['action'] ) && $data['action'] == "generate_missing_thumbnails" ): ?>
	<p>action completed</p>
<?php else: ?>
<a href="/admin/home/generate_missing_thumbnails">Generate missing thumbnails</a> <br>
<a href="/admin/home/version_control">Version Control</a>
<?php endif; ?>
<hr>




<?php include APP . DS . '/view/admin/footer.php'; ?>
