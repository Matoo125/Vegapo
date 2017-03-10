
<?php include APP . DS . '/view/admin/header.php'; ?>

<!--toggle sidebar button-->
<p class="hidden-md-up">
    <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
</p>

<h2 class="display-3 hidden-xs-down">
    Zoznam Užívateľov
</h2>

<hr>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>

<div class="row">

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Prezývka</th>
                        <th>Krstné meno</th>
                        <th>Priezvisko</th>
                        <th>Kontaktný email</th>
                        <th>Rola</th>
                        <th>Krajina</th>
                        <th>Registrovaný</th>
                        <th>Posledné Prihlásenie</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($data)): ?>
                        <?php foreach ($data as $item): ?>

                            <tr>
                                <td><?php echo $item['user_id']; ?></td>
                                <td><?php echo $item['username']; ?></td>
                                <td><?php echo $item['first_name']; ?></td>
                                <td><?php echo $item['last_name']; ?></td>
                                <td><?php echo $item['email']; ?></td>
                                <td><?php echo USERS[$item['role']]; ?></td>
                                <td><?php echo $item['country']; ?></td>
                                <td><?php echo strftime("%e. %B %Y", strtotime($item['created_at'])); ?></td>
                                <td><?php echo strftime("%e. %B %k:%M", strtotime($item['last_activity'])); ?></td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<hr>




<?php include APP . DS . '/view/admin/footer.php'; ?>
