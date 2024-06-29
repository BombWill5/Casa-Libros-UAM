<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar/Modificar Libro</title>
    <!-- Incluir SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Formulario de Libro</h2>

    <?php
    include 'db_connect.php';

    // Consulta para obtener las editoriales
    $sql = "SELECT idEditorial, nombre FROM editorial";
    $result = $conn->query($sql);
    ?>

    <form id="libroForm">
        <!-- No incluir idlibro en el formulario -->

        <label for="idEditorial">Editorial:</label>
        <select id="idEditorial" name="idEditorial" required>
            <?php
            if ($result->num_rows > 0) {
                // Mostrar opciones en el combobox
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['idEditorial'] . "'>" . $row['nombre'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay editoriales disponibles</option>";
            }
            ?>
        </select><br><br>

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="detalles">Detalles:</label>
        <input type="text" id="detalles" name="detalles" required><br><br>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" required><br><br>

        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" max="2024" required><br><br>

        <label for="edicion">Edición:</label>
        <input type="text" id="edicion" name="edicion" required><br><br>

        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required>
            <option value="Texto">Texto</option>
            <option value="Referencia">Referencia</option>
            <option value="E-book">E-book</option>
        </select><br><br>

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" required><br><br>

        <input type="button" value="Guardar" id="submitBtn">
    </form>

    <?php $conn->close(); // Cerrar conexión ?>

    <script>
    $(document).ready(function() {
        $('#submitBtn').click(function() {
            $.ajax({
                url: 'procesar_libro.php',
                type: 'POST',
                data: $('#libroForm').serialize(),
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
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
