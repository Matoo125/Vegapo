
<?php include APP . DS . '/view/admin/header.php'; ?>

<!--toggle sidebar button-->
<p class="hidden-md-up">
    <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
</p>

<h2 class="display-3 hidden-xs-down">
    Osobné Informácie
</h2>

<hr>

<?php if (Session::hasFlash()): ?>
    <div class="alert alert-<?php Session::flashStyle(); ?>" role="alert">
        <?php Session::flash(); ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-5">
        <h3>Všeobecné</h3>
        <form method="post" action="" id="profile-update">
            <div class="form-group">
                <label for="email">Email adresa</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $data['user']['email']; ?>">
            </div>

            <div class="form-group">
                <label for="username">Prezývka</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $data['user']['username']; ?>">
            </div>

            <div class="form-group">
                <label for="first-name">Krstné meno</label>
                <input type="text" class="form-control" id="first-name" name="first-name" placeholder="First Name" value="<?php echo $data['user']['first_name']; ?>">
            </div>

            <div class="form-group">
                <label for="last-name">Priezvisko</label>
                <input type="text" class="form-control" id="last-name" name="last-name" placeholder="Last Name" value="<?php echo $data['user']['last_name']; ?>">
            </div>

            <div class="form-group">
                <label for="about-me">O mne</label>
                <textarea class="form-control" id="about-me" name="about-me" rows="3"><?php echo $data['user']['about_me']; ?></textarea>
            </div>

            <button type="submit" name="change-details" class="btn btn-primary">Odoslať</button>
        </form>
    </div>

    <div class="col-md-5 offset-1">
        <h3>Zmena hesla</h3>
        <form method="post" action="" id="password-update">
            <div class="form-group">
                <label for="old-password">Staré heslo</label>
                <input type="password" class="form-control" id="old-password" name="old-password" >
            </div>

            <div class="form-group">
                <label for="new-password">Nové heslo</label>
                <input type="password" class="form-control" id="new-password" name="new-password" >
            </div>

            <div class="form-group">
                <label for="new-password2">Kontrola nového hesla</label>
                <input type="password" class="form-control" id="new-password2" name="new-password2" >
            </div>

            <button type="submit" name="change-password" class="btn btn-primary">Odoslať</button>
        </form>
    </div>

</div>

<hr>



<?php include APP . DS . '/view/admin/footer.php'; ?>
