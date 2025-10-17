<?php
include_once '../../../config/database.php';
// Manejo de la actualización del producto
if (isset($_POST['update_product'])) {
    $id = intval($_POST['edit_id']);
    $nombre = $conn->real_escape_string($_POST['edit_nombre']);
    $marca = $conn->real_escape_string($_POST['edit_marca']);
    $precio = floatval($_POST['edit_precio']);
    $estado = $conn->real_escape_string($_POST['edit_estado']);
    $categoria = intval($_POST['edit_categoria']);

    $updateSql = "UPDATE PRODUCTOS 
                    SET Nombre='$nombre',
                        Marca='$marca',
                        Estado='$estado',
                        Id_Categoria=$categoria,
                        Costo_unit=$precio
                    WHERE Id=$id";

    if ($conn->query($updateSql)) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
            });
        </script>";
    } else {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();
            });
        </script>";
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

	<title>Administrador - Gestión Producto</title>
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
						
							.btn-export-excel {
								background-color: #28a745;
								color: white;
							}

							.btn-export-excel:hover {
								background-color: #277639;
							}
						
							.btn-export-pdf {
								background-color: #dc3545;
								color: white;
							}

							.btn-export-pdf:hover {
								background-color: #b12e3b;
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
							.btn-success, .btn-warning {
								background-color: #3ea556d3;
								border-color: #28a745;
								font-size: 12px; 
								color: #fff; 
								font-weight: 400;
								line-height: 10px;
							}
							.btn-warning {
								background-color: #ffd149;
								border-color: #ffc107;
							}
							.table th, .table td {
								text-align: center ;
								border: 1px solid #ddd; 
								padding: 9px 15px; 
							}

							.table tbody tr {
								border-bottom: 1px solid #ddd;
							}
							.table td, .table th {
								vertical-align: middle;
							}
							.table th {
								padding: 15px 5px;
							}
							.product-show {
								position: absolute;
								top: 50%;
								left: 5px;
								transform: translateY(-50%);
							}
							.table img {
								width: 70px !important;
								height: 70px !important;
								object-fit: cover;
							}
							.border-bottom {
								border-bottom: 1px solid #ddd;
							}
							.form-select {
								width: auto;
								margin-right: 10px;
							}

							.order-actions {
								display: flex;
								justify-content: center;
								align-items: center;
							}

							.order-actions .bxs-edit {
								color: #f2f2f2;
								font-weight: 500;
								
							}
							.order-actions .bxs-trash {
								color: #f2f2f2;
								font-weight: 500;
							}

						</style>
<body>
	<div class="wrapper">
		<?php include_once '../../../config/sidebar.php'; ?>
	</div>
		<header>
			<?php include_once '../../../config/nav.php'; ?>
		</header>

		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Productos</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
								<li class="breadcrumb-item active" aria-current="page">Gestión de productos</li>
							</ol>
						</nav>
					</div>
				</div>
			  
				<div class="card">
					<div class="card-body">
						<div class="d-lg-flex justify-content-between align-items-center mb-4 gap-3">
							<!-- Sección izquierda: Mostrar productos y búsqueda -->
							<div class="d-flex align-items-center gap-3 flex-grow-1 flex-wrap">
								<div class="position-relative flex-grow-1">
									<input type="search" class="form-control ps-5 radius-30" placeholder="Buscar producto por nombre" id="searchInput">
									<span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
								</div>
								<div class="d-flex align-items-center gap-2 flex-wrap">
									<input type="number" step="0.01" min="0" class="form-control w-auto" id="minPrice" placeholder="Precio mínimo (S/.)">
									<input type="number" step="0.01" min="0" class="form-control w-auto" id="maxPrice" placeholder="Precio máximo (S/.)">
								</div>
							</div>
							<!-- Sección derecha: Filtros -->
							<div class="d-flex align-items-center gap-2 flex-wrap justify-content-end flex-shrink-0">
								<label for="categoryFilter">Categoría:</label>
								<select class="form-select w-auto" id="categoryFilter" onchange="filterTable()">
									<option value="">Todas</option>
									<?php
									$categorySql = "SELECT DISTINCT nombre FROM categoria";
									$categoryResult = $conn->query($categorySql);
									while ($category = $categoryResult->fetch_assoc()) {
										echo "<option value='" . htmlspecialchars($category['nombre']) . "'>" . htmlspecialchars($category['nombre']) . "</option>";
									}
									?>
								</select>
								<label for="estadoFilter">Estado:</label>
								<select class="form-select w-auto" id="estadoFilter" onchange="filterTable()">
									<option value="">Todos</option>
									<option value="Abastecido">Abastecido</option>
									<option value="Agotado">Agotado</option>
								</select>
							</div>
							<div class="d-flex align-items-center gap-2 flex-wrap justify-content-end flex-shrink-0">
								<a href="Gestion-Productos.php" class="btn custom-btn radius-30 mt-2 mt-lg-0" 
									style="background-color: #17529bff; border-color: #227b89ff; color: white; padding: 10px 20px; border-radius: 30px; text-align: center; display: inline-flex; align-items: center; text-decoration: none;">
									<i class="bx bx-package" style="margin-right: 8px;"></i> Ir a crear producto
								</a>
							</div>
							<script>
								// Filtro por nombre de producto
								document.getElementById('searchInput').addEventListener('input', function () {
									const searchValue = this.value.toLowerCase();
									const rows = document.querySelectorAll('.product-row');
									rows.forEach(row => {
										const name = row.querySelector('.product-name').innerText.toLowerCase();
										if (name.includes(searchValue)) {
											row.style.display = '';
										} else {
											row.style.display = 'none';
										}
									});
								});
								// Filtro por categoría
								document.getElementById('categoryFilter').addEventListener('change', function () {
									const selectedValue = this.value.toLowerCase();
									const rows = document.querySelectorAll('.product-row');
									rows.forEach(row => {
										const category = row.querySelector('.product-category').innerText.toLowerCase();
										if (selectedValue === '' || category === selectedValue) {
											row.style.display = '';
										} else {
											row.style.display = 'none';
										}
									});
								});
								// Filtro por estado
								document.getElementById('estadoFilter').addEventListener('change', function () {
									const selectedValue = this.value.toLowerCase();
									const rows = document.querySelectorAll('.product-row');
									rows.forEach(row => {
										const estado = row.querySelector('.product-estado').innerText.toLowerCase();
										if (selectedValue === '' || estado === selectedValue) {
											row.style.display = '';
										} else {
											row.style.display = 'none';
										}
									});
								});
								// Filtro por precio
								document.getElementById('minPrice').addEventListener('input', filterByPrice);
								document.getElementById('maxPrice').addEventListener('input', filterByPrice);

								function filterByPrice() {
									const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
									const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
									const rows = document.querySelectorAll('.product-row');

									rows.forEach(row => {
									const priceText = row.querySelector('.product-precio').innerText
										.replace('S/', '')
										.replace(',', '')
										.trim();
									const price = parseFloat(priceText);

									if (price >= minPrice && price <= maxPrice) {
										row.style.display = '';
									} else {
										row.style.display = 'none';
									}
									});
								}
							</script>
						</div>	
						<!-- Tabla de productos -->
						<div class="table-responsive">
						<table class="table mb-0">
							<thead class="table-light">
								<tr>
									<th>ID</th>
									<th>Nombre</th>
									<th>Marca</th>
									<th>Precio Uni</th>
									<th>Estado</th>
									<th>Lotes</th>
									<th>Categoría</th>
									<th>Ultimo Abastecimiento</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody id="productTable">
								<?php
								$sql = "SELECT 
									PRODUCTOS.Id AS ID,
									PRODUCTOS.Nombre AS NOMBRE,
									PRODUCTOS.Costo_unit AS PRECIO,
									PRODUCTOS.Marca AS MARCA,
									CATEGORIA.Nombre AS CATEGORIA,
									PRODUCTOS.Estado AS ESTADO,
									(SELECT COUNT(*) FROM LOTE WHERE LOTE.Id_Producto = PRODUCTOS.Id) AS CANTIDAD_LOTES,
									(SELECT MAX(Fecha_Registro) FROM LOTE WHERE LOTE.Id_Producto = PRODUCTOS.Id) AS FECHA_REGISTRO
								FROM PRODUCTOS
								INNER JOIN CATEGORIA ON PRODUCTOS.Id_Categoria = CATEGORIA.Id
								ORDER BY PRODUCTOS.Id ASC";

								$result = $conn->query($sql);
								if ($result->num_rows > 0) {
									while ($row = $result->fetch_assoc()) {
										echo "<tr class='product-row'>";
										echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
										echo "<td class='product-name'>" . htmlspecialchars($row['NOMBRE']) . "</td>";
										echo "<td class='product-marca'>" . htmlspecialchars($row['MARCA']) . "</td>";
										echo "<td class='product-precio'>S/" . number_format($row['PRECIO'], 2) . "</td>";
										echo "<td class='product-estado'>" . htmlspecialchars($row['ESTADO']) . "</td>";
										echo "<td>" . htmlspecialchars($row['CANTIDAD_LOTES']) . "</td>";
										echo "<td class='product-category'>" . htmlspecialchars($row['CATEGORIA']) . "</td>";
										echo "<td class='product-fecha' data-fecha='" . ($row['FECHA_REGISTRO'] ? date("Y-m-d", strtotime($row['FECHA_REGISTRO'])) : "") . "'>" 
											. ($row['FECHA_REGISTRO'] ? date("d-m-Y", strtotime($row['FECHA_REGISTRO'])) : "-") . "</td>";
										echo "<td>
												<button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-id='" . htmlspecialchars($row['ID']) . "'>Editar</button>
											</td>";
										echo "</tr>";
									}
								} else {
									echo "<tr><td colspan='9' class='text-center'>No se encontraron productos</td></tr>";
								}
								?>
							</tbody>
						</table>
						<!-- Modal Editar Producto -->
						<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<form method="POST" action="Gestion-Productos.php">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="editModalLabel">Editar Producto</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
										</div>
										<div class="modal-body">
											<input type="hidden" name="edit_id" id="edit_id">

											<div class="mb-3">
												<label for="edit_nombre" class="form-label">Nombre</label>
												<input type="text" class="form-control" name="edit_nombre" id="edit_nombre" required>
											</div>

											<div class="mb-3">
												<label for="edit_precio" class="form-label">Precio Unitario</label>
												<input type="number" step="0.01" class="form-control" name="edit_precio" id="edit_precio" required>
											</div>

											<div class="mb-3">
												<label for="edit_estado" class="form-label">Estado</label>
												<select class="form-select" name="edit_estado" id="edit_estado" required>
													<option value="Abastecido">Abastecido</option>
													<option value="Agotado">Agotado</option>
												</select>
											</div>

											<div class="mb-3">
												<label for="edit_categoria" class="form-label">Categoría</label>
												<select class="form-select" name="edit_categoria" id="edit_categoria" required>
													<?php
													$catSql = "SELECT Id, Nombre FROM CATEGORIA";
													$catResult = $conn->query($catSql);
													while ($cat = $catResult->fetch_assoc()) {
														echo "<option value='" . $cat['Id'] . "'>" . htmlspecialchars($cat['Nombre']) . "</option>";
													}
													?>
												</select>
											</div>
											<div class="mb-3">
												<label for="edit_marca" class="form-label">Marca</label>
												<input type="text" class="form-control" name="edit_marca" id="edit_marca" required>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
											<button type="submit" name="update_product" class="btn btn-primary">Guardar cambios</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<!-- Modal Éxito -->
						<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
							<div class="modal-header bg-success text-white">
								<h5 class="modal-title">✅ Éxito</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
							</div>
							<div class="modal-body">
								¡El producto se actualizó correctamente!
							</div>
							</div>
						</div>
						</div>
						<!-- Modal Error -->
						<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
							<div class="modal-header bg-danger text-white">
								<h5 class="modal-title">❌ Error</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
							</div>
							<div class="modal-body">
								Hubo un error al actualizar el producto.
							</div>
							</div>
						</div>
						</div>
						<script>
						document.querySelectorAll('[data-bs-target="#editModal"]').forEach(btn => {
							btn.addEventListener('click', function () {
								const row = this.closest('tr');
								document.getElementById('edit_id').value = row.children[0].innerText;
								document.getElementById('edit_nombre').value = row.querySelector('.product-name').innerText;
								document.getElementById('edit_precio').value = row.querySelector('.product-precio').innerText.replace('S/', '').replace(',', '').trim();
								document.getElementById('edit_estado').value = row.querySelector('.product-estado').innerText;
								// Categoría (selecciona en el <select> la categoría actual)
								let categoria = row.querySelector('.product-category').innerText;
								let categoriaSelect = document.getElementById('edit_categoria');
								for (let opt of categoriaSelect.options) {
									if (opt.text === categoria) {
										categoriaSelect.value = opt.value;
										break;
									}
								}
								document.getElementById('edit_marca').value = row.querySelector('.product-marca').innerText;
							});
						});
						</script>
						<!-- Fin Modal Editar Producto -->
					</div>
				</div>
			</div>
		</div>
		<div class="overlay toggle-icon"></div>
	</div>
	
	<!-- Incluye la biblioteca XLSX -->
	<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.28"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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