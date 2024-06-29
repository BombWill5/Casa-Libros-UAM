<?php
include 'db_connect.php';

// Verificar que los parámetros requeridos están presentes
if (isset($_POST['idlibro'], $_POST['nombreCliente'])) {
    $idlibro = $_POST['idlibro'];
    $nombreCliente = $_POST['nombreCliente'];

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Consultar el cliente
        $sql_cliente = "SELECT idCliente, saldo FROM cliente WHERE nombre = ?";
        $stmt_cliente = $conn->prepare($sql_cliente);
        $stmt_cliente->bind_param('s', $nombreCliente);
        $stmt_cliente->execute();
        $result_cliente = $stmt_cliente->get_result();

        if ($result_cliente->num_rows == 0) {
            // Cliente no encontrado
            echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
        } else {
            $cliente = $result_cliente->fetch_assoc();
            $idCliente = $cliente['idCliente'];
            $saldo = $cliente['saldo'];

            // Consultar el precio del libro
            $sql_libro = "SELECT precio FROM libro WHERE idlibro = ?";
            $stmt_libro = $conn->prepare($sql_libro);
            $stmt_libro->bind_param('i', $idlibro);
            $stmt_libro->execute();
            $result_libro = $stmt_libro->get_result();
            $libro = $result_libro->fetch_assoc();
            $precio = $libro['precio'];

            if ($saldo < $precio) {
                // Saldo insuficiente
                echo json_encode(['success' => false, 'message' => 'Saldo insuficiente.']);
            } else {
                // Actualizar saldo del cliente
                $nuevo_saldo = $saldo - $precio;
                $sql_actualizar_saldo = "UPDATE cliente SET saldo = ? WHERE idCliente = ?";
                $stmt_actualizar_saldo = $conn->prepare($sql_actualizar_saldo);
                $stmt_actualizar_saldo->bind_param('di', $nuevo_saldo, $idCliente);
                $stmt_actualizar_saldo->execute();

                // Confirmar transacción
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Compra realizada exitosamente.']);
            }
        }
    } catch (mysqli_sql_exception $exception) {
        // Revertir transacción en caso de error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error al procesar la compra: ' . $exception->getMessage()]);
    }

    $stmt_cliente->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos.']);
}

$conn->close();
?>
