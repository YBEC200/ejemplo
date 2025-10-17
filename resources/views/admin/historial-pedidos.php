<?php
// Conexión a la base de datos
include_once '../../../config/database.php';
// Consulta para obtener la cantidad de ventas pendientes
$sqlVentasPendientes = "SELECT COUNT(*) AS ventasPendientes FROM ventas WHERE Estado = 'Pendiente'";
$resultVentasPendientes = $conn->query($sqlVentasPendientes);
$ventasPendientes = $resultVentasPendientes->fetch_assoc()['ventasPendientes'] ?? 0;

// Consulta para obtener la cantidad de mantenimientos pendientes
$sqlMantenimientosPendientes = "SELECT COUNT(*) AS mantenimientosPendientes FROM mantenimiento WHERE Costo_Total = 0";
$resultMantenimientosPendientes = $conn->query($sqlMantenimientosPendientes);
$mantenimientosPendientes = $resultMantenimientosPendientes->fetch_assoc()['mantenimientosPendientes'] ?? 0;
// Consulta para obtener los pedidos con estado "Pendiente"
$sqlPendientes = "SELECT 
    ventas.Id AS PedidoID, 
    usuario.Nombre AS Cliente, 
    ventas.Fecha_Pedido AS FechaPedido, 
    ventas.Tipo AS Tipo,
    ventas.Costo_total AS Total
    FROM ventas
    INNER JOIN usuario ON ventas.Id_Usuario = usuario.Id
    WHERE ventas.Estado = 'Pendiente'
    ORDER BY ventas.Fecha_Pedido DESC";

$resultPendientes = $conn->query($sqlPendientes);

// AJAX para detalle de venta
if (isset($_GET['ajax_detalle_venta']) && isset($_GET['id_venta'])) {
    $id_venta = intval($_GET['id_venta']);
    $sql = "SELECT 
                productos.Nombre AS producto,
                detalle_venta.Cantidad AS cantidad,
                detalle_venta.Costo AS costo
            FROM detalle_venta
            INNER JOIN productos ON detalle_venta.Id_Producto = productos.Id
            WHERE detalle_venta.Id_Venta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['producto']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
            echo "<td>S/" . number_format($row['costo'], 2) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center'>No hay productos en esta venta</td></tr>";
    }
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/CD_IMAGEN.png" type="image/png"/>
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
	<title>Administrador - Notificaciones y Alertas</title>
</head>

<body>
	<div class="wrapper">
		<?php include_once '../../../config/sidebar.php'; ?>
	</div>
		<header>
			<?php include_once '../../../config/nav.php'; ?>
		</header>
<style>
    .table td, .table th {
        vertical-align: middle;
        text-align: center;
        border: 1px solid #ddd;
        padding: 10px 15px;
    }

    .table-hover tbody tr:hover {
        background-color: #f9f9f9;
    }

    .cliente-nombre {
        background-color: #509faf;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
        text-align: center;
        box-shadow: 0 2px 4px #2f7c80;
    }
	.btn-action {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        font-size: 18px;
    }
