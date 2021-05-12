<?php require app('app_root') . '/resources/views/includes/header.php'; ?>

<div class="d-flex">
    <?php require_once app('app_root') . "/resources/views/includes/sidebar.php" ?>


    <div class="w-75 bg-white" style="height: 100vh; overflow-y:scroll">

        <div class="bg-secondary p-2 pl-4 text-white">
            <i class="fas fa-server"></i> Server: <?= $data['host'] ?>
        </div>

        <?php require_once app('app_root') . "/resources/views/includes/nav.php" ?>

        
        <div class="px-3">
            <?php if (isset($_GET['table'])) : ?>
                <?php if (count($data['table_contents']) === 0) : ?>
                    <h4 class="">Table doesn't have any content!</h4>
                <?php else : ?>
                    <table id="myTable" class="table table-responsive table-dark w-100">
                        <thead>
                            <?php foreach ($data['table_contents'][0] as $key => $value) : ?>
                                <th>
                                    <a class="text-success" href="#">
                                        <?= $key ?>
                                    </a>
                                </th>
                            <?php endforeach; ?>
                        </thead>
                        <tbody>
                            <?php foreach ($data['table_contents'] as $key => $value) : ?>
                                <tr class="bg-dark">
                                    <?php foreach ($value as $key => $value) : ?>
                                        <td><?= $value === null ? 'NULL' : $value ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php else : ?>
                <h4 class="p-4">Please select any table to see further informations <i class="far text-warning fa-smile-beam"></i></h4>
            <?php endif; ?>
        </div>
    </div>


</div>
<script defer>
    $(document).ready(function() {
        $('#myTable').DataTable({
            paging: false,
        });
    });
</script>

<?php require app('app_root') . '/resources/views/includes/footer.php'; ?>