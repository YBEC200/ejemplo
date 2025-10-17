<?php
// Conexión a la base de datos
include_once '../../../config/database.php';

// Consulta para obtener el primer usuario (Carlos) junto con su rol
$sqljeje = "SELECT 
    usuario.Id, 
    usuario.Nombre, 
    usuario.Apellido_Paterno, 
    usuario.Apellido_Materno, 
    usuario.Correo, 
    usuario.Telefono, 
    rol.Cargo AS Rol 
FROM usuario 
INNER JOIN rol ON usuario.Id_Rol = rol.Id 
WHERE usuario.Id = 1 LIMIT 1";

$result = $conn->query($sqljeje);

// Verificar si se encontró un usuario
if ($result->num_rows > 0) {
    $admin2 = $result->fetch_assoc(); // Obtener los datos del usuario
} else {
    $admin2 = null; // No se encontró un usuario
}

// Manejo del formulario para actualizar los datos del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formType']) && $_POST['formType'] === 'updateAdmin') {
    // Obtener los datos enviados desde el formulario
    $nombre = $_POST['nombre'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($apellido_paterno) && !empty($apellido_materno) && !empty($correo)) {
        // Actualizar los datos del usuario
        $stmt = $conn->prepare("UPDATE usuario SET Nombre = ?, Apellido_Paterno = ?, Apellido_Materno = ?, Correo = ?, Telefono = ? WHERE Id = 1");
        $stmt->bind_param("sssss", $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono);

        if ($stmt->execute()) {
            // Redirigir después de actualizar
            header("Location: Perfil-Usuario.php");
            exit;
        } else {
            echo "<script>alert('Ocurrió un error al actualizar los datos.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Por favor, complete todos los campos.');</script>";
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

	<title>Administrador</title>
</head>

<body>
	<div class="wrapper">
		<?php include_once '../../../config/sidebar.php'; ?>
	</div>
		<header>
			<?php include_once '../../../config/nav.php'; ?>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Perfil de Usuario</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($admin['Correo'] ?? ''); ?></li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">
							<div class="col-lg-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex flex-column align-items-center text-center">
											<img src="assets/images/avatars/avatar-1.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
											<div class="mt-3">
												<h4><?php echo htmlspecialchars($admin2['Nombre'] ?? ''); ?></h4>
												<p class="text-secondary mb-1"><?php echo htmlspecialchars($admin2['Rol'] ?? ''); ?></p>
												<p class="text-muted font-size-sm"><?php echo htmlspecialchars($admin2['Telefono'] ?? ''); ?></p>
											</div>
										</div>
										<ul class="list-group list-group-flush">
											<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
												<h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Pag.Web</h6>
												<span class="text-secondary">www.cd_technology.com</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
												<h6 class="mb-0"><svg version="1.0" xmlns="http://www.w3.org/2000/svg"
													width="22.5" height="22.5" viewBox="0 0 512 512"
													preserveAspectRatio="xMidYMid meet" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook me-2 icon-inline text-primary">
												   <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
												   fill="#000000" stroke="none">
												   <path d="M236 4355 c-33 -13 -68 -32 -78 -42 -17 -18 23 -57 1188 -1143 663
												   -619 1211 -1125 1218 -1125 13 0 2376 2273 2376 2286 0 4 -25 17 -55 28 l-56
												   21 -2267 0 -2267 0 -59 -25z"/>
												   <path d="M4340 3283 c-426 -411 -775 -750 -775 -754 0 -10 1545 -1452 1551
												   -1447 2 3 3 667 2 1476 l-3 1472 -775 -747z"/>
												   <path d="M1 2509 c1 -800 4 -1465 8 -1477 5 -21 84 51 776 717 424 408 771
												   745 773 750 2 5 -318 307 -710 672 -392 365 -743 692 -780 728 l-68 64 1
												   -1454z"/>
												   <path d="M2989 1979 c-178 -171 -338 -318 -356 -325 -41 -18 -95 -18 -136 0
												   -18 8 -177 150 -355 316 l-323 301 -33 -28 c-19 -15 -372 -354 -785 -753 -516
												   -498 -747 -728 -740 -735 7 -7 739 -10 2302 -10 2271 0 2292 0 2334 20 l43 20
												   -804 750 c-442 412 -808 751 -814 753 -5 1 -155 -137 -333 -309z"/>
												   </g>
												   </svg>Correo Laboral</h6>
												<span class="text-secondary"><?php echo htmlspecialchars($admin2['Correo'] ?? ''); ?></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
												<h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook me-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
												<span class="text-secondary">www.facebook.com/CD-TECH</span>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="card">
									<div class="card-body">
										<form action="" method="POST">
											<input type="hidden" name="formType" value="updateAdmin">
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Nombre</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($admin2['Nombre'] ?? ''); ?>" required />
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Apellido Paterno</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input type="text" class="form-control" name="apellido_paterno" value="<?php echo htmlspecialchars($admin2['Apellido_Paterno'] ?? ''); ?>" required />
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Apellido Materno</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input type="text" class="form-control" name="apellido_materno" value="<?php echo htmlspecialchars($admin2['Apellido_Materno'] ?? ''); ?>" required />
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Correo Laboral</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input type="email" class="form-control" name="correo" value="<?php echo htmlspecialchars($admin2['Correo'] ?? ''); ?>" required />
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Teléfono</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($admin2['Telefono'] ?? ''); ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3"></div>
												<div class="col-sm-9 text-secondary">
													<button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	</div>
	<!--end wrapper-->
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>

</html>