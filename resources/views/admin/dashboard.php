<?php
include_once '../../../config/database.php';
// Total Orders (total de ventas)
$res = $conn->query("SELECT COUNT(*) as total FROM VENTAS");
$total_orders = $res->fetch_assoc()['total'] ?? 0;

// Total de Mantenimientos
$res = $conn->query("SELECT COUNT(*) as total FROM MANTENIMIENTO");
$total_mantenimientos = $res->fetch_assoc()['total'] ?? 0;

// Ganancias del mes actual
$res = $conn->query("SELECT SUM(Costo_total) as total FROM VENTAS WHERE MONTH(Fecha_Registro) = MONTH(CURDATE()) AND YEAR(Fecha_Registro) = YEAR(CURDATE())");
$ganancias_mes = $res->fetch_assoc()['total'] ?? 0;

// Total Customers (usuarios con rol 'User')
$res = $conn->query("SELECT COUNT(*) as total FROM USUARIO WHERE Id_Rol IN (SELECT Id FROM ROL WHERE Rol='User')");
$total_customers = $res->fetch_assoc()['total'] ?? 0;

// 1. Resumen de Ventas x Mes
$ventas_labels = [];
$ventas_data = [];
$res = $conn->query("SELECT DATE_FORMAT(Fecha_Registro, '%Y-%m') as mes, COUNT(*) as total FROM VENTAS GROUP BY mes ORDER BY mes");
while($row = $res->fetch_assoc()) {
    $ventas_labels[] = $row['mes'];
    $ventas_data[] = (int)$row['total'];
}

// 2. Mantenimientos por Tipo
$mantenimientos_labels = ['Preventivo', 'Correctivo', 'Predictivo'];
$mantenimientos_data = [0, 0, 0];
$res = $conn->query("SELECT Tipo, COUNT(*) as total FROM MANTENIMIENTO GROUP BY Tipo");
while($row = $res->fetch_assoc()) {
    $idx = array_search($row['Tipo'], $mantenimientos_labels);
    if ($idx !== false) $mantenimientos_data[$idx] = (int)$row['total'];
}

// 3. Cantidad de productos según la categoria
$productos_categoria_labels = [];
$productos_categoria_data = [];
$res = $conn->query("SELECT CATEGORIA.Nombre as categoria, COUNT(PRODUCTOS.Id) as total FROM PRODUCTOS INNER JOIN CATEGORIA ON PRODUCTOS.Id_Categoria = CATEGORIA.Id GROUP BY categoria");
while($row = $res->fetch_assoc()) {
    $productos_categoria_labels[] = $row['categoria'];
    $productos_categoria_data[] = (int)$row['total'];
}

