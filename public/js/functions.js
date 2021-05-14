// Get URL parameters
function getUrlParameter(parameter) {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  return urlParams.get(parameter);
}

// Printing Erros
function printError(message, parent) {
  let errorDiv = `<div class="alert alert-danger">${message}</div>`;
  parent.innerHTML = "";
  parent.insertAdjacentHTML("beforeend", errorDiv);
}

// Render Spinner to DOM
function renderSpinner(parent) {
  const spinner = `<div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status"></div>
                  </div>`;
  parent.innerHTML = "";
  parent.insertAdjacentHTML("beforeend", spinner);
}

// Printing Table
function printTable(data, parent) {
  const content = data.data;
  if (!content.length) return;

  const tableHead = buildTableHead(content);
  const tableBody = buildTableBody(content);

  const table = `<table class="table table-responsive table-dark">
                  ${tableHead}${tableBody}
                </table>`;
  parent.innerHTML = "";
  parent.insertAdjacentHTML("beforeend", table);
  $(".sql__tab table").DataTable({ paging: false });
}

function buildTableHead(data) {
  const keys = Object.keys(data[0]);

  const theads = keys
    .map((key) => `<th class="text-success">${key}</th>`)
    .join("");

  return `<thead>${theads}</thead>`;
}

function buildTableBody(data) {
  let output = ``;

  data.forEach((entry) => {
    entry = Object.values(entry);

    output += `<tr class="bg-dark">`;
    output += entry.map((value) => `<td>${value}</td>`).join("");
    output += `</tr>`;
  });

  return output;
}
