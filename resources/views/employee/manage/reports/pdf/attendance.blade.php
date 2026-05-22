<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Attendance Report</title>
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
        <h2 style="margin: 0 0 6px;">Attendance Report</h2>
        <div>Generated at: {{ $generatedAt->format('Y-m-d H:i:s') }}</div>
        @if (!empty($note))
            <div class="note">{{ $note }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ optional($row->employee)->full_name }}</td>
                    <td>{{ optional(optional($row->employee)->department)->name }}</td>
                    <td>{{ optional($row->attendance_date)?->format('Y-m-d') }}</td>
                    <td>{{ $row->check_in_time }}</td>
                    <td>{{ $row->check_out_time }}</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $row->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

