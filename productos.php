<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <?php require "./conexion.php" ?>
</head>

<body>
    
    <?php
    function depurar($entrada)
    {
        return trim(htmlspecialchars($entrada));
    }
    session_start();
    if (($_SESSION["rol"]!="admin")) {
        header('location: sesiones/iniciar_sesion.php');
    } 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_nombre = depurar($_POST["nombre"]);
        $temp_descripcion = depurar($_POST["descripcion"]);
        $temp_precio = depurar($_POST["precio"]);
        $temp_cantidad = depurar($_POST["cantidad"]);
        $nombre_imagen = $_FILES["imagen"]["name"];
        $ruta_final = "img/" . $nombre_imagen;

        // * Comprobar nombre
        if (strlen($temp_nombre) == 0) {
            $err_nombre = "El nombre es obligatorio";
        } else {
            if (strlen($temp_nombre) > 40) {
                $err_nombre = "El nombre no puede tener mas de 40 caracteres";
            } else {
                $patron = "/^[A-Za-z0-9]*( [A-Za-z0-9]+)*$/";
                if (!preg_match($patron, $temp_nombre)) {
                    $err_nombre = "El nombre solo pude contener letras, numeros o espacios en blanco";
                } else {
                    $nombre = $temp_nombre;
                }
            }
        }

        // * Comprobar descripcion
        if (strlen($temp_descripcion) == 0) {
            $err_descripcion = "La descripcion es obligatoria";
        } else {
            if (strlen($temp_descripcion) > 255) {
                $err_descripcion = "La descripcion no puede tener mas de 255 caracteres";
            } else {
                $patron = "/^[A-Za-z0-9]*( [A-Za-z0-9]+)*$/";
                if (!preg_match($patron, $temp_descripcion)) {
                    $err_descripcion = "La descripcion solo pude contener letras, numeros o espacios en blanco";
                } else {
                    $descripcion = $temp_descripcion;
                }
            }
        }


        // * Comprobar precio
        if (strlen($temp_precio) == 0) {
            $err_precio = "El precio es obligatorio";
        } elseif (!is_numeric($temp_precio)) {
            $err_precio = "El precio debe ser un número";
        } elseif ($temp_precio < 0) {
            $err_precio = "El precio no puede ser negativo";
        } elseif ($temp_precio > 99999.99) {
            $err_precio = "El precio no puede ser mayor de 99999.99";
        } else {
            $precio = $temp_precio;
        }

        // * Comprobar cantidad
        if (strlen($temp_cantidad) == 0) {
            $err_cantidad = "La cantidad es obligatoria";
        } elseif (filter_var($temp_cantidad, FILTER_VALIDATE_INT) === false) { // Otra forma de hacerlo, el ctype_digit no acepta negativos mientras que el filter_var si, pero el 0 te devuelve FALSE ya que el "0" se evalua como FALSE. Una forma de arreglar esto es comprobando estrictamente como he hecho
            // } elseif (!ctype_digit($temp_cantidad)) {
            $err_cantidad = "La cantidad debe ser un número entero";
        } elseif ($temp_cantidad < 0) {
            $err_cantidad = "La cantidad no puede ser negativa";
        } elseif ($temp_cantidad > 99999) {
            $err_cantidad = "La cantidad no puede ser mayor de 99999";
        } else {
            $cantidad = $temp_cantidad;
        }
    }
    ?>
    <div class="container">
        <h1>Producto</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre del producto:</label>
                <input type="text" name="nombre" class="form-control">
                <?php if (isset($err_nombre)) echo "<label class='text-danger'>" . $err_nombre . "</label>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion del producto: </label>
                <input type="text" name="descripcion" class="form-control">
                <?php if (isset($err_descripcion)) echo "<label class='text-danger'>" . $err_descripcion . "</label>" ?>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Precio: </label>
                <input type="text" name="precio" class="form-control">
                <?php if (isset($err_precio)) echo "<label class='text-danger'>" . $err_precio . "</label>" ?>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Cantidad:</label>
                <input type="text" name="cantidad" class="form-control">
                <?php if (isset($err_cantidad)) echo "<label class='text-danger'>" . $err_cantidad . "</label>" ?>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Imagen:</label>
                <input type="file" name="imagen" class="form-control">
                <?php if (isset($err_imagen)) echo "<label class='text-danger'>" . $err_imagen . "</label>" ?>
            </div>
            <input class="btn btn-primary" type="submit" value="Enviar">
        </form>
    </div>

    <?php
    if (isset($nombre) && isset($descripcion) && isset($precio) && isset($cantidad)) {
        $sql = "INSERT INTO productos (nombreProducto, precio, descripcion, cantidad, imagen) VALUES ('$nombre', '$precio', '$descripcion', '$cantidad', '$ruta_final')";
        if ($conexion->query($sql)) {
            echo "Producto añadido correctamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
    }
    ?>
</body>

</html>