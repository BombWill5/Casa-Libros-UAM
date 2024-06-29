<?php
include 'db_connect.php';

if (isset($_POST['idlibro'])) {
    $idlibro = $_POST['idlibro'];
    $idEditorial = $_POST['idEditorial'];
    $titulo = $_POST['titulo'];
    $detalles = $_POST['detalles'];
    $precio = $_POST['precio'];
    $anio = $_POST['anio'];
    $edicion = $_POST['edicion'];
    $tipo = $_POST['tipo'];
    $autor = $_POST['autor'];

    // Primero, actualizar la tabla libro
    $sql = "UPDATE libro SET 
            idEditorial='$idEditorial', 
            titulo='$titulo', 
            detalles='$detalles', 
            precio='$precio', 
            anio='$anio', 
            edicion='$edicion', 
            tipo='$tipo' 
            WHERE idlibro='$idlibro'";

    if ($conn->query($sql) === TRUE) {
        // Luego, actualizar la tabla autor
        $sql_autor = "SELECT idAutor FROM autorlibro WHERE idlibro='$idlibro'";
        $result_autor = $conn->query($sql_autor);
        if ($result_autor->num_rows > 0) {
            $row_autor = $result_autor->fetch_assoc();
            $idAutor = $row_autor['idAutor'];
            $sql_update_autor = "UPDATE autor SET nombre='$autor' WHERE idAutor='$idAutor'";
            $conn->query($sql_update_autor);
        } else {
            $sql_insert_autor = "INSERT INTO autor (nombre) VALUES ('$autor')";
            if ($conn->query($sql_insert_autor) === TRUE) {
                $new_idAutor = $conn->insert_id;
                $sql_insert_autorlibro = "INSERT INTO autorlibro (idlibro, idAutor) VALUES ('$idlibro', '$new_idAutor')";
                $conn->query($sql_insert_autorlibro);
            }
        }

        $response = array('success' => true, 'message' => 'Libro actualizado exitosamente.');
    } else {
        $response = array('success' => false, 'message' => 'Error al actualizar el libro.');
    }
} else {
    $response = array('success' => false, 'message' => 'ID de libro no proporcionado.');
}

$conn->close();
echo json_encode($response);
?>
