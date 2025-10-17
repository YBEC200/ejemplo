<!doctype html>
<html lang="es">

<head>
	<!-- Meta etiquetas requeridas -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Favicon -->
	<link rel="icon" href="assets/images/CDTECH.png" type="image/png"/>
	<!-- Plugins -->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- Loader -->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>Inicio de sesión - Administrador Botica San Antonio</title>
	<!-- CSS para fondo borroso -->
	<style>
		/* Fondo borroso */
		.background {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-image: url('assets/images/bg-themes/4.png');
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			filter: blur(0px); 
			z-index: 1;
		}

		.wrapper {
			position: relative;
			z-index: 2;
			overflow-y: auto; /* Permite el desplazamiento si el contenido es más grande que la altura */
			overflow-x: hidden !important;
		}

		body {
			margin: 0;
			padding: 0;
			height: 100vh;
		}
	</style>
</head>

<body class="">
	<!-- Fondo borroso -->
	<div class="background"></div>

	<!-- Wrapper -->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card mb-0">
							<div class="card-body">
								<div class="p-1">
									<!-- Logo de Botica San Antonio -->
									<div class="mb-2 text-center">
										<img src="assets/images/cdtech.png" width="90" alt="Logo Botica San Antonio" />
									</div>
									<div class="text-center mb-4">
										<h5 class="">Administrador</h5>
										<p class="mb-0">Por favor, inicie sesión en su cuenta</p>
									</div>
									<div class="form-body">
										<form class="row g-3">
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Correo Electrónico</label>
												<input type="email" class="form-control" id="inputEmailAddress" placeholder="example@gmail.com" required>
											<div id="email-error" style="color: red; font-size: 0.9em; display: none;"></div>
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Contraseña</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="******" required>
													<span class="input-group-text" style="cursor:pointer;">
														<i class="bx bx-hide"></i>
													</span>
												</div>
												<div id="password-error" style="color: red; font-size: 0.9em; display: none;"></div>
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
													<label class="form-check-label" for="flexSwitchCheckChecked">Recuérdame</label>
												</div>
											</div>
											<!--<div class="col-md-6 text-end">
												<a href="../resources/views/admin/Recuperar-Contraseña.html" class="link-style">¿Olvidaste tu contraseña?</a>
											</div>-->
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary">Iniciar sesión</button>
												</div>
												<div id="error-message" style="color: red; display: none; margin-top: 10px; text-align: center;"></div> <!-- Mensaje de error -->
											</div>											
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Fin de la fila -->
			</div>
		</div>
	</div>
	<!-- Fin del wrapper -->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!-- Plugins -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!-- Mostrar y ocultar contraseña -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password .input-group-text").on('click', function () {
				var input = $('#show_hide_password input');
				var icon = $('#show_hide_password i');
				if (input.attr("type") == "text") {
					input.attr('type', 'password');
					icon.removeClass("bx-show").addClass("bx-hide");
				} else {
					input.attr('type', 'text');
					icon.removeClass("bx-hide").addClass("bx-show");
				}
			});
		});
	</script>
	<!-- App JS -->
	<script src="assets/js/app.js"></script>
	<!-- Validar usuario -->
	<script>
		$(document).ready(function () {
			function validarEmail(email) {
				// Expresión regular simple para validar email
				var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				return re.test(email);
			}

			function validarFormulario() {
				var correo = $("#inputEmailAddress").val().trim();
				var contrasena = $("#inputChoosePassword").val().trim();
				var emailError = $("#email-error");
				var passwordError = $("#password-error");
				var errorMessage = $("#error-message");
				var valido = true;

				// Limpiar mensajes previos
				emailError.hide().text('');
				passwordError.hide().text('');
				errorMessage.hide().text('');

				// Validar correo vacío
				if (correo === "") {
					emailError.text("Por favor, ingresa tu correo electrónico.").show();
					valido = false;
				} else if (!validarEmail(correo)) {
					emailError.text("Ingresa un correo electrónico válido.").show();
					valido = false;
				}

				// Validar contraseña vacía
				if (contrasena === "") {
					passwordError.text("Por favor, ingresa tu contraseña.").show();
					valido = false;
				}

				if (!valido) return;

				// Validar usuario y contraseña correctos
				if (correo === "admin@example.com" && contrasena === "123") {
					window.location.href = '../resources/views/admin/dashboard.php';
				} else {
					errorMessage.text("Correo electrónico y/o contraseña incorrectos.").show();
				}
			}

			$("button[type='submit']").click(function(event) {
				event.preventDefault();
				validarFormulario();
			});
		});
	</script>
</body>

</html>