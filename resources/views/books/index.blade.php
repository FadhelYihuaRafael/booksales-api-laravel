<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f4f6f9;
        }

        h1 {
            color: #263b5e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #263b5e;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

    <h1>Data Buku</h1>
    <p>Daftar buku yang tersedia.</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Buku</th>
                <th>Deskripsi</th>
                <th>Tahun Terbit</th>
                <th>Author</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->description }}</td>
                    <td>{{ $book->published_year }}</td>
                    <td>{{ $book->author->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
