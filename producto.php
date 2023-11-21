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

    // comprobar si el usuario ha sido eliminado
    $res = mysqli_query($conexion, "select usuario from usuarios where usuario = '$usuario'");
    if (mysqli_num_rows($res) == 0) {
        session_destroy();
    }

    //recuperamos
    $idProducto = $_GET["id"];
    require 'util/nav.php';
    $sql = "SELECT * FROM productos WHERE idProducto = '$idProducto'";
    if ($resultado = $conexion->query($sql)) {
        $fila = $resultado->fetch_row();
        $producto = new Producto(
            $fila[0],
            $fila[1],
            $fila[2],
            $fila[3],
            $fila[4],
            $fila[5]
        );
    }
    ?>
    <div class="container-sm w-30 mt-4">
        <div class="product-item bg-light mb-4">
            <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100" src="<?php echo $producto->imagen ?>" alt="">
            </div>
            <div class="text-center py-4">
                <h6 text-decoration-none text-truncate>
                    <?php echo $producto->nombreProducto ?>
                </h6>

                <div class="d-flex align-items-center justify-content-center mt-2">
                    <h5>
                        <?php echo "Precio: " . $producto->precio . " €" ?>
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-center mt-2">
                    <form action="" method="post">
                        <label>Seleccione cantidad</label>
                        <select name="cantidad" id="">
                            <?php
                            for ($i=0;($i<=5)&&($i <= $producto->cantidad) ; $i++) { 
                                echo "<option value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" value="Comprar">
                    </form>
                </div>
            </div>
        </div>
    </div>