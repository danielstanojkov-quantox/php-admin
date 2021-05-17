<div id="import__database" class="text-dark border ml-2">
    <div class="import__database content p-4">
        <div class="lead mb-4"><i class="fas text-primary fa-cloud-upload-alt"></i> Import Database:</div>
        <form enctype="multipart/form-data" method="POST" action="<?= app('url_root') ?>/import" class="d-flex justify-content-between align-items-center">
            <div class="form-group">
                <label for="sql_file">Upload file:</label>
                <input type="file" name="sql_file" id="sql_file">
                <input type="hidden" name="db_name" value="<?= $_GET['db_name'] ?? '' ?>">
                <small id="sqlHelp" class="form-text text-muted">The file type must be sql.</small>
            </div>
            <button type="submit" class="btn btn-success">Import</button>
        </form>

        <?php if (sessionExists('import__error')) : ?>
            <div class="text-danger"><?= session('import__error') ?></div>
        <?php endif; ?>
        <?php if (sessionExists('import__success')) : ?>
            <div class="text-success"><?= session('import__success') ?></div>
        <?php endif; ?>
    </div>
</div>