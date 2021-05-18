<div class="w-25 p-3" style="height: 100vh; overflow-y:auto; overflow-x:hidden">
    <!-- UserInfo -->
    <div class="d-flex align-items-center mb-4">
        <div class="w-50">
            <img class="img-fluid" src="<?= app('url_root') ?>/public/img/male-placeholder-image.jpeg" alt="">
        </div>

        <div class="w-50 ml-3">
            <p class="text-right">
                <a href="<?= app('url_root') ?>/logout">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                </a>
            </p>
            <h5><strong>Host: </strong> <br><?= $data['host'] ?></h5>
            <hr>
            <h5><strong>Username: </strong> <br><?= $data['username'] ?></h5>
        </div>
    </div>

    <?php require_once app('app_root') . "/resources/views/includes/createForm.php" ?>


    <!-- Select Form -->
    <?php if (sessionExists('db_error')) : ?>
        <div class="alert alert-danger text-danger"><?= session('db_error') ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <p class="lead mb-1">Select Database</p>

        <form method="get" class="input-group">
            <select name="db_name" class="custom-select">
                <option disabled selected>Choose database</option>
                <?php foreach ($data['databases'] as $database) : ?>
                    <option <?= (isset($_GET['db_name']) && $_GET['db_name'] == $database->Database) ? 'selected' : null ?> value="<?= $database->Database ?>">
                        <?= $database->Database ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="input-group-append">
                <button class="btn btn-dark" type="submit">Select</button>
            </div>
        </form>
    </div>

    <hr>

    <?php if (!is_null($data['tables'])) : ?>
        <div class="table__links--parent">
            <?php if (count($data['tables']) == 0) : ?>
                <p class="text-dark font-weight-bold">No tables available</p>
            <?php else : ?>
                <p class="text-dark font-weight-bold">Tables:</p>
            <?php endif; ?>

            <?php foreach ($data['tables'] as $table) : ?>
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-table text-primary"></i>
                    <a class="table__url d-flex align-items-center ml-2 mb-0" href="http://localhost/php_admin/dashboard?db_name=<?= $_GET['db_name'] ?>&table=<?= $table ?>">
                        <?= $table; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>