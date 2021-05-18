<?php if (isset($_GET['db_name'])) : ?>
    <div id="export__database" class="text-dark border">
        <div class="export__database content p-4">
            <div class="lead mb-4"><i class="fas fa-file-export"></i> Export Database:</div>
            <form method="POST" action="<?= app('url_root') ?>/export" class="d-flex justify-content-between align-items-center">
                <div class="small"><strong>Note:</strong></br>By clicking on the export button you will export the selected database.</div>
                <input type="hidden" name="db_name" value="<?= $_GET['db_name'] ?? '' ?>">
                <button type="submit" class="btn btn-dark">Export</button>
            </form>
        </div>
    </div>
<?php endif; ?>