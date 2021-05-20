<div>
    <button class="btn btn-danger mb-3" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <i class="fas mr-2 fa-database"></i><i class="fas fa-plus"></i>
    </button>
    <div class="collapse bg-transparent mb-3" id="collapseExample">
        <form action="<?= app('url_root'); ?>/dashboard/store" method="POST">
            <input name="dbName" value="<?= sessionExists('dbName') ? session('dbName') : ''  ?>" type="text" class="p-2 mb-2 d-block w-100" placeholder="Database name">

            <select class="p-2 d-block w-100" name="encodingType">
                <?php foreach ($data['encoding_types'] as $charset => $collations) : ?>
                    <optgroup label="<?= $charset ?>">
                        <?php foreach ($collations as $collation) : ?>
                            <option <?= $collation === app('collation_type') && $charset === app('character_set') ? 'selected' : '' ?> value="<?= $charset ?>:<?= $collation ?>"><?= $collation ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>

            <div class="text-right mt-2">
                <button type="submit" class="btn btn-transparent p-0">
                    <i class="fas fa-2x fa-plus-square text-dark"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<?php if (sessionExists('db_creation_success')) : ?>
    <div class="alert alert-success text-success"><?= session('db_creation_success') ?></div>
<?php endif; ?>
<?php if (sessionExists('db_creation_error')) : ?>
    <div class="alert alert-danger text-danger"><?= session('db_creation_error') ?></div>
<?php endif; ?>