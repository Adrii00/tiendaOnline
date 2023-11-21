<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <?php require './database.php'; ?>
    <?php require 'Objetos/producto.php'; ?>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"]) && isset($_SESSION["rol"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $_SESSION["rol"] = "cliente";
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    }
    // Mostramos el navbar
    require 'util/nav.php';
    // Mostrar productos en la cesta
    $sql = "SELECT pc.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen FROM productos_cestas pc JOIN productos p ON pc.idProducto = p.idProducto WHERE pc.idCesta = (SELECT idCesta FROM cestas WHERE usuario = '$usuario')";
    $resultado = $conexion->query($sql);

    $sql = "SELECT idProducto,cantidad from productos_cestas where idCesta = (Select idCesta from usuarios where usuario = '$usuario')";
    $precio1 = $conexion->query($sql);
    $precioCestaTotal = 0;
    while ($fila = $precio1->fetch_assoc()) {
        $idProductoActual = $fila["idProducto"];
        $cantidadProvisional = $fila["cantidad"];
        $sql = "SELECT precio from productos where idProducto=$idProductoActual";
        $precioUd = ($conexion->query($sql))->fetch_assoc()['precio'];
        $precioCestaTotal = $precioCestaTotal + ($precioUd * $cantidadProvisional);
    }
    $sql = "UPDATE cestas set precioTotal = $precioCestaTotal where idCesta = (Select idCesta from usuarios where usuario = '$usuario')";
    $conexion->query($sql);

    $sql = "SELECT precioTotal from cestas where usuario = '$usuario'";
    $precioTotal = $conexion->query($sql)->fetch_assoc()["precioTotal"];
    $productosCesta = [];
    while ($fila = $resultado->fetch_assoc()) {
        $nuevo_productoCesta = new Producto(
            $fila["idProducto"],
            $fila["nombreProducto"],
            $fila["precio"],
            $fila["descripcion"],
            $fila["cantidad"],
            $fila["imagen"]
        );
        array_push($productosCesta, $nuevo_productoCesta);
    }
    ?>
    <!-- Contenido de la página -->
    <h2 class="mb-5 text-center">Mi cesta</h2>
    <div class="container text-center">
        <?php
        if (count($productosCesta) == 0) {
            echo "<h4>No hay productos actualmente</h4>";
        } else {
            ?>
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-success">
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Precio ud</th>
                        <th>Precio total</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($productosCesta as $producto) {
                        ?>
                        <tr class="bg-dark">
                            <td><img style="width: 100px" src="<?php echo $producto->imagen ?>" alt="Foto"></td>
                            <td>
                                <?php echo $producto->nombreProducto ?>
                            </td>
                            <td>
                                <?php echo $producto->descripcion ?>
                            </td>
                            <td>
                                <?php echo $producto->cantidad ?>
                            </td>
                            <td>
                                <?php echo $producto->precio ?>€
                            </td>
                            <td>
                                <?php echo $producto->precio * $producto->cantidad ?>€
                            </td>
                            <td>
                                <form action="eliminar_productocesta.php" method="post">
                                    <input type="hidden" name="location" value="cesta">
                                    <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                    <button class="btn btn-danger" type="submit">Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="container bg-dark w-35 float-end text-light">
                <div class="row row-cols-2 h-100 p-2">
                    <p class="text-start fs-5 my-auto">Precio total:
                        <?php echo $precioTotal ?>€
                    </p>
                    <form class="text-end my-auto" action="" method="post">
                        <input class="btn btn-success" type="submit" value="Realizar pedido">
                    </form>
                </div>
            </div>
        </div>
        </div>
        <?php
        }
        ?>
    </div>