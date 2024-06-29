<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Incluir SweetAlert y jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>Casa de libros abiertos UAM</header>
    <div class="container">
        <h2>Editar Libro</h2>

        <?php
        include 'db_connect.php';

        if (isset($_GET['idlibro'])) {
            $idlibro = $_GET['idlibro'];

            // Consultar los datos del libro
            $sql = "SELECT libro.idlibro, libro.idEditorial, libro.titulo, libro.detalles, libro.precio, libro.anio, libro.edicion, libro.tipo, autor.nombre AS autor_nombre
                    FROM libro
                    JOIN autorlibro ON libro.idlibro = autorlibro.idlibro
                    JOIN autor ON autorlibro.idAutor = autor.idAutor
                    WHERE libro.idlibro = '$idlibro'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $idEditorial = $row['idEditorial'];
                $titulo = $row['titulo'];
                $detalles = $row['detalles'];
                $precio = $row['precio'];
                $anio = $row['anio'];
                $edicion = $row['edicion'];
                $tipo = $row['tipo'];
                $autor = $row['autor_nombre'];
            } else {
                echo "<script>
                        Swal.fire({
                            title: 'Error',
                            text: 'Libro no encontrado.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = 'buscar_libro.php';
                        });
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'ID de libro no proporcionado.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'buscar_libro.php';
                    });
                  </script>";
            exit();
        }

        // Consulta para obtener las editoriales
        $sql_editorial = "SELECT idEditorial, nombre FROM editorial";
        $result_editorial = $conn->query($sql_editorial);
        ?>

        <form id="editarLibroForm">
            <input type="hidden" id="idlibro" name="idlibro" value="<?php echo $idlibro; ?>">

            <label for="idEditorial">Editorial:</label>
            <select id="idEditorial" name="idEditorial">
                <?php
                if ($result_editorial->num_rows > 0) {
                    while ($row_editorial = $result_editorial->fetch_assoc()) {
                        $selected = ($row_editorial['idEditorial'] == $idEditorial) ? 'selected' : '';
                        echo "<option value='" . $row_editorial['idEditorial'] . "' $selected>" . $row_editorial['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay editoriales disponibles</option>";
                }
                ?>
            </select><br><br>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo $titulo; ?>"><br><br>

            <label for="detalles">Detalles:</label>
            <input type="text" id="detalles" name="detalles" value="<?php echo $detalles; ?>"><br><br>

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" value="<?php echo $precio; ?>"><br><br>

            <label for="anio">Año:</label>
            <input type="number" id="anio" name="anio" max="2024" value="<?php echo $anio; ?>"><br><br>

            <label for="edicion">Edición:</label>
            <input type="text" id="edicion" name="edicion" value="<?php echo $edicion; ?>"><br><br>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo">
                <option value="Texto" <?php echo ($tipo == 'Texto') ? 'selected' : ''; ?>>Texto</option>
                <option value="Referencia" <?php echo ($tipo == 'Referencia') ? 'selected' : ''; ?>>Referencia</option>
                <option value="E-book" <?php echo ($tipo == 'E-book') ? 'selected' : ''; ?>>E-book</option>
            </select><br><br>

            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" value="<?php echo $autor; ?>"><br><br>

            <input type="submit" value="Actualizar">
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $("#editarLibroForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: 'actualizar_libro.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire({
                            title: 'Éxito',
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

    <?php $conn->close(); // Cerrar conexión ?>
</body>
</html>
