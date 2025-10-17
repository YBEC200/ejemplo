<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/logotipo.png" type="image/png"/>
	<!--plugins-->
	<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet"/>
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
  	<link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.18/jspdf.plugin.autotable.min.js"></script>
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="assets/css/dark-theme.css"/>
	<link rel="stylesheet" href="assets/css/semi-dark.css"/>
	<link rel="stylesheet" href="assets/css/header-colors.css"/>

	<title>Administrador - Botica San Antonio</title>
</head>

<body>
	<div class="wrapper">

		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="assets/images/logotipo.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text">Botica S.A</h4>
				</div>
				<div class="toggle-icon ms-auto" style="color: #333333; ">
				<i class='bx bx-menu'></i>
				</div>
		</div>
			 <ul class="metismenu" id="menu">
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-home-alt'></i></div>
						<div class="menu-title">Dashboard</div>
					</a>
					<ul>
						<li><a href="Analisis-ventas.html"><i class='bx bx-radio-circle'></i>Análisis de Ventas</a></li>
						<li><a href="Gestion-ventas.html"><i class='bx bx-radio-circle'></i>Gestión de Ventas</a></li>
						<li><a href="Notificaciones-Alertas.html"><i class='bx bx-radio-circle'></i>Notificaciones y Alertas</a></li>
					</ul>
				</li>

		
				<li class="menu-label">Gestión de Productos</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-box'></i></div>
						<div class="menu-title">Productos</div>
					</a>
					<ul>
						<li><a href="Gestión-Productos.html"><i class='bx bx-radio-circle'></i>Gestión de Productos</a></li>
						<li><a href="Gestión-Categorias-Presentaciones.html"><i class='bx bx-radio-circle'></i>Tipos de Productos</a></li>
						<li><a href="Detalle-Productos.html"><i class='bx bx-radio-circle'></i>Detalles de Producto</a></li>
					</ul>
				</li>
				

				<li class="menu-label">Gestión de Pedidos</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-cart'></i></div>
						<div class="menu-title">Pedidos</div>
					</a>
					<ul>
						<li><a href="Gestion-Pedidos.html"><i class='bx bx-radio-circle'></i>Gestión de Pedidos</a></li>
						<li><a href="Historial-Pedidos.html"><i class='bx bx-radio-circle'></i>Historial de Pedidos</a></li>
					</ul>
				</li>


				<li class="menu-label">Boleta Electrónica</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-file'></i></div>
						<div class="menu-title">Boleta</div>
					</a>
					<ul>
						<li><a href="Boleta-Electronica.html"><i class='bx bx-radio-circle'></i>Ver Boleta Electrónica</a></li>
						<li><a href="Actualizar-Boleta-Electronica.html"><i class='bx bx-radio-circle'></i>Actualizar Boleta</a></li>
					</ul>
				</li>


				<li class="menu-label">Usuarios</li>
				<li>
					<a href="Gestion-clientes.html">
						<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
						<div class="menu-title">Lista de Usuarios</div>
					</a>
				</li>
				
			</ul>
	</div>
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					  <div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center gap-1">
							<div class="app-container p-2 my-2"> </div>
							<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
								<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
								</a>
							</li>
							<li class="nav-item dark-mode d-none d-sm-flex">
								<a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
								</a>
							</li>
							<li class="nav-item dropdown dropdown-app">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
								<div class="dropdown-menu dropdown-menu-end p-0">
									<div class="app-container p-2 my-2">
									  <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/slack.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Slack</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/behance.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Behance</p>
											  </div>
											  </div>
										  </a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												<img src="assets/images/app/google-drive.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Dribble</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/outlook.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Outlook</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/github.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">GitHub</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/stack-overflow.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Stack</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/figma.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Stack</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/twitter.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Twitter</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/google-calendar.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Calendar</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/spotify.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Spotify</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/google-photos.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Photos</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/pinterest.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Photos</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/linkedin.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">linkedin</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/dribble.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Dribble</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/youtube.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">YouTube</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/google.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">News</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/envato.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Envato</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="assets/images/app/safari.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Safari</p>
											  </div>
											  </div>
											</a>
										 </div>
									  </div>
									</div>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">7</span>
									<i class='bx bx-bell'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notificaciones</p>
											<p class="msg-header-badge">7 Nuevos</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson<span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>
										
									</div>
									<a href="Notificaciones-Alertas.html">
										<div class="text-center msg-footer">
											<button class="btn btn-primary w-100">View All Notifications</button>
										</div>
									</a>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<div class="dropdown-menu dropdown-menu-end">
									<div class="header-message-list">	
									</div>
								</div>
							</li>
						</ul>
					</div>


					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="assets/images/avatars/avatar-1.png" class="user-img" alt="user avatar">
							<div class="user-info">
								<p class="user-name mb-0">Jose Enrique</p>
								<p class="designattion mb-0">Propietario</p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<a class="dropdown-item d-flex align-items-center" href="Perfil-Usuario.html"><i class="bx bx-user fs-5"></i><span>Perfil</span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0" ></div>
							</li>
							<li><a class="dropdown-item d-flex align-items-center" href="../../../public/index.html"><i class="bx bx-log-out-circle"></i><span>Cerrar Sesión</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>




		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
		
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Comercio Electrónico</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Gestión de Ventas</li>
							</ol>
						</nav>
					</div>
				</div>
		
				<div class="card radius-10">
					<div class="card-header">
						<div class="d-flex align-items-center">
							<div>
								<h6 class="mb-0">Gestión de Ventas</h6>
							</div>
							<div class="dropdown ms-auto">
								<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
									<i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
								</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href="javascript:exportTableToExcel('ventasTable', 'Ventas')">Exportar a Excel</a></li>
									<li><a class="dropdown-item" href="javascript:exportStyledTableToPDF()">Exportar a PDF</a></li>
								</ul>
							</div>
						</div>
					</div>
		
					<div class="card-body">
						<!-- Formulario para registrar una nueva venta -->
						<div class="mb-4">
							<h6>Registrar Nueva Venta</h6>
							<form id="ventasForm" class="row g-3">
								<div class="col-md-3">
									<label for="cliente" class="form-label">Cliente</label>
									<input type="text" class="form-control" id="cliente" placeholder="Nombre del cliente">
								</div>
								<div class="col-md-3">
									<label for="metodoPago" class="form-label">Método de Pago</label>
									<select id="metodoPago" class="form-select">
										<option value="Efectivo">Efectivo</option>
										<option value="Tarjeta">Tarjeta</option>
										<option value="Transferencia">Transferencia</option>
									</select>
								</div>
								<div class="col-md-2">
									<label for="total" class="form-label">Total (S/)</label>
									<input type="number" class="form-control" id="total" placeholder="Monto total">
								</div>
								<div class="col-md-2">
									<label for="fechaVenta" class="form-label">Fecha</label>
									<input type="date" class="form-control" id="fechaVenta">
								</div>
								<div class="col-md-2 d-flex align-items-end">
									<button type="button" class="btn btn-primary w-100" onclick="registrarVenta()">Registrar</button>
								</div>
							</form>
						</div>
		
						<!-- Tabla de ventas -->
						<div class="table-responsive">
							<h6 class="mb-3">Ventas Realizadas</h6>
							<table class="table align-middle mb-0" id="ventasTable">
								<thead class="table-light">
									<tr>
										<th>Venta ID</th>
										<th>Cliente</th>
										<th>Método de Pago</th>
										<th>Total (S/)</th>
										<th>Fecha</th>
										<th>Boleta/Factura</th>
									</tr>
								</thead>
								<tbody id="ventasBody">
									<tr>
										<td>V-0001</td>
										<td>Juan Pérez</td>
										<td>Efectivo</td>
										<td>150.00</td>
										<td>2025-01-27</td>
										<td><button class="btn btn-sm btn-info" onclick="generarDocumento('V-0001')">Generar</button></td>
									</tr>
									<tr>
										<td>V-0002</td>
										<td>María López</td>
										<td>Tarjeta</td>
										<td>300.00</td>
										<td>2025-01-26</td>
										<td><button class="btn btn-sm btn-info" onclick="generarDocumento('V-0002')">Generar</button></td>
									</tr>
								</tbody>
							</table>
						</div>
		
						<!-- Reportes -->
						<div class="mt-4">
							<h6>Reportes</h6>
							<div class="row g-3">
								<div class="col-md-4">
									<label for="filtroReporte" class="form-label">Generar Reporte</label>
									<select id="filtroReporte" class="form-select">
										<option value="Diario">Diario</option>
										<option value="Semanal">Semanal</option>
										<option value="Mensual">Mensual</option>
									</select>
								</div>
								<div class="col-md-4 d-flex align-items-end">
									<button class="btn btn-success w-100" onclick="generarReporte()">Generar Reporte</button>
								</div>
							</div>
						</div>
					</div>
				</div>
		
			</div>
		</div>

		 <div class="overlay toggle-icon"></div>
	
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
	</div>





































    	<!-- search modal -->
        <div class="modal" id="SearchModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header gap-2">
                  <div class="position-relative popup-search w-100">
                    <input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="Search">
                    <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
                  </div>
                  <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search-list">
                       <p class="mb-1">Html Templates</p>
                       <div class="list-group">
                          <a href="javascript:;" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bxl-angular fs-4'></i>Best Html Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vuejs fs-4'></i>Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-magento fs-4'></i>Responsive Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-shopify fs-4'></i>eCommerce Html Templates</a>
                       </div>
                       <p class="mb-1 mt-3">Web Designe Company</p>
                       <div class="list-group">
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-windows fs-4'></i>Best Html Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-dropbox fs-4' ></i>Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-opera fs-4'></i>Responsive Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-wordpress fs-4'></i>eCommerce Html Templates</a>
                       </div>
                       <p class="mb-1 mt-3">Software Development</p>
                       <div class="list-group">
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-mailchimp fs-4'></i>Best Html Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-zoom fs-4'></i>Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-sass fs-4'></i>Responsive Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vk fs-4'></i>eCommerce Html Templates</a>
                       </div>
                       <p class="mb-1 mt-3">Online Shoping Portals</p>
                       <div class="list-group">
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Html Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-skype fs-4'></i>Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-twitter fs-4'></i>Responsive Html5 Templates</a>
                          <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vimeo fs-4'></i>eCommerce Html Templates</a>
                       </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        <!-- end search modal -->




	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="assets/plugins/chartjs/js/chart.js"></script>
    <script src="assets/plugins/sparkline-charts/jquery.sparkline.min.js"></script>
	<script src="assets/js/index.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar(".app-container")
	</script>
</body>

</html>