<?php
include_once '../../../config/database.php';
if (isset($_POST['delete_pedido_id'])) {
    $id = intval($_POST['delete_pedido_id']);
    $conn->query("DELETE FROM detalle_venta WHERE Id_Venta = $id");
    $conn->query("DELETE FROM ventas WHERE Id = $id");
    exit;
}
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
// --- Cálculo de totales por estado ---
$totalPedidos = 0;
$pendientes = 0;
$entregados = 0;
$cancelados = 0;

$sqlTotales = "SELECT Estado, COUNT(*) AS cantidad FROM ventas GROUP BY Estado";
$resultTotales = $conn->query($sqlTotales);

if ($resultTotales) {
    while ($row = $resultTotales->fetch_assoc()) {
        $totalPedidos += $row['cantidad'];
        switch (strtolower($row['Estado'])) {
            case 'pendiente':
                $pendientes = $row['cantidad'];
                break;
            case 'entregado':
                $entregados = $row['cantidad'];
                break;
            case 'cancelado':
                $cancelados = $row['cantidad'];
                break;
        }
    }
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

	<title>Administrador - Gestión de Pedido</title>
</head>
<style>
    .form-control:hover{
    box-shadow: 0 2px 4px #fc9090;
    }

    .form-control:focus{
    outline: none !important ;
    box-shadow: none;
    box-shadow: 0 0 20px #ffa4a4;
    }
        
    .btn-export-excel, .btn-export-pdf {
    font-size: 13px;
    width: 100px;
    padding: 6px 10px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    justify-content: center;
    }

    .btn-export-excel i, .btn-export-pdf i{
    font-size: 18px;
    }

    

    @media (max-width: 995px) {
        .d-lg-flex {
            flex-wrap: wrap !important;
        }

        .d-lg-flex > div {
            width: 100%;
            margin-bottom: 10px; 
        }

        .btn-export-excel, .btn-export-pdf, .btn-primary {
            width: 100%;
            text-align: center;
        }

        .gap-2 {
            gap: 5px;
        }
        }
    .table td, .table th {
        vertical-align: middle;
        text-align: center;
        border: 1px solid #ddd;
        padding: 10px 15px;
    }


    .modal-header {
        border-bottom: 2px solid #ddd;
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-hover tbody tr:hover {
    background-color: #f9f9f9;
    }

    .table td {
    text-align: center !important;
    vertical-align: middle;
    line-height: 50px;
    }

    .table td span{
    text-align: center;
    vertical-align: middle;
    line-height: 12px;
    }

    .table td .badge-entregado {
    box-shadow: 0 2px 4px #b2dea9;
    }

    .table td .badge-pendiente {
    box-shadow: 0 2px 4px #d4e395;
    }

    .table td .badge-cancelado {
    box-shadow: 0 2px 4px #f0bcbc;
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
<body>
	<div class="wrapper">
      <?php include_once '../../../config/sidebar.php'; ?>
	</div>
		<header>
			<?php include_once '../../../config/nav.php'; ?>
		</header>
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Pedidos</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Gestión de pedidos</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                    <div class="col">
                        <div class="card radius-10 bg-gradient-cosmic">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-auto">
                                        <p class="mb-0 text-white" style="font-weight: 600;">Total Pedidos</p>
                                        <h4 class="my-1 text-white"><?php echo $totalPedidos; ?></h4>
                                    </div>
                                    <div>
                                        <i class="bx bx-cart text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-kyoto">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-auto">
                                        <p class="mb-0 text-white" style="font-weight: 600;">Pendientes</p>
                                        <h4 class="my-1 text-white"><?php echo $pendientes; ?></h4>
                                    </div>
                                    <div>
                                        <i class="bx bx-time text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-ohhappiness">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-auto">
                                        <p class="mb-0 text-white" style="font-weight: 600;">Entregados</p>
                                        <h4 class="my-1 text-white"><?php echo $entregados; ?></h4>
                                    </div>
                                    <div>
                                        <i class="bx bx-check-circle text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-ibiza">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-auto">
                                        <p class="mb-0 text-white" style="font-weight: 600;">Cancelados</p>
                                        <h4 class="my-1 text-white"><?php echo $cancelados; ?></h4>
                                    </div>
                                    <div>
                                        <i class="bx bx-x-circle text-white" style="font-size: 2.5rem;"></i>
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
                            <!--<div class="dropdown ms-auto">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:exportTableToExcel('tableData', 'Pedidos')">Exportar a Excel</a></li>
                                    <li><a class="dropdown-item" href="javascript:exportStyledTableToPDF()">Exportar a PDF</a></li>
                                </ul>
                            </div>-->
                        </div>
                    </div>

                    <style>
                        .badge {
                          display: inline-block;
                          padding: 0.5rem 1rem;
                          font-size: 0.8rem;
                          font-weight: 600;
                          border-radius: 20px; 
                          text-align: center;
                        }
                        .badge-pendiente {
                          background-color: rgb(255 193 7 / .11); 
                          color: #ffc107;
                        }
                        .badge-entregado {
                          background-color: rgb(23 160 14 / .11); 
                          color: #15ca20; 
                        }
                        .badge-cancelado {
                          background-color: rgb(244 17 39 / .11);
                          color: #fd3550;
                        }
                    </style>   
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
                                    <option value="" selected>Filtrar por Estado</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Entregado">Entregado</option>
                                    <option value="Cancelado">Cancelado</option>
                                    </select>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrderModal">
                                        <i class="bx bx-plus"></i> Crear Pedido
                                    </button>
                                </div>
                            </div>
                            <script>
                            // Filtro por cliente (barra de búsqueda)
                            function searchClient() {
                                const input = document.getElementById('searchInput'); // Obtener el campo de búsqueda
                                const filter = input.value.toLowerCase(); // Convertir el texto ingresado a minúsculas
                                const rows = document.querySelectorAll('#orderTable tr'); // Seleccionar todas las filas de la tabla

                                rows.forEach(row => {
                                    const clientCell = row.querySelector('td:nth-child(2)'); // Seleccionar la celda del cliente (2ª columna)
                                    if (clientCell) {
                                        const clientName = clientCell.textContent.toLowerCase(); // Obtener el texto del cliente en minúsculas

                                        // Mostrar u ocultar la fila según si coincide con el filtro
                                        if (clientName.includes(filter)) {
                                            row.style.display = ''; // Mostrar la fila
                                        } else {
                                            row.style.display = 'none'; // Ocultar la fila
                                        }
                                    }
                                });
                            }

                            // Filtro por fecha
                            document.getElementById('startDate').addEventListener('change', filterByDate);
                            document.getElementById('endDate').addEventListener('change', filterByDate);

                            function filterByDate() {
                                const startDate = document.getElementById('startDate').value; // Fecha de inicio
                                const endDate = document.getElementById('endDate').value; // Fecha de fin
                                const rows = document.querySelectorAll('#orderTable tr'); // Filas de la tabla

                                rows.forEach(row => {
                                    const dateCell = row.querySelector('td:nth-child(3)'); // Celda de la fecha (3ª columna)
                                    if (dateCell) {
                                        const rowDate = dateCell.textContent.trim(); // Fecha de la fila en formato YYYY-MM-DD

                                        // Mostrar u ocultar la fila según el rango de fechas
                                        if (
                                            (!startDate || rowDate >= startDate) && 
                                            (!endDate || rowDate <= endDate)
                                        ) {
                                            row.style.display = ''; // Mostrar la fila
                                        } else {
                                            row.style.display = 'none'; // Ocultar la fila
                                        }
                                    }
                                });
                            }

                            // Filtro por estado
                            document.getElementById('filterSelect').addEventListener('change', function () {
                                const filterValue = this.value.toLowerCase(); // Obtener el valor seleccionado y convertirlo a minúsculas
                                const rows = document.querySelectorAll('#orderTable tr'); // Seleccionar todas las filas de la tabla

                                rows.forEach(row => {
                                    const estadoCell = row.querySelector('td:nth-child(6)'); // Seleccionar la celda de "Estado" (6ª columna)
                                    if (estadoCell) {
                                        const estadoText = estadoCell.textContent.toLowerCase(); // Obtener el texto del estado en minúsculas

                                        // Mostrar u ocultar la fila según el filtro
                                        if (filterValue === '' || filterValue === 'mostrar todo' || estadoText === filterValue) {
                                            row.style.display = ''; // Mostrar la fila
                                        } else {
                                            row.style.display = 'none'; // Ocultar la fila
                                        }
                                    }
                                });
                            });
                            </script>
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pedido ID</th>
                                        <th>Cliente</th>
                                        <th>Fecha Pedido</th>
                                        <th>Tipo</th>
                                        <th>Total (S/)</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="orderTable">
                                <?php
                                // Consulta actualizada para la nueva estructura
                                $sql = "SELECT 
                                    ventas.Id AS PedidoID, 
                                    usuario.Nombre AS Cliente, 
                                    ventas.Fecha_Pedido AS FechaPedido, 
                                    ventas.Tipo AS Tipo,
                                    ventas.Costo_total AS Total, 
                                    ventas.Estado AS Estado
                                    FROM ventas
                                    INNER JOIN usuario ON ventas.Id_Usuario = usuario.Id
                                    ORDER BY ventas.Fecha_Pedido DESC";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['PedidoID']) . "</td>";
                                        echo "<td><span class='badge cliente-nombre'>" . htmlspecialchars($row['Cliente']) . "</span></td>";
                                        echo "<td>" . htmlspecialchars($row['FechaPedido']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Tipo']) . "</td>";
                                        echo "<td>S/" . number_format($row['Total'], 2) . "</td>";

                                        // Asignar clases de estilo según el estado
                                        $estadoClase = '';
                                        switch (strtolower($row['Estado'])) {
                                            case 'pendiente':
                                                $estadoClase = 'badge-pendiente';
                                                break;
                                            case 'entregado':
                                                $estadoClase = 'badge-entregado';
                                                break;
                                            case 'cancelado':
                                                $estadoClase = 'badge-cancelado';
                                                break;
                                        }

                                        echo "<td><span class='badge $estadoClase'>" . ucfirst($row['Estado']) . "</span></td>";
                                        echo "<td><div class='d-flex justify-content-center gap-2'>";
                                        if (strtolower($row['Estado']) !== 'entregado') {
                                            echo "<button type='button' class='btn btn-danger btn-sm btn-action-delete' data-id='" . htmlspecialchars($row['PedidoID']) . "' title='Eliminar'>
                                                    <i class='bx bx-trash'></i>
                                                </button>";
                                        }
                                        // Botón para ver detalle
                                        echo "<button type='button' class='btn btn-info btn-sm btn-action btn-ver-detalle' data-id='" . htmlspecialchars($row['PedidoID']) . "' title='Ver Detalle'>
                                                <i class='bx bx-list-ul'></i>
                                            </button>";
                                        echo "</div></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No se encontraron pedidos</td></tr>";
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
                            <!-- Modal Confirmar Eliminación -->
                            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <form method="POST" id="deletePedidoForm">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteLabel">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                        ¿Seguro que deseas eliminar este pedido?
                                        <input type="hidden" name="delete_pedido_id" id="deletePedidoId">
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            <script>
                            document.querySelectorAll('.btn-action-delete').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    var id = this.getAttribute('data-id');
                                    document.getElementById('deletePedidoId').value = id;
                                    var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                                    modal.show();
                                });
                            });
                            document.getElementById('deletePedidoForm').addEventListener('submit', function(e) {
                                e.preventDefault();
                                var id = document.getElementById('deletePedidoId').value;
                                fetch('Gestion-Pedidos.php', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                    body: 'delete_pedido_id=' + encodeURIComponent(id)
                                })
                                .then(res => res.text())
                                .then(() => {
                                    // Cerrar el modal
                                    var modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                                    modal.hide();
                                    // Recargar la página para actualizar la tabla y los totales
                                    location.reload();
                                });
                            });
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
                        <!-- Mensaje de error -->
                        </div>
                    </div>
                </div>
                   
			</div>
		</div>
        <!--end page wrapper -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar(".app-container")
	</script>
</body>

</html>