<?php
// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$contraseña = 'moll4503';
$base_datos = 'ecom';

// Crear conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Comprobar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el ID del pedido desde la URL
$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar detalles del pedido
$sql_pedido = "SELECT * FROM pedidos WHERE id = ?";
$stmt = $conexion->prepare($sql_pedido);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result_pedido = $stmt->get_result();
$pedido = $result_pedido->fetch_assoc();

// Consultar detalles del usuario
$sql_usuario = "SELECT * FROM usuarios WHERE id = ?";
$stmt_usuario = $conexion->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $pedido['usuario_id']);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
//Consulta del numero y direccion del usuario 
//$sql_pedido =



// Consultar productos en el pedido
$sql_productos = "SELECT p.*, pp.unidades FROM productos p
                  JOIN pedidos_has_productos pp ON p.id = pp.producto_id
                  WHERE pp.pedido_id = ?";
$stmt_productos = $conexion->prepare($sql_productos);
$stmt_productos->bind_param("i", $pedido_id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
        }
        .order-details {
            margin-bottom: 30px;
        }
        .order-details h2 {
            color: #555;
        }
        .order-details p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles del Pedido</h1>

        <!-- Detalles del pedido -->
        <div class="order-details">
            <h2>Pedido ID: <?php echo htmlspecialchars($pedido['id']); ?></h2>
            <p><strong>Celular:</strong> <?php echo htmlspecialchars($pedido['celular']); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion']); ?></p>
            <p><strong>Coste:</strong> $<?php echo htmlspecialchars(number_format($pedido['coste'], 2)); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($pedido['fecha']); ?></p>
            <p><strong>Hora:</strong> <?php echo htmlspecialchars($pedido['hora']); ?></p>
        </div>

        <!-- Datos del usuario que hizo el pedido -->
        <div class="order-details">
            <h2>Detalles del Usuario</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']) . ' ' . htmlspecialchars($usuario['apellidos']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        </div>

        <!-- Productos en el pedido -->
        <h2>Productos en el Pedido</h2>
        <table>
            <thead>
                <tr>
                    <th>ID del Producto</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Unidades</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $result_productos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['id']); ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                    <td>$<?php echo htmlspecialchars(number_format($producto['precio'], 2)); ?></td>
                    <td><?php echo htmlspecialchars($producto['unidades']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
