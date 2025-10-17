<?php
include_once '../../../config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guardar producto
    if (isset($_POST['guardar_producto'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $id_categoria = $_POST['id_categoria'];
        $marca = $_POST['marca'];
        $costo_unit = $_POST['costo_unit'];

        $stmt = $conn->prepare("INSERT INTO Productos (Nombre, Descripcion, Id_Categoria, Marca, Costo_unit) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisd", $nombre, $descripcion, $id_categoria, $marca, $costo_unit);
        if ($stmt->execute()) {
            header("Location: Agregar-Productos.php?imgsuccess=1");
            exit();
        } else {
            echo "<script>alert('Error al guardar el producto');</script>";
        }
        $stmt->close();
    }

    // Guardar imágenes
    if (isset($_POST['guardar_imagenes'])) {
        $id_producto = $_POST['id_producto'];

        // Imagen principal
        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] == 0) {
            $imgData = file_get_contents($_FILES['imagen_principal']['tmp_name']);
            $stmt = $conn->prepare("UPDATE productos SET Imagen_Principal=? WHERE Id=?");
            $stmt->bind_param("bi", $imgData, $id_producto);
            $stmt->send_long_data(0, $imgData);
            $stmt->execute();
            $stmt->close();
        }

        // Imagen secundaria
        if (isset($_FILES['imagen_secundaria']) && $_FILES['imagen_secundaria']['error'] == 0) {
            $imgDataSec = file_get_contents($_FILES['imagen_secundaria']['tmp_name']);
            $stmtSec = $conn->prepare("INSERT INTO producto_imagenes (Id_Producto, Imagen) VALUES (?, ?)");
            $stmtSec->bind_param("ib", $id_producto, $imgDataSec);
            $stmtSec->send_long_data(1, $imgDataSec);
            if ($stmtSec->execute()) {
                header("Location: Agregar-Productos.php?imgsuccess=1");
                exit();
            } else {
                echo "<script>alert('Error al guardar la imagen secundaria');</script>";
            }
            $stmtSec->close();
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

	<title>Administrador - Agregar Producto</title>
</head>

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
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Productos</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Agregar nuevo producto</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
			  
				<div class="card">
					<div class="card-body p-4">
						<div class="d-flex justify-content-between align-items-center">
							<h5 class="card-title mb-0">Agregar Nuevo Producto</h5>
							<a href="Gestion-Productos.php" class="btn custom-btn radius-30 mt-2 mt-lg-0" 
								style="background-color: #32acbe; border-color: #269cae; color: white; padding: 10px 20px; border-radius: 30px; text-align: center; display: inline-flex; align-items: center; text-decoration: none;">
								<i class="bx bx-file" style="margin-right: 8px;"></i> Ver la gestión de productos
							</a>
						  </div>
						  <hr />
						<div class="form-body mt-4">
							<div class="row">
								
								<!-- Columna Izquierda -->
								<div class="col-lg-7">
									<div class="border border-3 p-4 rounded">
										<form action="" method="POST">
											<div class="mb-3">
												<label for="inputProductTitle" class="form-label">Nombre del Producto</label>
												<input type="text" class="form-control" id="inputProductTitle" name="nombre" placeholder="Ingresa el nombre del producto" required>
											</div>
											<div class="mb-3">
												<label for="inputProductDescription" class="form-label">Descripción</label>
												<textarea class="form-control" id="inputProductDescription" name="descripcion" placeholder="Describe el producto"></textarea>
											</div>
											<div class="mb-3">
												<label for="inputPrice" class="form-label">Costo Unitario</label>
												<div class="input-group">
													<span class="input-group-text">S/</span>
													<input type="number" class="form-control" id="inputPrice" name="costo_unit" step="0.01" min="0.01" placeholder="00.00" required>
												</div>
											</div>
											<div class="mb-3">
												<label for="inputCategory" class="form-label">Categoría</label>
												<select class="form-select" id="inputCategory" name="id_categoria" required>
													<option value="">Seleccionar Categoría</option>
													<?php
													$sqlCategorias = "SELECT Id, Nombre FROM categoria";
													$resultCategorias = $conn->query($sqlCategorias);
													while ($row = $resultCategorias->fetch_assoc()) {
														echo "<option value='{$row['Id']}'>" . htmlspecialchars($row['Nombre']) . "</option>";
													}
													?>
												</select>
												<script>
												document.getElementById('inputPrice').addEventListener('input', function () {
													const input = this;
													if (input.value < 0.00) {
														input.value = ''; // Limpia el campo si el valor es negativo
													}
												});
											</script>
											</div>
											<div class="mb-3">
												<label for="inputBrand" class="form-label">Marca</label>
												<input type="text" class="form-control" id="inputBrand" name="marca" placeholder="Ingresa la marca del producto" required>
											</div>
											<button type="submit" name="guardar_producto" class="btn btn-primary w-100">Guardar Producto</button>
										</form>
									</div>
								</div>
								<!-- Formulario de Imagen -->
								<div class="col-lg-5">
									<div class="border border-3 p-4 rounded">
										<form action="" method="POST" enctype="multipart/form-data">
											<div class="mb-3">
												<label for="inputProductImage" class="form-label">Producto</label>
												<select class="form-select" id="inputProductImage" name="id_producto" required>
													<option value="">Seleccionar Producto</option>
													<?php
													$sqlProductos = "SELECT Id, Nombre FROM productos";
													$resultProductos = $conn->query($sqlProductos);
													while ($row = $resultProductos->fetch_assoc()) {
														echo "<option value='{$row['Id']}'>" . htmlspecialchars($row['Nombre']) . "</option>";
													}
													?>
												</select>
											</div>
											<div class="mb-3">
												<label for="imagen_principal" class="form-label">Imagen Principal</label>
												<input type="file" class="form-control" id="imagen_principal" name="imagen_principal" accept="image/*">
											</div>
											<div class="mb-3">
												<label for="imagen_secundaria" class="form-label">Imagen Secundaria</label>
												<input type="file" class="form-control" id="imagen_secundaria" name="imagen_secundaria" accept="image/*">
											</div>
											<button type="submit" name="guardar_imagenes" class="btn btn-primary w-100">Guardar Imágenes</button>
										</form>
										<br>
										<div class="container-fotos">
											<h5>Vista Previa de Imágenes</h5>
											<div>
												<div class="mb-3">
													<label class="form-label">Imagen Principal</label>
													<div id="vista-previa-imagen-principal" class="preview-container"></div>
												</div>
												<div class="mb-3">
													<label class="form-label">Imagen Secundaria</label>
													<div id="vista-previa-imagenes" class="preview-container"></div>
												</div>
											</div>										
										</div>
										<script>
										// Vista previa imagen principal
										document.getElementById('imagen_principal').addEventListener('change', function(e) {
											const preview = document.getElementById('vista-previa-imagen-principal');
											preview.innerHTML = '';
											const file = e.target.files[0];
											if (file) {
												const reader = new FileReader();
												reader.onload = function(ev) {
													const img = document.createElement('img');
													img.src = ev.target.result;
													img.className = 'imagen-vista-previa';
													preview.appendChild(img);
												}
												reader.readAsDataURL(file);
											}
										});

										// Vista previa imagen secundaria
										document.getElementById('imagen_secundaria').addEventListener('change', function(e) {
											const preview = document.getElementById('vista-previa-imagenes');
											preview.innerHTML = '';
											const file = e.target.files[0];
											if (file) {
												const reader = new FileReader();
												reader.onload = function(ev) {
													const img = document.createElement('img');
													img.src = ev.target.result;
													img.className = 'imagen-vista-previa';
													preview.appendChild(img);
												}
												reader.readAsDataURL(file);
											}
										});
										</script>
									</div>
								</div>
							</div> 
						</div>
					</div>
				</div>
				<script>
				</script>
				<!-- Estilos CSS -->	
				<style>
				.container-fotos {
					max-width: 700px;
					margin: auto;
					background: transparent;
					padding: 20px;
					border-radius: 10px;
					box-shadow: 0 4px 10px rgba(163, 163, 163, 0.9);
				}
				
				label {
					font-weight: bold;
					margin-bottom: 5px;
					display: block;
				}
				
				.file-input {
					display: block;
					width: 100%;
					padding: 10px;
					border: 2px dashed #a2cfff;
					border-radius: 8px;
					text-align: center;
					cursor: pointer;
					transition: 0.3s;
					background: transparent;
				}
				
				.file-input:hover {
					background: #e9f5ff;
				}
				
				.preview-container {
					margin-top: 10px;
					text-align: center;
				}
				
				.contenedor-imagen {
					position: relative;
					display: inline-block;
					margin: 5px;
				}
				
				.imagen-vista-previa {
					border-radius: 10px;
					object-fit: cover;
					transition: transform 0.3s;
					width: 120px;
					height: 120px;
				}
				
				
				
				.boton-eliminar {
					position: absolute;
					top: 2px;
					right: 1px;
					background: rgb(0, 0, 0);
					width: 30px;
					color: white;
					border: none;
					padding: 5px;
					font-size: 14px;
					border-radius: 50%;
					cursor: pointer;
					display: none;
				}
				
				.contenedor-imagen:hover .boton-eliminar {
					display: block;
				}
				
				#vista-previa-imagen-principal img {
					width: 150px;
					height: 150px;
					border: 2px solid #b4d8fe;
					padding: 5px;
				}
				
				#vista-previa-imagenes {
					display: flex;
					flex-wrap: wrap;
					gap: 10px;
					justify-content: center;
				}
				
				#vista-previa-imagenes img {
					width: 90px;
					height: 90px;
					border: 2px solid #89e9e6;
					padding: 3px;
				}
				</style>
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
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
	<script>
		$(document).ready(function () {
			$('#image-uploadify').imageuploadify();
		})
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>

</html>