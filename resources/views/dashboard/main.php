<?php require app('app_root') . '/resources/views/includes/header.php'; ?>

<div class="d-flex">
    <?php require_once app('app_root') . "/resources/views/includes/sidebar.php" ?>


    <div class="w-75 bg-white" style="height: 100vh; overflow-y:scroll; overflow-x:hidden">

        <div class="bg-secondary p-2 pl-4 text-white">
            <i class="fas fa-server"></i> Server: <?= $data['host'] ?>
        </div>

        <!-- Create Database Form -->
        <?php require_once app('app_root') . "/resources/views/includes/nav.php" ?>

        <!-- Tabs -->
        <?php require_once app('app_root') . "/resources/views/dashboard/tabs/sql.php" ?>

        <div class="px-3">
            <?php if (isset($_GET['table'])) : ?>
                <?php if (count($data['table_contents']) === 0) : ?>
                    <h4>Table doesn't have any content!</h4>
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

        <div class="row mt-5 d-flex align-items-center justify-content-between">
            <div class="col-6">
                <?php require_once app('app_root') . "/resources/views/dashboard/tabs/import.php" ?>
            </div>

            <div class="col-6">
                <?php require_once app('app_root') . "/resources/views/dashboard/tabs/export.php" ?>
            </div>

        </div>
    </div>


</div>

<script defer src="<?= app('url_root') ?>/public/js/functions.js"></script>
<script defer>
    // Table Filtering
    $(document).ready(function() {
        $('#myTable').DataTable({
            paging: false
        });
    });


    // Custom SQL Queries
    const sqlTabBtn = document.getElementById('sql__tab--btn');
    const sqlTab = document.getElementById('sql__tab');
    const submitQueryBtn = document.getElementById('query__submit');
    const parent = document.querySelector('.sql__tab .results');

    const submitQuery = () => {
        renderSpinner(parent);
        const db_name = getUrlParameter('db_name');
        const sql = document.getElementById('query__text-area').value.trim();

        if (!sql) {
            printError('No Query String Provided!', parent);
            return;
        }

        axios.get(`http://localhost/php_admin/api/results?db_name=${db_name}&query=${sql}`)
            .then(data => {
                printTable(data, parent);
            })
            .catch(err => {
                printError(err.response.data.message, parent);
            })
    }

    sqlTabBtn.addEventListener('click', function() {
        const aboutBox = new WinBox({
            title: 'Custom Query',
            width: '80%',
            height: '80%',
            x: "center",
            y: "center",
            mount: document.querySelector("#sql__tab .content"),
            onfocus: function() {
                this.setBackground('#000')
            },
            onblur: function() {
                this.setBackground('#777')
            },
        })
    });
</script>

<?php require app('app_root') . '/resources/views/includes/footer.php'; ?>