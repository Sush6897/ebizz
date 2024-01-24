
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Import</title>
</head>
<body>
    <form action="{{ url('/import') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="file">Choose Excel file:</label>
        <input type="file" name="file" accept=".xlsx, .xls, .csv">
        <button type="submit">Import</button>
    </form>
</body>
</html>
