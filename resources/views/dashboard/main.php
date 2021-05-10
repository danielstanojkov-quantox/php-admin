<div class="w-75 bg-white" style="height: 100vh; overflow-y:scroll">

    <table class="table table-responsive table-dark w-100">
        <!-- <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
            </tr>
        </tbody> -->
    </table>

</div>

<script>
    const printTable = data => {
        if (!data.length) return;

        const keys = Object.keys(data[0]);

        // Table Head
        let output = `<thead><tr>`;
        keys.forEach(key => {
            output += `<th scope="col">${key}</th>`
        });
        output += `</tr></thead>`;

        // Table Body
        output += `<tbody><tr>`;
        data.forEach(element => {
            output += `<tr>`;
            keys.forEach(key => {
                output += `<td>${element[key]}</td>`
            })
            output += `</tr>`;
        });
        output += `</tr></tbody>`;
        document.querySelector('.table').insertAdjacentHTML('beforeend', output);
    }

    const fetchData = () => {
        const urlParams = new URLSearchParams(window.location.search);
        const dbName = urlParams.get('db_name');
        const tableName = urlParams.get('table');

        fetch(`http://localhost/php_admin/Api/table/${dbName}/${tableName}`)
            .then(res => res.json())
            .then(data => {
                printTable(data);
            })
            .catch(err => {
                console.error('Something went wrong!');
            });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('db_name') && urlParams.get('table')) {
        fetchData();
    }
</script>