<ul class="nav nav-tabs my-3 mx-3">
    <li class="nav-item mr-3">
        <a class="nav-link bg-info text-light" href="<?= app('url_root') ?>/dashboard"><i class="fas fa-table"></i> Browse</a>
    </li>
    <?php if (request('db_name')) : ?>
        <li class="nav-item mr-3">
            <button class="nav-link bg-info text-light" id="sql__tab--btn"><i class="fas fa-sticky-note"></i> SQL</button>
        </li>
    <?php endif; ?>
    <?php if (count($data['accounts'])) : ?>
        <li class="nav-item mr-3">
            <button class="nav-link bg-info text-light" id="users__tab--btn"><i class="fas fa-users"></i> User Accounts</button>
        </li>
    <?php endif; ?>
</ul>