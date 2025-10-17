<?php
$current_page = basename($_SERVER['PHP_SELF']);
function isActive($pages) {
    global $current_page;
    return in_array($current_page, (array)$pages) ? 'active' : '';
}
function isShow($pages) {
    global $current_page;
    return in_array($current_page, (array)$pages) ? 'show' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
	.arrow-menu {
		transition: transform 0.3s;
	}
	.arrow-menu.collapsed {
		transform: rotate(-90deg);
	}
	.arrow-menu.expanded {
		transform: rotate(180deg);
	}
	.logo-text .cd {
		color: #222;
		font-weight: bold;
	}
	.logo-text .tech {
		color: #36a2e0;
		font-weight: bold;
	}
	.metismenu .active {
        background: #2196f3 !important;
        color: #fff !important;
        border-radius: 8px;
    }
    .metismenu .active i {
        color: #fff !important;
    }
</style>
<body>
    <div class="wrapper">
    <div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="assets/images/CD_IMAGEN.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text">
						<span class="cd">CD</span><span class="tech">TECH</span>
					</h4>
				</div>
				<div class="toggle-icon ms-auto" style="color: #333333; ">
				<i class='bx bx-menu'></i>
				</div>
			</div>
			<ul class="metismenu" id="menu">
				<li>
					<a data-bs-toggle="collapse" href="#submenuProductos" role="button" aria-expanded="<?php echo isShow(['dashboard.php','historial-pedidos.php','Notificaciones-Alertas.php']) ? 'true' : 'false'; ?>" aria-controls="submenuProductos" class="d-flex align-items-center">
						<div class="parent-icon"><i class='bx bx-home'></i></div>
						<div class="menu-title flex-grow-1">Dashboard</div>
						<i class="bx bx-chevron-down ms-auto arrow-menu"></i>
					</a>
					<ul class="collapse <?php echo isShow(['dashboard.php','historial-pedidos.php','Notificaciones-Alertas.php']); ?>" id="submenuProductos">
						<li><a href="dashboard.php" class="<?php echo isActive('dashboard.php'); ?>"><i class='bx bx-bar-chart'></i>Analisis de Ventas</a></li>
						<li><a href="historial-pedidos.php" class="<?php echo isActive('historial-pedidos.php'); ?>"><i class='bx bx-list-ul'></i>Lista de Pendiente</a></li>
						<li><a href="Notificaciones-Alertas.php" class="<?php echo isActive('Notificaciones-Alertas.php'); ?>"><i class='bx bx-bell'></i>Notificaciones y alertas</a></li>
					</ul>
				</li>
				<li class="menu-label">Gestión de Ventas</li>
				<li>
					<a data-bs-toggle="collapse" href="#submenuPedidos" role="button" aria-expanded="<?php echo isShow(['Gestion-Pedidos.php','Asignar-Pedidos.php']); ?>" aria-controls="submenuPedidos" class="d-flex align-items-center">
						<div class="parent-icon"><i class='bx bx-cart'></i></div>
						<div class="menu-title flex-grow-1">Pedidos</div>
						<i class="bx bx-chevron-down ms-auto arrow-menu"></i>
					</a>
					<ul class="collapse <?php echo isShow(['Gestion-Pedidos.php','Asignar-Pedidos.php']); ?>" id="submenuPedidos">
						<li><a href="Gestion-Pedidos.php" class="<?php echo isActive('Gestion-Pedidos.php'); ?>"><i class='bx bx-task'></i>Gestión de Pedidos</a></li>
						<li><a href="Asignar-Pedidos.php" class="<?php echo isActive('Asignar-Pedidos.php'); ?>"><i class='bx bx-bell'></i>Asignar Pedido</a></li>
					</ul>
					<span></span>
					<a data-bs-toggle="collapse" href="#submenuMantenimientos" role="button" aria-expanded="<?php echo isShow(['Gestion-Mantenimiento.php','Asignar-Mantenimiento.php']); ?>"  aria-controls="submenuMantenimientos" class="d-flex align-items-center">
						<div class="parent-icon"><i class='bx bx-wrench'></i></div>
						<div class="menu-title flex-grow-1">Mantenimientos</div>
						<i class="bx bx-chevron-down ms-auto arrow-menu"></i>
					</a>
					<ul class="collapse <?php echo isShow(['Gestion-Mantenimiento.php','Asignar-Mantenimiento.php']); ?>" id="submenuMantenimientos">
						<li><a href="" class="<?php echo isActive('Gestion-Mantenimiento.php'); ?>"><i class='bx bx-cog'></i>Gestión de Mantenimientos</a></li>
						<li><a href="" class="<?php echo isActive('Asignar-Mantenimiento.php'); ?>"><i class='bx bx-user-plus'></i>Asignar Mantenimiento</a></li>
					</ul>
				</li>
				<li class="menu-label">Gestion de Productos</li>
				<li>
					<a data-bs-toggle="collapse" href="#submenuBoleta" role="button" aria-expanded="<?php echo isShow(['Gestion-Productos.php','Agregar-Productos.php','Agregar-Categoria']); ?>" aria-controls="submenuBoleta" class="d-flex align-items-center">
						<div class="parent-icon"><i class='bx bx-package'></i></div>
						<div class="menu-title flex-grow-1">Producto</div>
						<i class="bx bx-chevron-down ms-auto arrow-menu"></i>
					</a>
					<ul class="collapse <?php echo isShow(['Gestion-Productos.php','Agregar-Productos.php','Agregar-Categoria']); ?>" id="submenuBoleta">
						<li><a href="Gestion-Productos.php" class="<?php echo isActive('Gestion-Productos.php'); ?>"><i class='bx bx-box'></i>Gestión de Productos</a></li>
						<li><a href="Agregar-Categoria.php" class="<?php echo isActive('Agregar-Categoria.php'); ?>"><i class='bx bx-plus-circle'></i>Agregar Categoria o Lote</a></li>
						<li><a href="Agregar-Productos.php" class="<?php echo isActive('Agregar-Productos.php'); ?>"><i class='bx bx-layer-plus'></i>Agregar Producto</a></li>
					</ul>
				</li>
				<li class="menu-label">Usuarios</li>
				<li>
					<a href="Gestion-clientes.php"  class="<?php echo isActive('Gestion-clientes.php'); ?>">
						<div class="parent-icon"><i class="bx bx-user"></i></div>
						<div class="menu-title">Lista de Usuarios</div>
					</a>
				</li>				
			</ul>
    </div>
	<script>
		// Rota todas las flechas de los menús desplegables
		document.querySelectorAll('a[data-bs-toggle="collapse"]').forEach(function(toggle) {
			const arrow = toggle.querySelector('.arrow-menu');
			const targetId = toggle.getAttribute('href');
			const submenu = document.querySelector(targetId);

			if (arrow && submenu) {
				// Estado inicial
				if (!submenu.classList.contains('show')) {
					arrow.classList.add('collapsed');
					arrow.classList.remove('expanded');
				} else {
					arrow.classList.add('expanded');
					arrow.classList.remove('collapsed');
				}
				submenu.addEventListener('show.bs.collapse', function () {
					arrow.classList.add('expanded');
					arrow.classList.remove('collapsed');
				});
				submenu.addEventListener('hide.bs.collapse', function () {
					arrow.classList.add('collapsed');
					arrow.classList.remove('expanded');
				});
			}
		});
	</script>
	</body>
</html>