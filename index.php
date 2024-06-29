<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        header {
            width: 100%;
            background-color: #003366;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 24px;
        }

        h2 {
            color: #003366;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 3000px;
            margin: 20px auto;
            overflow: auto;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 20px 40px;
            margin: 20px;
            font-size: 1.5rem;
            color: white;
            background-color: #003366;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0055b3;
        }
    </style>
</head>
<body>
    <header>Casa de libros abiertos UAM</header>
    <div class="container">
        <h2>Menú Principal</h2>
        <div class="btn-container">
            <a href="form_libro.php" class="btn">Agregar Libro</a>
            <a href="buscar_libro.php" class="btn">Buscar Libro</a>
        </div>
    </div>
</body>
</html>
