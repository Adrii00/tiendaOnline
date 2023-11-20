<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="./">Tienda</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                if ($rol == "admin") {
                ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Administrador
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="administrar_usuarios.php">Ver usuarios</a></li>
                            <li><a class="dropdown-item" href="insertar_productos.php">Insertar Producto</a></li>
                        </ul>
                    </li>
                <?php
                }
                ?>
            </ul>
            <?php
            if ($usuario == "invitado") {
            ?>
                <a class="btn btn-success" href="Sesiones/iniciar_sesion.php">Iniciar Sesión</a>
            <?php
            } else {
            ?>
                <a class="btn btn-success" href="Sesiones/cerrar_sesion.php">Cerrar Sesión</a>
            <?php
            }
            ?>
        </div>
    </div>
</nav>