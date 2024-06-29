<?php
include 'db_connect.php';

if (isset($_POST['titulo'])) {
    $titulo = $_POST['titulo'];

    // Consultar la base de datos
    $sql = "SELECT libro.idlibro, libro.titulo, libro.detalles, libro.precio, libro.anio, libro.edicion, libro.tipo, editorial.nombre AS editorial_nombre, autor.nombre AS autor_nombre, unidad.nombre AS unidad_nombre
            FROM libro
            JOIN editorial ON libro.idEditorial = editorial.idEditorial
            JOIN autorlibro ON libro.idlibro = autorlibro.idlibro
            JOIN autor ON autorlibro.idAutor = autor.idAutor
            LEFT JOIN unidadlibro ON libro.idlibro = unidadlibro.idlibro
            LEFT JOIN unidad ON unidadlibro.idunidad = unidad.idunidad
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
                    <td>{$row['autor_nombre']}</td>
                    <td>{$row['unidad_nombre']}</td>
                    <td>";
            
            // Si el tipo de libro es "E-book", muestra el enlace "Comprar"
            if ($row['tipo'] == 'E-book') {
                echo "<a href='comprar_libro.php?idlibro={$row['idlibro']}'>Comprar</a>";
            } else {
                echo "Pedir en la UAM";
            }

            echo "</td>
                    <td><a href='editar_libro.php?idlibro={$row['idlibro']}'>Editar</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No se encontraron libros</td></tr>";
    }
}

$conn->close();
?>
