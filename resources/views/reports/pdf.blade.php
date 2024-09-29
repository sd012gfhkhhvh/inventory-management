<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
    <style>
        /* Add any necessary styles for your PDF here */
    </style>
</head>
<body>
    <h1>Inventory Report</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
