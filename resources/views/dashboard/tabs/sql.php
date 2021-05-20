<div id="sql__tab" class="d-none text-dark">
    <div class="sql__tab content p-4">
        <p class="lead">
            Run SQL query/queries on server
            <strong>"<?= $data['host'] ?>"</strong> <i class="fas fa-pen"></i>
        </p>
        <textarea class="form-control p-4 font-weight-bold" id="query__text-area" name="query" cols="30" rows="10"></textarea>
        <div class="text-right mb-3">
            <button onclick="submitQuery()" class="btn btn-success mt-3" id="query__submit">Go <i class="fas fa-arrow-right"></i></button>
        </div>

        <div class="results"></div>
    </div>
</div>