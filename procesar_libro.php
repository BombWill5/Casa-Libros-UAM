<?php
include 'db_connect.php';

// Verificar que todos los campos requeridos están presentes
if (isset($_POST['idEditorial'], $_POST['titulo'], $_POST['detalles'], $_POST['precio'], $_POST['anio'], $_POST['edicion'], $_POST['tipo'], $_POST['autor'], $_POST['unidad'])) {
    $idEditorial = $_POST['idEditorial'];
    $titulo = $_POST['titulo'];
    $detalles = $_POST['detalles'];
    $precio = $_POST['precio'];
    $anio = $_POST['anio'];
    $edicion = $_POST['edicion'];
    $tipo = $_POST['tipo'];
    $autor = $_POST['autor'];
    $unidad = $_POST['unidad']; // Nueva variable para la unidad

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Insertar el libro
        $sql_libro = "INSERT INTO libro (idEditorial, titulo, detalles, precio, anio, edicion, tipo) VALUES ('$idEditorial', '$titulo', '$detalles', '$precio', '$anio', '$edicion', '$tipo')";
        $conn->query($sql_libro);
        $idLibro = $conn->insert_id; // Obtener el ID del libro recién insertado

        // Insertar el autor
        $sql_autor = "INSERT INTO autor (nombre) VALUES ('$autor')";
        $conn->query($sql_autor);
        $idAutor = $conn->insert_id; // Obtener el ID del autor recién insertado

        // Insertar en la tabla autorlibro
        $sql_autorlibro = "INSERT INTO autorlibro (idAutor, idLibro) VALUES ('$idAutor', '$idLibro')";
        $conn->query($sql_autorlibro);

        // Insertar en la tabla unidadlibro
        $sql_unidadlibro = "INSERT INTO unidadlibro (idLibro, idUnidad) VALUES ('$idLibro', '$unidad')";
        $conn->query($sql_unidadlibro);

        // Confirmar transacción
        $conn->commit();

        // Respuesta exitosa
        echo json_encode(['success' => true, 'message' => 'Libro agregado exitosamente']);
    } catch (mysqli_sql_exception $exception) {
        // Revertir transacción en caso de error
        $conn->rollback();

        // Respuesta de error
        echo json_encode(['success' => false, 'message' => 'Error al agregar el libro: ' . $exception->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
}

$conn->close();
?>
