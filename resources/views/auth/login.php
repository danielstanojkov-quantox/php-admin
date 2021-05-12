<?php require app('app_root') . '/resources/views/includes/header.php'; ?>

<div class="container d-flex align-items-center justify-content-center mt-5">
    <div class="row w-100">
        <div class="col-lg-10 col-xl-9 mx-auto p-5">
            <div class="card card-signin flex-row my-5">
                <div class="card-img-left d-none d-md-flex"></div>

                <div class="card-body">
                    <h5 class="card-title text-center">Sign In</h5>

                    <?php if (sessionExists('login_failed')) : ?>
                        <div class="alert alert-danger text-dark"><?= session('login_failed') ?></div>
                    <?php endif; ?>

                    <form class="form-signin" method="POST" action="<?= app('url_root') . '/login'  ?>">
                        <div class="form-label-group">
                            <input value="<?= sessionExists('host') ? session('host') : '' ?>" name="host" type="text" id="inputUserame" class="form-control" placeholder="Host" autofocus>
                            <label for="inputUserame">Host</label>
                        </div>

                        <div class="form-label-group">
                            <input value="<?= sessionExists('username') ? session('username') : '' ?>" name="username" type="text" id="inputEmail" class="form-control" placeholder="Username">
                            <label for="inputEmail">Username</label>
                        </div>

                        <div class="form-label-group">
                            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password">
                            <label for="inputPassword">Password</label>
                        </div>

                        <button class="btn btn-lg btn-success btn-block text-uppercase" type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require app('app_root') . '/resources/views/includes/footer.php'; ?>