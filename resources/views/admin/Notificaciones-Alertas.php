<?php
// Conexión a la base de datos
include_once '../../../config/database.php';

// Consulta para obtener los productos con estado "Agotado"
$sqlProductosAgotados = "SELECT Id, Nombre, Imagen_Principal, Estado FROM productos WHERE Estado = 'Agotado'";
$resultAgotados = $conn->query($sqlProductosAgotados);

// Consulta para obtener productos con lotes menores a 50
$sqlLotesMenores = "
    SELECT p.Id, p.Nombre, p.Imagen_Principal, SUM(l.Cantidad) AS TotalCantidad
    FROM productos p
    INNER JOIN lote l ON p.Id = l.Id_Producto
    GROUP BY p.Id
    HAVING TotalCantidad < 50
";
$resultLotesMenores = $conn->query($sqlLotesMenores);
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
    .alert-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .alert-image.riesgo-alto {
        border: 3px solid #ff0000; /* Borde rojo para riesgo alto */
    }

    .alert-image.riesgo-medio {
        border: 3px solid #ffa500; /* Borde naranja para riesgo medio */
    }

    .alert-image img {
        border-radius: 50%;
        width: 100%;
        height: 100%;
        object-fit: cover;
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
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Notificaciones y alertas</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card radius-10">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Notificaciones y alertas</h6>
                        </div>
                    </div>
                </div>
				
                <div class="card-body">
                    <!-- Alertas de productos agotados -->
                    <?php if ($resultAgotados->num_rows > 0): ?>
						<?php while ($producto = $resultAgotados->fetch_assoc()): ?>
							<div class="alert alert-danger d-flex align-items-center" role="alert">
								<div class="alert-image riesgo-alto me-3">
									<?php if (!empty($producto['Imagen_Principal'])): ?>
										<img src="data:image/jpeg;base64,<?php echo base64_encode($producto['Imagen_Principal']); ?>" alt="<?php echo htmlspecialchars($producto['Nombre']); ?>">
									<?php else: ?>
										<img src="assets/images/default.png" alt="Imagen predeterminada">
									<?php endif; ?>
								</div>
								<div>
									<h5 class="alert-heading"><?php echo htmlspecialchars($producto['Nombre']); ?></h5>
									<p><strong>Estado:</strong> <?php echo htmlspecialchars($producto['Estado']); ?></p>
									<p><strong>Riesgo:</strong> Alto</p>
									<p class="mb-0">Este producto está agotado. Es crucial reabastecerlo lo antes posible para evitar interrupciones en el inventario y posibles pérdidas de ventas.</p>
									<hr>
									<p class="mb-0"><strong>Recomendacion:</strong></p>
								</div>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>

                    <!-- Alertas de lotes menores a 50 -->
                    <?php if ($resultLotesMenores->num_rows > 0): ?>
						<?php while ($producto = $resultLotesMenores->fetch_assoc()): ?>
							<div class="alert alert-warning d-flex align-items-center" role="alert">
								<div class="alert-image riesgo-medio me-3">
									<?php if (!empty($producto['Imagen_Principal'])): ?>
										<img src="data:image/jpeg;base64,<?php echo base64_encode($producto['Imagen_Principal']); ?>" alt="<?php echo htmlspecialchars($producto['Nombre']); ?>">
									<?php else: ?>
										<img src="assets/images/default.png" alt="Imagen predeterminada">
									<?php endif; ?>
								</div>
								<div>
									<h5 class="alert-heading"><?php echo htmlspecialchars($producto['Nombre']); ?></h5>
									<p><strong>Estado:</strong> Quedan pocos lotes</p>
									<p><strong>Riesgo:</strong> Medio</p>
									<p class="mb-0">La cantidad total de lotes de este producto es menor a 50. Considere reabastecer los lotes para evitar que se agoten.</p>
									<hr>
									<p class="mb-0"><strong>Recomendacion:</strong></p>
								</div>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
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
	<script src="assets/js/index.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar(".app-container")
	</script>
</body>
</html>