@extends('layouts.app')
@section('title', 'Magazine')
@section('content')
<div class="container">
    <h3 class="mb-4">Журнал оперативного обліку завдань</h3>
    <hr>

    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" id="searchAction" class="form-control" placeholder="Пошук за подією...">
        </div>
        <div class="col-md-3">
            <input type="text" id="searchName" class="form-control" placeholder="Пошук за прізвищем...">
        </div>
        <div class="col-md-3">
            <input type="date" id="searchDate" class="form-control" placeholder="Дата">
        </div>
        <div class="col-md-3 text-end">
            <button class="btn btn-primary" onclick="resetFilters()">Скинути фільтри</button>
        </div>
    </div>

    <table class="table table-sm table-bordered table-hover" id="magazine-table">
        <thead class="table-light">
            <tr>
                <th class="text-center" onclick="sortTable('date')">Дата &#x25B2;&#x25BC;</th>
                <th class="text-center" onclick="sortTable('time')">Час &#x25B2;&#x25BC;</th>
                <th class="text-center" onclick="sortTable('comment')">Подія &#x25B2;&#x25BC;</th>
                <th class="text-center" onclick="sortTable('user')">Працівник &#x25B2;&#x25BC;</th>
            </tr>
        </thead>
        <tbody id="magazine-body">
            <!-- JavaScript вставить дані тут -->
        </tbody>
    </table>
</div>


<script>
const logs = @json($recentLogs);
</script>

<script>
// Обробка даних
let data = logs.map(log => {
    return {
        date: new Date(log.created_at).toLocaleDateString('uk-UA'),
        time: new Date(log.created_at).toLocaleTimeString('uk-UA'),
        comment: log.comment ?? '',
        user: (log.user?.position ?? '') + ' - ' + (log.user?.name ?? ''),
    };
});

let currentSort = { column: null, asc: true };

// Рендеринг таблиці
function renderTable(filteredData) {
    const tbody = document.getElementById('magazine-body');
    tbody.innerHTML = '';
    filteredData.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.date}</td>
            <td>${row.time}</td>
            <td>${row.comment}</td>
            <td>${row.user}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Сортування таблиці
function sortTable(column) {
    if (currentSort.column === column) {
        currentSort.asc = !currentSort.asc;
    } else {
        currentSort.column = column;
        currentSort.asc = true;
    }

    const sorted = [...data].sort((a, b) => {
        let valA = a[column].toLowerCase?.() ?? a[column];
        let valB = b[column].toLowerCase?.() ?? b[column];
        if (valA < valB) return currentSort.asc ? -1 : 1;
        if (valA > valB) return currentSort.asc ? 1 : -1;
        return 0;
    });

    renderTable(sorted);
}

// Фільтрація
function filterTable() {
    const searchAction = document.getElementById('searchAction').value.toLowerCase();
    const searchName = document.getElementById('searchName').value.toLowerCase();
    const searchDate = document.getElementById('searchDate').value;

    const filtered = data.filter(row => {
        return (
            row.comment.toLowerCase().includes(searchAction) &&
            row.user.toLowerCase().includes(searchName) &&
            (searchDate === '' || row.date === new Date(searchDate).toLocaleDateString('uk-UA'))
        );
    });

    renderTable(filtered);
}

function resetFilters() {
    document.getElementById('searchAction').value = '';
    document.getElementById('searchName').value = '';
    document.getElementById('searchDate').value = '';
    renderTable(data);
}

document.getElementById('searchAction').addEventListener('input', filterTable);
document.getElementById('searchName').addEventListener('input', filterTable);
document.getElementById('searchDate').addEventListener('change', filterTable);

// Початковий рендер
renderTable(data);
</script>

@endsection