<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Журнал</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
       body {
            font-family: 'DejaVu Sans', sans-serif;
        }
    </style>
</head>
<body>
    <h3>Журнал оперативного обліку</h3>
    <p>Operating Report</p>
    <table>
        <thead>
            <tr>
                <th>Дата</th>
                <th>Час</th>
                <th>Подія</th>
                <th>Працівник</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['time'] }}</td>
                    <td>{{ $row['comment'] }}</td>
                    <td>{{ $row['user'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
