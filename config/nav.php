<?php
// Conexión a la base de datos (ajusta según tu archivo de conexión)
include 'database.php';

// Consulta para obtener el usuario administrador
$sql = "SELECT u.Nombre, u.Apellido_Paterno, r.Cargo FROM USUARIO u
        INNER JOIN ROL r ON u.Id_Rol = r.Id
        WHERE r.Rol = 'Admin' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    $admin = ['Nombre' => 'Admin', 'Apellido_Paterno' => '', 'Cargo' => 'Administrador'];
}


// Consulta para obtener las alertas de productos agotados
$sqlProductosAgotados = "SELECT Id, Nombre, Imagen_Principal, Estado FROM productos WHERE Estado = 'Agotado'";
$resultAgotados2 = $conn->query($sqlProductosAgotados);

// Consulta para obtener productos con lotes menores a 50
$sqlLotesMenores = "
    SELECT p.Id, p.Nombre, p.Imagen_Principal, SUM(l.Cantidad) AS TotalCantidad
    FROM productos p
    INNER JOIN lote l ON p.Id = l.Id_Producto
    GROUP BY p.Id
    HAVING TotalCantidad < 50
";
$resultLotesMenores2 = $conn->query($sqlLotesMenores);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
        .topbar {
            background: #2196f3;
            min-height: 60px;
            border-bottom: 2px solid #1976d2;
        }
        .navbar-custom-icons .nav-link {
            color: #111;
            font-size: 2rem;
            position: relative;
            padding: 0 10px;
        }
        .navbar-custom-icons .nav-link .badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%,-50%);
            background: #f44336;
            color: #fff;
            font-size: 0.8rem;
            border-radius: 50%;
            padding: 2px 6px;
        }
        .user-box {
			display: flex;
			align-items: center;
			border-left: 3px solid #1976d2;   /* Más gruesa y azul */
			border-right: 3px solid #1976d2;  /* Línea derecha igual que la izquierda */
			padding-left: 20px;
			padding-right: 20px;
			min-width: 220px;
			background: rgba(255,255,255,0.05);
		}
        .user-info {
            margin-right: 15px;
        }
        .user-name {
            font-weight: bold;
            color: #222;
            margin: 0;
            font-size: 1.1rem;
        }
        .user-role {
            font-size: 0.9rem;
            color: #555;
            margin: 0;
        }
        .user-avatar {
            width: 44px;
            height: 44px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #1976d2;
        }
        .user-avatar i {
            font-size: 2rem;
            color: #111;
        }
		.dropdown-menu {
			max-height: 400px; /* Altura máxima del dropdown */
			overflow: hidden; /* Evitar scrollbar en el dropdown */
			border-radius: 12px;
			box-shadow: 0 4px 24px rgba(33, 150, 243, 0.10);
			border: 1.5px solid #1976d2;
			min-width: 300px; /* Ancho aumentado */
			padding: 0;
			background: #fdfdfdff;
		}

		.dropdown-item {
			display: flex;
			align-items: center;
			gap: 8px; /* Espaciado reducido */
			padding: 8px 12px; /* Menor padding */
			font-size: 0.9rem; /* Texto más pequeño */
			transition: background 0.2s, color 0.2s;
			word-wrap: break-word; /* Permitir que el texto baje de línea */
        	white-space: normal; /* Texto en múltiples líneas */
		}

		.dropdown-item:hover {
			background: #e3f2fd;
			color: #1976d2;
		}

		.msg-header-title {
			font-size: 1rem; /* Texto más pequeño */
		}

		.msg-header-badge {
			font-size: 0.8rem; /* Texto más pequeño */
			padding: 2px 6px; /* Tamaño reducido */
		}

		.msg-header {
			position: sticky;
			top: 0;
			z-index: 10;
			background: #fff;
			padding: 10px;
			border-bottom: 1px solid #ddd;
		}

		.msg-footer {
			position: sticky;
			bottom: 0;
			z-index: 10;
			background: #fff;
			padding: 10px;
			border-top: 1px solid #ddd;
		}

		.header-notifications-list {
			max-height: 300px; /* Altura máxima del contenido desplazable */
			overflow-y: auto; /* Habilitar scrollbar solo aquí */
		}

		.msg-footer button {
			font-size: 0.9rem;
			padding: 8px 12px;
		}

		.dropdown-menu .dropdown-item:hover, 
		.dropdown-menu .dropdown-item:focus {
			background: #e3f2fd;
			color: #1976d2;
		}
		.dropdown-divider {
			margin: 0;
			border-top: 1.5px solid #1976d2;
		}
        @media (max-width: 600px) {
            .user-box { min-width: 120px; padding-left: 5px; }
            .user-info { margin-right: 5px; }
            .user-name { font-size: 0.95rem; }
        }
		.dropdown-menu::before, .dropdown-menu::after {
			display: none !important;
		}
		#nav-container .alert-image {
			width: 150px !important; /* Tamaño reducido */
			height: 100px !important; /* Tamaño reducido */
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Sombra más pequeña */
		}

		#nav-container .alert-image img {
			width: 100%;
			height: 100%;
			border-radius: 50%; /* Imagen circular */
			object-fit: cover; /* Ajustar imagen */
		}

		#nav-container .alert-image.riesgo-alto img {
			border: 2px solid #ff0000; /* Borde rojo para riesgo alto */
		}

		#nav-container .alert-image.riesgo-medio img {
			border: 2px solid #ffa500; /* Borde naranja para riesgo medio */
		}

		#nav-container .dropdown-item {
			display: flex;
			align-items: center;
			gap: 8px; /* Espaciado reducido */
			padding: 8px 12px; /* Menor padding */
			font-size: 0.9rem; /* Texto más pequeño */
			transition: background 0.2s, color 0.2s;
		}

		#nav-container .dropdown-item:hover {
			background: #f1f1f1;
		}

		#nav-container .msg-header-title {
			font-size: 1rem; /* Texto más pequeño */
		}

		#nav-container .msg-header-badge {
			font-size: 0.8rem; /* Texto más pequeño */
			padding: 2px 6px; /* Tamaño reducido */
		}
	</style>