// 4. Métodos de Pago por Venta
$metodos_pago_labels = [];
$metodos_pago_data = [];
$res = $conn->query("SELECT METODO_PAGO.Nombre, COUNT(VENTAS.Id) as total FROM VENTAS INNER JOIN METODO_PAGO ON VENTAS.Id_Metodo_Pago = METODO_PAGO.Id GROUP BY METODO_PAGO.Nombre");
while($row = $res->fetch_assoc()) {
    $metodos_pago_labels[] = $row['Nombre'];
    $metodos_pago_data[] = (int)$row['total'];
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

	<title>Administrador - Dashboard</title>
</head>

<body>
	<div class="wrapper">
      <?php include_once '../../../config/sidebar.php'; ?>
	</div>
		<header>
			<?php include_once '../../../config/nav.php'; ?>
		</header>
        <div class="page-wrapper">
            <div class="page-content">
                <!-- Tarjetas resumen -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 row-cols-xxl-4">
                    <div class="col">
                        <div class="card radius-10 bg-gradient-cosmic">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-auto">
                                        <p class="mb-0 text-white">Total de ventas</p>
                                        <h4 class="my-1 text-white"><?php echo $total_orders; ?></h4>
                                        <p class="mb-0 font-13 text-white"><!-- REFERENCIAL --></p>
                                    </div>
                                    <div>
                                        <i class='bx bx-cart text-white' style="font-size:2rem"></i>
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
                                        <p class="mb-0 text-white">Total de Mantenimientos</p>
                                        <h4 class="my-1 text-white"><?php echo $total_mantenimientos; ?></h4>
                                        <p class="mb-0 font-13 text-white"><!-- REFERENCIAL --></p>
                                    </div>
                                    <div>
                                        <i class='bx bx-dollar text-white' style="font-size:2rem"></i>
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
                                        <p class="mb-0 text-white">Ganancias del mes</p>
                                        <h4 class="my-1 text-white">$<?php echo number_format($ganancias_mes,2);?></h4>
                                        <p class="mb-0 font-13 text-white"><!-- REFERENCIAL --></p>
                                    </div>
                                    <div>
                                        <i class='bx bx-dollar text-white' style="font-size:2rem"></i>
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
                                        <p class="mb-0 text-dark">Total Clientes Registrados</p>
                                        <h4 class="my-1 text-dark"><?php echo $total_customers; ?></h4>
                                        <p class="mb-0 font-13 text-dark"><!-- REFERENCIAL --></p>
                                    </div>
                                    <div>
                                        <i class='bx bx-user text-dark' style="font-size:2rem"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Gráficos principales -->
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card radius-10">
                            <div class="card-header">
                                <h6 class="mb-0">Resumen de Ventas x Mes</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-container-9">
                                    <canvas id="ventasChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card radius-10">
                            <div class="card-header">
                                <h6 class="mb-0">Mantenimientos por Tipo</h6>
                            </div>
                            <div class="card-body d-flex align-items-center">
                                <div class="chart-container-9" style="flex:1;">
                                    <canvas id="mantenimientosChart"></canvas>
                                </div>
                                <div id="mantenimientosLegend" style="flex:1; margin-left:20px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card radius-10">
                            <div class="card-header">
                                <h6 class="mb-0">Cantidad de productos por categoria</h6>
                            </div>
                            <div class="card-body d-flex align-items-center">
                                <div class="chart-container-9" style="flex:1;">
                                    <canvas id="productosCategoriaChart"></canvas>
                                </div>
                                <div id="productosCategoriaLegend" style="flex:1; margin-left:20px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card radius-10">
                            <div class="card-header">
                                <h6 class="mb-0">Métodos de Pago por Venta</h6>
                            </div>
                            <div class="card-body d-flex align-items-center">
                                <div class="chart-container-9" style="flex:1;">
                                    <canvas id="metodosPagoChart"></canvas>
                                </div>
                                <div id="metodosPagoLegend" style="flex:1; margin-left:20px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end page-content -->
        </div><!-- end page-wrapper -->
    </div><!-- end wrapper -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Resumen de Ventas x Mes
        new Chart(document.getElementById('ventasChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($ventas_labels); ?>,
                datasets: [{
                    label: 'Ventas',
                    data: <?php echo json_encode($ventas_data); ?>,
                    backgroundColor: '#36a2e0'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { title: { display: true, text: 'Mes' } },
                    y: { title: { display: true, text: 'Cantidad de Ventas' }, beginAtZero: true }
                }
            }
        });       
    </script>

    <script>
        function createLegend(chart, legendId) {
            const data = chart.data;
            const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
            let html = '<ul style="list-style:none;padding-left:0">';
            data.labels.forEach((label, i) => {
                const value = data.datasets[0].data[i];
                const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                const color = data.datasets[0].backgroundColor[i];
                html += `<li style="margin-bottom:8px;display:flex;align-items:center;">
                    <span style="display:inline-block;width:16px;height:16px;background:${color};border-radius:3px;margin-right:8px;"></span>
                    <span>${label}: <b>${value}</b> <span style="color:#888">(${percent}%)</span></span>
                </li>`;
            });
            html += '</ul>';
            document.getElementById(legendId).innerHTML = html;
        }

        // 2. Mantenimientos por Tipo
        const mantenimientosChart = new Chart(document.getElementById('mantenimientosChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($mantenimientos_labels); ?>,
                datasets: [{
                    label: 'Mantenimientos',
                    data: <?php echo json_encode($mantenimientos_data); ?>,
                    backgroundColor: ['#36a2e0', '#ff6384', '#ffcd56']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
        createLegend(mantenimientosChart, 'mantenimientosLegend');

        // 3. Cantidad de productos según la categoria
        const productosCategoriaChart = new Chart(document.getElementById('productosCategoriaChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($productos_categoria_labels); ?>,
                datasets: [{
                    label: 'Productos',
                    data: <?php echo json_encode($productos_categoria_data); ?>,
                    backgroundColor: ['#36a2e0', '#4bc0c0', '#9966ff', '#ff6384', '#ffcd56']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
        createLegend(productosCategoriaChart, 'productosCategoriaLegend');

        // 4. Métodos de Pago por Venta
        const metodosPagoChart = new Chart(document.getElementById('metodosPagoChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($metodos_pago_labels); ?>,
                datasets: [{
                    label: 'Métodos de Pago',
                    data: <?php echo json_encode($metodos_pago_data); ?>,
                    backgroundColor: ['#36a2e0', '#ffcd56', '#ff6384', '#4bc0c0']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
        createLegend(metodosPagoChart, 'metodosPagoLegend');
        </script>
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
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