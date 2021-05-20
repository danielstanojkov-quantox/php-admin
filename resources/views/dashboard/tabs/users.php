<div id="users__tab" class="d-none text-dark">
    <div class="users__tab content p-4">

        <?php if (sessionExists('user_deleted_success')) : ?>
            <div class="alert alert-success text-dark"><?= session('user_deleted_success') ?></div>
        <?php endif; ?>
        <?php if (sessionExists('user_deleted_error')) : ?>
            <div class="alert alert-danger text-dark"><?= session('user_deleted_error') ?></div>
        <?php endif; ?>

        <table class="table table-warning">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th scope="col">User name</th>
                    <th scope="col">Host name</th>
                    <th scope="col">Password</th>
                    <th scope="col">Global privileges</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['accounts'] as $account) : ?>
                    <tr>
                        <td>
                            <form action="<?= app('url_root') ?>/Users/delete" method="post">
                                <input type="hidden" name="account" value="<?= $account['user'] ?>@<?= $account['host'] ?>">
                                <button type="submit" class="btn btn-transparent">
                                    <i class="fas fa-times text-danger"></i>
                                </button>
                            </form>
                        </td>
                        <td><?= $account['user'] === '' ? 'Any' : $account['user']; ?></td>
                        <td><?= $account['host']; ?></td>
                        <td class="<?= trim($account['authentication_string']) ? '' : 'text-danger'; ?>">
                            <?= trim($account['authentication_string']) ? 'Yes' : 'No'; ?>
                        </td>
                        <td><?= $account['Grant_priv'] === 'Y' ? 'ALL PRIVILEGES' : 'USAGE'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="mt-5">Create new account</h2>
        <hr>
        <?php if (sessionExists('registration_failed')) : ?>
            <div class="alert alert-danger text-dark"><?= session('registration_failed') ?></div>
        <?php endif; ?>
        <?php if (sessionExists('registration_successfull')) : ?>
            <div class="alert alert-success text-dark"><?= session('registration_successfull') ?></div>
        <?php endif; ?>
        <form action="<?= app('url_root') ?>/Users/store" method="POST">
            <select name="role" class="custom-select w-50 mb-3">
                <option selected disabled>Choose a role</option>
                <option value="admin">Admin</option>
                <option value="maintainer">Maintainer</option>
                <option value="basic">Basic User</option>
            </select>
            <input type="text" value="<?= sessionExists('account_username') ? session('account_username') : '' ?>" class="form-control w-50 mb-2" name="username" placeholder="User name">
            <input readonly type="text" value="localhost" class="form-control w-50 mb-2">
            <input type="password" class="form-control w-50" name="password" placeholder="Account Password">
            <input type="hidden" class="form-control w-50" name="db_name" value="<?= request('db_name'); ?>">
            <small>*Optional</small>
            <button class="btn btn-dark d-block px-5 mt-3" type="submit">Create</button>
        </form>
    </div>
</div>