<body>
    <div id="nav-container">
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand gap-3">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <div class="app-container p-2 my-2"></div>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class='bx bxl-reddit'></i></a>
                            </li>
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown">
                                    <span class="alert-count">
                                        <?php echo ($resultAgotados2->num_rows + $resultLotesMenores2->num_rows); ?>
                                    </span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="msg-header sticky-top bg-white">
                                        <p class="msg-header-title">Notificaciones</p>
                                        <p class="msg-header-badge">
                                            <?php echo ($resultAgotados2->num_rows + $resultLotesMenores2->num_rows); ?> Nuevas
                                        </p>
                                    </div>
                                    <div class="header-notifications-list" style="max-height: 300px; overflow-y: auto;">
                                        <!-- Alertas de productos agotados -->
                                        <?php if ($resultAgotados2->num_rows > 0): ?>
                                            <?php while ($producto = $resultAgotados2->fetch_assoc()): ?>
                                                <a class="dropdown-item" href="javascript:;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="alert-image riesgo-alto">
                                                            <?php if (!empty($producto['Imagen_Principal'])): ?>
                                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['Imagen_Principal']); ?>" alt="<?php echo htmlspecialchars($producto['Nombre']); ?>">
                                                            <?php else: ?>
                                                                <img src="assets/images/default.png" alt="Imagen predeterminada">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="msg-name">Stock agotado<span class="msg-time float-end">Hace poco</span></h6>
                                                            <p class="msg-info">Se necesita reponer el stock del producto "<?php echo htmlspecialchars($producto['Nombre']); ?>".</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                        <!-- Alertas de lotes menores a 50 -->
                                        <?php if ($resultLotesMenores2->num_rows > 0): ?>
                                            <?php while ($producto = $resultLotesMenores2->fetch_assoc()): ?>
                                                <a class="dropdown-item" href="javascript:;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="alert-image riesgo-medio">
                                                            <?php if (!empty($producto['Imagen_Principal'])): ?>
                                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['Imagen_Principal']); ?>" alt="<?php echo htmlspecialchars($producto['Nombre']); ?>">
                                                            <?php else: ?>
                                                                <img src="assets/images/default.png" alt="Imagen predeterminada">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="msg-name">Pocos existencias<span class="msg-time float-end">Hace poco</span></h6>
                                                            <p class="msg-info">Quedan menos de 50 existencias del producto "<?php echo htmlspecialchars($producto['Nombre']); ?>".</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-center msg-footer sticky-bottom bg-white">
                                        <a href="Notificaciones-Alertas.php">
                                            <button class="btn btn-primary w-100">Ir a todas las notificaciones</button>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown px-3">
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="assets/images/avatars/avatar-1.png" class="user-img" alt="user avatar">
                            <div class="user-info">
                                <div>
                                    <p class="user-name mb-0">
                                        <?php echo htmlspecialchars($admin['Nombre'] ?? ''); ?>
                                        <?php echo htmlspecialchars($admin['Apellido_Paterno'] ?? ''); ?>
                                    </p>
                                </div>
                                <p class="user-role mb-0"><?php echo htmlspecialchars($admin['Cargo']); ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="Perfil-Usuario.php"><i class="bx bx-user fs-5"></i><span>Perfil</span></a>
                            </li>
                            <li><div class="dropdown-divider mb-0"></div></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="../../../public/index.php"><i class="bx bx-log-out-circle"></i><span>Cerrar Sesión</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
    </div>
</body>
</html>