</style>
		<!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
			<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
				<div class="breadcrumb-title pe-3">Dashboard</div>
				<div class="ps-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb mb-0 p-0">
							<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
							<li class="breadcrumb-item active" aria-current="page">Lista de pendientes</li>
						</ol>
					</nav>
				</div>
			</div>

			<!-- Tarjetas para Ventas y Mantenimientos -->
			<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
				<div class="col">
					<div class="card radius-10 bg-gradient-cosmic" id="ventasCard">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="mb-0 text-white" style="font-weight: 600;">Ventas Pendientes</p>
									<h4 class="my-1 text-white"><?php echo $ventasPendientes; ?></h4>
								</div>
								<div>
									<i class="bx bx-cart text-white" style="font-size: 2.5rem;"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card radius-10 bg-gradient-kyoto" id="mantenimientosCard">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="me-auto">
									<p class="mb-0 text-white" style="font-weight: 600;">Mantenimientos Pendientes</p>
									<h4 class="my-1 text-white"><?php echo $mantenimientosPendientes; ?></h4>
								</div>
								<div>
									<i class="bx bx-wrench text-white" style="font-size: 2.5rem;"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card radius-10">
				<div class="card-header">
					<div class="d-flex align-items-center">
						<div>
						<h6 class="mb-0">Gestión de Pedidos</h6>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<div class="d-flex justify-content-between mb-3">
							<div class="d-flex align-items-center gap-3 flex-grow-1 flex-wrap">
                                    <div class="d-flex align-items-center gap-3">
                                    <!--<label for="showSelect">Mostrar</label>
                                    <select id="showSelect" class="form-select w-auto" style="cursor: pointer;">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                    </select>-->
                                    </div>
                                    <div class="position-relative flex-grow-1">
                                    <input type="search" class="form-control ps-5 radius-30" placeholder="Buscar según el cliente" id="searchInput"  onkeyup="searchClient()">
                                    <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 ms-5">
                                    <div class="dropdown me-4">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="filterDateDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Filtrar por Fecha
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="filterDateDropdown">
                                            <li>
                                                <div class="d-flex gap-2">
                                                    <input type="date" class="form-control" id="startDate">
                                                    <span>a</span>
                                                    <input type="date" class="form-control" id="endDate">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                            
                                    <select class="form-select w-auto me-4" id="filterSelect">
										<option value="" selected>Filtrar por tipo</option>
										<option value="Envio">Envío</option>
										<option value="Recoger">Recoger</option>
									</select>
                                </div>
							</div>
							<div class="table-responsive mt-4">
								<table class="table align-middle mb-0">
									<thead class="table-light">
										<tr>
											<th>Pedido ID</th>
											<th>Cliente</th>
											<th>Fecha Pedido</th>
											<th>Tipo</th>
											<th>Total (S/)</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($resultPendientes->num_rows > 0) {
											while ($row = $resultPendientes->fetch_assoc()) {
												echo "<tr>";
												echo "<td>" . htmlspecialchars($row['PedidoID']) . "</td>";
												echo "<td><span class='badge cliente-nombre'>" . htmlspecialchars($row['Cliente']) . "</span></td>";
												echo "<td>" . htmlspecialchars($row['FechaPedido']) . "</td>";
												echo "<td>" . htmlspecialchars($row['Tipo']) . "</td>";
												echo "<td>S/" . number_format($row['Total'], 2) . "</td>";
												echo "<td><button type='button' class='btn btn-info btn-sm btn-action btn-ver-detalle' data-id='" . htmlspecialchars($row['PedidoID']) . "' title='Ver Detalle'>
														<i class='bx bx-list-ul'></i>
													</button></td>";
												echo "</tr>";
											}
										} else {
											echo "<tr><td colspan='6' class='text-center'>No se encontraron pedidos pendientes</td></tr>";
										}
										?>
									</tbody>
								</table>

								<!-- Modal Detalle Venta -->
								<div class="modal fade" id="detalleVentaModal" tabindex="-1" aria-labelledby="detalleVentaModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="detalleVentaModalLabel">Detalle de la Venta</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
											</div>
											<div class="modal-body">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>Producto</th>
															<th>Cantidad</th>
															<th>Costo</th>
														</tr>
													</thead>
													<tbody id="detalleVentaBody">
														<tr><td colspan="3" class="text-center">Cargando...</td></tr>
													</tbody>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<script>
	function aplicarFiltros() {
		const searchValue = document.getElementById('searchInput').value.toLowerCase();
		const tipoValue = document.getElementById('filterSelect').value;
		const startDate = document.getElementById('startDate').value;
		const endDate = document.getElementById('endDate').value;

		const rows = document.querySelectorAll("table tbody tr");

		rows.forEach(row => {
			const cliente = row.querySelector("td:nth-child(2)")?.innerText.toLowerCase() || "";
			const fechaPedido = row.querySelector("td:nth-child(3)")?.innerText || "";
			const tipo = row.querySelector("td:nth-child(4)")?.innerText || "";

			let mostrar = true;

			// Filtro por cliente
			if (searchValue && !cliente.includes(searchValue)) {
				mostrar = false;
			}

			// Filtro por tipo
			if (tipoValue && tipo !== tipoValue) {
				mostrar = false;
			}

			// Filtro por fecha
			if (startDate || endDate) {
				const fecha = new Date(fechaPedido);
				if (startDate && fecha < new Date(startDate)) {
					mostrar = false;
				}
				if (endDate && fecha > new Date(endDate)) {
					mostrar = false;
				}
			}

			row.style.display = mostrar ? "" : "none";
		});
	}

	// Detectar cambios en los inputs
	document.getElementById('searchInput').addEventListener('keyup', aplicarFiltros);
	document.getElementById('filterSelect').addEventListener('change', aplicarFiltros);
	document.getElementById('startDate').addEventListener('change', aplicarFiltros);
	document.getElementById('endDate').addEventListener('change', aplicarFiltros);
	</script>
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script>
	document.querySelectorAll('.btn-ver-detalle').forEach(btn => {
		btn.addEventListener('click', function() {
			const ventaId = this.getAttribute('data-id');
			const modal = new bootstrap.Modal(document.getElementById('detalleVentaModal'));
			const tbody = document.getElementById('detalleVentaBody');
			tbody.innerHTML = '<tr><td colspan="3" class="text-center">Cargando...</td></tr>';

			fetch('Gestion-Pedidos.php?ajax_detalle_venta=1&id_venta=' + ventaId)
				.then(response => response.text())
				.then(html => {
					tbody.innerHTML = html;
				})
				.catch(() => {
					tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar el detalle</td></tr>';
				});
			modal.show();
		});
	});
	</script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="assets/plugins/chartjs/js/chart.js"></script>
	<script src="assets/js/index.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar(".app-container")
	</script>
</body>
</html>