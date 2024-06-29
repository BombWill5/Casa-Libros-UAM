<?php
// Verificar si se ha pasado el parámetro idlibro por GET
if (isset($_GET['idlibro'])) {
    $idlibro = $_GET['idlibro'];

    // Aquí deberías incluir la conexión a tu base de datos
    include 'db_connect.php';

    // Consulta para obtener los detalles del libro
    $sql = "SELECT libro.idlibro, libro.titulo, libro.detalles, libro.precio, editorial.nombre AS editorial_nombre, GROUP_CONCAT(autor.nombre SEPARATOR ', ') AS autores
            FROM libro
            JOIN editorial ON libro.idEditorial = editorial.idEditorial
            JOIN autorlibro ON libro.idlibro = autorlibro.idlibro
            JOIN autor ON autorlibro.idAutor = autor.idAutor
            WHERE libro.idlibro = '$idlibro'
            GROUP BY libro.idlibro";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $titulo = $row['titulo'];
        $detalles = $row['detalles'];
        $precio = $row['precio'];
        $editorial = $row['editorial_nombre'];
        $autores = $row['autores'];
    } else {
        echo "Libro no encontrado.";
        exit();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "ID de libro no especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprar Libro</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>Casa de libros abiertos UAM</header>
    <div class="container">
        <h2>Detalles del Libro</h2>
        <p><strong>Título:</strong> <?php echo $titulo; ?></p>
        <p><strong>Detalles:</strong> <?php echo $detalles; ?></p>
        <p><strong>Precio:</strong> $<?php echo $precio; ?></p>
        <p><strong>Editorial:</strong> <?php echo $editorial; ?></p>
        <p><strong>Autor(es):</strong> <?php echo $autores; ?></p>

        <form id="comprarLibroForm">
            <label for="nombreCliente">Nombre del Cliente:</label>
            <input type="text" id="nombreCliente" name="nombreCliente" required><br><br>
            <input type="hidden" id="idlibro" name="idlibro" value="<?php echo $idlibro; ?>">
            <input type="submit" value="Comprar">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#comprarLibroForm").submit(function(event) {
            event.preventDefault();

            var nombreCliente = $("#nombreCliente").val();
            var idlibro = $("#idlibro").val();

            $.ajax({
                url: 'procesar_compra.php',
                type: 'POST',
                data: { idlibro: idlibro, nombreCliente: nombreCliente },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire({
                            title: 'Compra Exitosa',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = 'buscar_libro.php';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: res.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo procesar la solicitud.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
