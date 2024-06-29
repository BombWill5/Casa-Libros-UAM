<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Libro</title>
    <!-- Incluir SweetAlert y jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Buscar Libro</h2>

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" onkeyup="buscarLibros()">
    
    <table border="1">
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
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="resultados">
            <!-- Resultados de la búsqueda se insertarán aquí -->
        </tbody>
    </table>

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
            error: function() {
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
