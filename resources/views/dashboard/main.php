<div class="w-75 bg-white" style="height: 100vh; overflow-y:scroll">

    <div class="bg-secondary p-2 pl-4 text-white">
        <i class="fas fa-server"></i> Server: <?= $data['host'] ?>
    </div>


    <table class="table table-sm p-3 table-responsive table-dark w-100"></table>

</div>


<script>
    const anchors = document.querySelectorAll('.table__url');
    const tableLinksParent = document.querySelector('.table__links--parent');
    const tableHeadLinksParent = document.querySelector('.table');


    tableHeadLinksParent.addEventListener('click', e => {
        e.preventDefault();
        const headEl = e.target.closest('.table__link');
        if (!headEl) return;

        appendQueryParameter(headEl.href);
        fetchData();
    });


    // Functions
    const fetchData = () => {
        const urlParams = new URLSearchParams(window.location.search);
        const dbName = urlParams.get('db_name');
        const tableName = urlParams.get('table');
        let sqlQuery = urlParams.get('sql_query');

        let url = `http://localhost/php_admin/Api/table/${dbName}/${tableName}`;
        if (sqlQuery) {
            sqlQuery = sqlQuery.replaceAll(" ", '_');
            url += `/${sqlQuery}`;
        }


        fetch(url)
            .then(res => res.json())
            .then(data => {
                document.querySelector('.table').innerHTML = '';
                printTable(data);
            })
            .catch(err => {
                console.error('Something went wrong!');
            });
    }

    // Listeners 
    tableLinksParent.addEventListener('click', e => {
        e.preventDefault();
        const tableAnchorEl = e.target.closest('.table__url');
        if (!tableAnchorEl) return;

        appendQueryParameter(tableAnchorEl.href);
        fetchData();
    });


    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('db_name') && urlParams.get('table')) {
        fetchData();
    };




    function printTable(data) {
        if (!data.length) {
            const output = `<h5 class="p-4 mb-0">No information available for this table!</h5>`;
            document.querySelector('.table').insertAdjacentHTML('beforeend', output);
            return;
        }

        const table = buildTable(data);

        document.querySelector('.table').insertAdjacentHTML('beforeend', table);
    }



    function buildTable(data) {
        const urlParams = new URLSearchParams(window.location.search);
        const dbName = urlParams.get('db_name');
        const tableName = urlParams.get('table');

        const keys = Object.keys(data[0]);

        const thead = buildTableHead(keys, dbName, tableName);
        const tbody = buildTableBody(data, keys, dbName, tableName);
        return thead + tbody;
    }

    function buildTableHead(keys, dbName, tableName) {
        let output = `<thead><tr>`;

        keys.forEach(key => {
            let sql = `SELECT * FROM ${tableName} ORDER BY ${key} DESC`;
            output += `<th scope="col">
            <a class="text-success table__link" href="${window.location.href}&sql_query=${sql}">${key}</a>
            </th>`
        });

        output += `</tr></thead>`;

        return output;
    }

    function buildTableBody(data, keys, dbName, tablename) {
        let output = `<tbody><tr>`;

        data.forEach(element => {
            output += `<tr>`;
            keys.forEach(key => {
                output += `<td>${element[key]}</td>`
            })
            output += `</tr>`;
        });
        output += `</tr></tbody>`;
        return output;
    }

    function appendQueryParameter(parameter) {
        window.history.pushState({}, null, parameter);
    }
</script>