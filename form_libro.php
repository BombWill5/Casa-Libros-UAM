<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar/Modificar Libro</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>Casa de libros abiertos UAM</header>
    <div class="container">
        <h2>Formulario de Libro</h2>
        <?php
        include 'db_connect.php';

        // Consulta para obtener las editoriales
        $sql_editoriales = "SELECT idEditorial, nombre FROM editorial";
        $result_editoriales = $conn->query($sql_editoriales);

        // Consulta para obtener las unidades
        $sql_unidades = "SELECT idUnidad, nombre FROM unidad";
        $result_unidades = $conn->query($sql_unidades);
        ?>

        <form id="libroForm">
            <label for="idEditorial">Editorial:</label>
            <select id="idEditorial" name="idEditorial">
                <?php
                if ($result_editoriales->num_rows > 0) {
                    while ($row = $result_editoriales->fetch_assoc()) {
                        echo "<option value='" . $row['idEditorial'] . "'>" . $row['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay editoriales disponibles</option>";
                }
                ?>
            </select><br><br>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo"><br><br>

            <label for="detalles">Detalles:</label>
            <input type="text" id="detalles" name="detalles"><br><br>

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio"><br><br>

            <label for="anio">Año:</label>
            <input type="number" id="anio" name="anio" max="2024"><br><br>

            <label for="edicion">Edición:</label>
            <input type="text" id="edicion" name="edicion"><br><br>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo">
                <option value="Texto">Texto</option>
                <option value="Referencia">Referencia</option>
                <option value="E-book">E-book</option>
            </select><br><br>

            <!-- Spinner para seleccionar la unidad -->
            <label for="unidad">Unidad:</label>
            <select id="unidad" name="unidad">
                <?php
                if ($result_unidades->num_rows > 0) {
                    while ($row = $result_unidades->fetch_assoc()) {
                        echo "<option value='" . $row['idUnidad'] . "'>" . $row['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay unidades disponibles</option>";
                }
                ?>
            </select><br><br>

            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor"><br><br>

            <input type="submit" value="Guardar">
        </form>

        <?php 
        // Cerrar la conexión a la base de datos
        $conn->close(); 
        ?>
    </div>

    <script>
    $(document).ready(function() {
        $("#libroForm").submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de manera tradicional

            // Realiza la petición AJAX
            $.ajax({
                url: 'procesar_libro.php',
                type: 'POST',
                data: $(this).serialize(), // Serializa los datos del formulario
                success: function(response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                // Redirige a alguna página o realiza alguna acción adicional
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
                    } catch (e) {
                        console.error('Error al parsear la respuesta JSON: ', e);
                        Swal.fire({
                            title: 'Error',
                            text: 'Error inesperado. Por favor, intenta nuevamente más tarde.',
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
