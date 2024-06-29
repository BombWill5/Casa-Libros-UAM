<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Libro</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>Casa de libros abiertos UAM</header>
    <div class="container">
        <h2>Buscar Libro</h2>

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" onkeyup="buscarLibros()">

        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Detalles</th>
                    <th>Precio</th>
                    <th>Año</th>
                    <th>Edición</th>
                    <th>Tipo</th>
                    <th>Editorial</th>
                    <th>Autor</th>
                    <th>Unidad</th>
                    <th colspan='2'>Acción</th>
                </tr>
            </thead>
            <tbody id="resultados">
                <!-- Resultados de la búsqueda se insertarán aquí -->
            </tbody>
        </table>
    </div>

    <script>
    function buscarLibros() {
        var titulo = $("#titulo").val();
        $.ajax({
            url: 'buscar_libro_backend.php',
            type: 'POST',
            data: { titulo: titulo },
            success: function(response) {
                $("#resultados").html(response);
            },
            error: function() {<?php
include 'db_connect.php';

if (isset($_POST['titulo'])) {
    $titulo = $_POST['titulo'];

    // Consultar la base de datos
    $sql = "SELECT libro.idlibro, libro.titulo, libro.detalles, libro.precio, libro.anio, libro.edicion, libro.tipo, editorial.nombre AS editorial_nombre, autor.nombre AS autor_nombre
            FROM libro
            JOIN editorial ON libro.idEditorial = editorial.idEditorial
            JOIN autorlibro ON libro.idlibro = autorlibro.idlibro
            JOIN autor ON autorlibro.idAutor = autor.idAutor
            WHERE libro.titulo LIKE '%$titulo%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['titulo']}</td>
                    <td>{$row['detalles']}</td>
                    <td>{$row['precio']}</td>
                    <td>{$row['anio']}</td>
                    <td>{$row['edicion']}</td>
                    <td>{$row['tipo']}</td>
                    <td>{$row['editorial_nombre']}</td>
                    <td>{$row['autor_nombre']}</td>";
            
            // Mostrar link de comprar solo para libros de tipo E-book

            
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No se encontraron libros</td></tr>";
    }
}

$conn->close();
?>

                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo procesar la solicitud.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
    </script>
</body>
</html>
