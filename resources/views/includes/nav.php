<ul class="nav nav-tabs my-3 mx-3">
    <li class="nav-item mr-3">
        <a class="nav-link bg-info text-light" href="<?= app('url_root') ?>/dashboard"><i class="fas fa-table"></i> Browse</a>
    </li>
    <?php if (isset($_GET['db_name'])) : ?>
        <li class="nav-item mr-3">
            <button class="nav-link bg-info text-light" id="sql__tab--btn"><i class="fas fa-sticky-note"></i> SQL</button>
        </li>
    <?php endif; ?>
    <li class="nav-item mr-3">
        <a class="nav-link bg-info text-light" href="#"><i class="fas fa-file-import"></i> Import</a>
    </li>
    <li class="nav-item">
        <a class="nav-link bg-info text-light" href="#"><i class="fas fa-download"></i></i> Export</a>
    </li>
</ul>