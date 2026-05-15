<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Employees Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px;
        }

        th {
            background: #f3f4f6;
        }

        .meta {
            margin-bottom: 12px;
        }

        .note {
            color: #b45309;
            font-size: 11px;
            margin: 6px 0 0;
        }
    </style>
</head>

<body>
    <div class="meta">
        <h2 style="margin: 0 0 6px;">Employees Report</h2>
        <div>Generated at: {{ $generatedAt->format('Y-m-d H:i:s') }}</div>
        @if (!empty($note))
            <div class="note">{{ $note }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Type</th>
                <th>State</th>
                <th>Department</th>
                <th>Date Of Hire</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->full_name }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->phone_number }}</td>
                    <td>{{ $row->employee_type }}</td>
                    <td>{{ $row->state }}</td>
                    <td>{{ optional($row->department)->name }}</td>
                    <td>{{ optional($row->date_of_hire)?->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

