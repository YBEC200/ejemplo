<?php
// Conexión a la base de datos
include_once '../../../config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['formType'] ?? '';

    // Crear nueva categoría
    if ($formType === "category") {
        $name = $_POST['categoryName'];
        $description = $_POST['categoryDescription'];
        $sql = "INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            echo "<script>window.onload = () => { document.querySelector('#successModal .modal-body').innerText = '¡Categoría creada correctamente!'; new bootstrap.Modal(document.getElementById('successModal')).show(); }</script>";
        } else {
            echo "<script>window.onload = () => { document.querySelector('#errorModal .modal-body').innerText = 'Error al crear la categoría.'; new bootstrap.Modal(document.getElementById('errorModal')).show(); }</script>";
        }
    }

    // Editar categoría
    if ($formType === "editCategory") {
        $id = $_POST['categoryId'];
        $name = $_POST['categoryName'];
        $description = $_POST['categoryDescription'];

        $sql = "UPDATE categoria SET nombre=?, descripcion=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $description, $id);
        if ($stmt->execute()) {
            echo "<script>window.onload = () => { document.querySelector('#successModal .modal-body').innerText = '¡Categoría actualizada correctamente!'; new bootstrap.Modal(document.getElementById('successModal')).show(); }</script>";
        } else {
            echo "<script>window.onload = () => { document.querySelector('#errorModal .modal-body').innerText = 'Error al actualizar la categoría.'; new bootstrap.Modal(document.getElementById('errorModal')).show(); }</script>";
        }
    }

    // Eliminar categoría con validación
    if ($formType === "deleteCategory") {
        $id = $_POST['categoryId'];
        $check = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE Id_Categoria = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();

        if ($result['total'] > 0) {
            echo "<script>
                window.onload = () => {
                    document.querySelector('#errorModal .modal-body').innerText = '❌ No se puede eliminar la categoría porque está vinculada a productos.';
                    new bootstrap.Modal(document.getElementById('errorModal')).show();
                }
            </script>";
        } else {
            $sql = "DELETE FROM categoria WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    echo "<script>window.onload = () => { document.querySelector('#successModal .modal-body').innerText = '¡Categoría eliminada correctamente!'; new bootstrap.Modal(document.getElementById('successModal')).show(); }</script>";
                } else {
                    echo "<script>window.onload = () => { document.querySelector('#errorModal .modal-body').innerText = 'Error al eliminar la categoría.'; new bootstrap.Modal(document.getElementById('errorModal')).show(); }</script>";
                }
            }
        }
    }

    // Crear lote
    if ($formType === 'createLote') {
        $loteName = $_POST['loteName'];
        $productoId = $_POST['productoId'];
        $cantidad = $_POST['loteCantidad'];
        $fecha = $_POST['loteFecha'];

        $stmt = $conn->prepare("INSERT INTO lote (Lote, Id_Producto, Cantidad, Fecha_Registro) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $loteName, $productoId, $cantidad, $fecha);
        if ($stmt->execute()) {
            echo "<script>window.onload = () => { document.querySelector('#successModal .modal-body').innerText = '¡Lote creado correctamente!'; new bootstrap.Modal(document.getElementById('successModal')).show(); }</script>";
        } else {
            echo "<script>window.onload = () => { document.querySelector('#errorModal .modal-body').innerText = 'Error al crear el lote.'; new bootstrap.Modal(document.getElementById('errorModal')).show(); }</script>";
        }
        $stmt->close();
    }

    // Editar lote
    if ($formType === 'updateLote') {
        $id = $_POST['loteId'];
        $loteName = $_POST['loteName'];
        $productoId = $_POST['productoId'];
        $cantidad = $_POST['loteCantidad'];
        $fecha = $_POST['loteFecha'];

        $stmt = $conn->prepare("UPDATE lote SET Lote=?, Id_Producto=?, Cantidad=?, Fecha_Registro=? WHERE Id=?");
        $stmt->bind_param("siisi", $loteName, $productoId, $cantidad, $fecha, $id);
        if ($stmt->execute()) {
            echo "<script>window.onload = () => { document.querySelector('#successModal .modal-body').innerText = '¡Lote actualizado correctamente!'; new bootstrap.Modal(document.getElementById('successModal')).show(); }</script>";
        } else {
            echo "<script>window.onload = () => { document.querySelector('#errorModal .modal-body').innerText = 'Error al actualizar el lote.'; new bootstrap.Modal(document.getElementById('errorModal')).show(); }</script>";
        }
        $stmt->close();
    }

    // Eliminar lote
    if ($formType === 'deleteLote') {
        $id = $_POST['loteId'];
        $stmt = $conn->prepare("DELETE FROM lote WHERE Id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>window.onload = () => { document.querySelector('#successModal .modal-body').innerText = '¡Lote eliminado correctamente!'; new bootstrap.Modal(document.getElementById('successModal')).show(); }</script>";
        } else {
            echo "<script>window.onload = () => { document.querySelector('#errorModal .modal-body').innerText = 'Error al eliminar el lote.'; new bootstrap.Modal(document.getElementById('errorModal')).show(); }</script>";
        }
        $stmt->close();
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

	<title>Administrador - Agregar Lotes y Categorias</title>
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
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Agregar Categoria o Lote</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
								<li class="breadcrumb-item active" aria-current="page">Gestión de Categorías y Lotes</li>
							</ol>
						</nav>
					</div>
				</div>
		
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between mb-4 flex-wrap">
							<!-- Sección Categorías -->
							<div class="flex-fill me-3 mb-3">
								<div class="d-flex justify-content-between mb-3">
									<div>
										<label for="categorySearch" class="fw-bold">Buscar Categoría</label>
										<input type="search" class="form-control" id="categorySearch" placeholder="Buscar categoría">
									</div>
									<button class="btn btn-primary" id="addCategoryBtn" data-bs-toggle="modal" data-bs-target="#categoryModal">Agregar Nueva Categoría</button>
									<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
									<!-- Modal crear categoria -->
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="categoryModalLabel">Agregar Nueva Categoría</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
											<form id="categoryForm" action="" method="POST">
												<input type="hidden" name="formType" value="category">
												<div class="mb-3">
													<label for="categoryName" class="form-label">Nombre de la Categoría</label>
													<input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Ingrese el nombre de la categoría" required>
												</div>
												<div class="mb-3">
													<label for="categoryDescription" class="form-label">Descripción</label>
													<textarea class="form-control" id="categoryDescription" name="categoryDescription" rows="3" placeholder="Ingrese una descripción" required></textarea>
												</div>
												<button type="submit" class="btn btn-primary">Guardar</button>
											</form>
											</div>
										</div>
									</div>
									</div>
								</div>
								<div class="table-responsive mb-3">
									<table class="table mb-0">
										<thead class="table-light">
											<tr>
												<th>ID</th>
												<th>Nombre</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tbody id="categoryTable">
										<?php
											// Consulta para obtener las categorías
											$sql = "SELECT id, nombre, descripcion FROM categoria";
											$result = $conn->query($sql);

											// Verificar si hay resultados
											if ($result->num_rows > 0) {
												// Recorrer los resultados y generar las filas de la tabla
												while ($row = $result->fetch_assoc()) {
													echo "<tr class='category-row'>";
													echo "<td>" . htmlspecialchars($row['id']) . "</td>";
													echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
													echo "<td>
															<button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editCategoryModal' onclick='populateEditCategoryModal(" . htmlspecialchars($row['id']) . ", \"" . htmlspecialchars($row['nombre']) . "\", \"" . htmlspecialchars($row['descripcion']) . "\")'>Editar</button>
															<button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteCategoryModal' onclick='setDeleteCategoryId(" . htmlspecialchars($row['id']) . ")'>Eliminar</button>
														</td>";
													echo "</tr>";
												}
											} else {
												// Mostrar un mensaje si no hay categorías
												echo "<tr><td colspan='4' class='text-center'>No se encontraron categorías</td></tr>";
											}
											?>
										</tbody>
									</table>
									<!-- Modal para Confirmar Eliminación de Categoría -->
									<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="deleteCategoryModalLabel">Eliminar Categoría</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<p>¿Estás seguro de que deseas eliminar esta categoría?</p>
													<form id="deleteCategoryForm" action="" method="POST">
														<input type="hidden" name="formType" value="deleteCategory">
														<input type="hidden" id="deleteCategoryId" name="categoryId">
														<button type="submit" class="btn btn-danger">Eliminar</button>
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
													</form>
												</div>
											</div>
										</div>
									</div>
									<!-- Modal para Editar Categoría -->
									<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<form id="editCategoryForm" action="" method="POST">
														<input type="hidden" name="formType" value="editCategory">
														<input type="hidden" id="editCategoryId" name="categoryId">
														<div class="mb-3">
															<label for="editCategoryName" class="form-label">Nombre de la Categoría</label>
															<input type="text" class="form-control" id="editCategoryName" name="categoryName" required>
														</div>
														<div class="mb-3">
															<label for="editCategoryDescription" class="form-label">Descripción</label>
															<textarea class="form-control" id="editCategoryDescription" name="categoryDescription" rows="3" placeholder="Ingrese una descripción aquí..." required></textarea>
														</div>
														<button type="submit" class="btn btn-primary">Guardar Cambios</button>
													</form>
												</div>
											</div>
										</div>
									</div>
									<script>
									function populateEditCategoryModal(id, name, description) {
										document.getElementById('editCategoryId').value = id;
										document.getElementById('editCategoryName').value = name;
										document.getElementById('editCategoryDescription').value = description;
									}

									function setDeleteCategoryId(id) {
										document.getElementById('deleteCategoryId').value = id;
									}
										document.getElementById('categorySearch').addEventListener('input', function () {
											const filter = this.value.toLowerCase();
											const rows = document.querySelectorAll('.category-row');
											rows.forEach(row => {
												const categoryName = row.children[1].textContent.toLowerCase();
												if (categoryName.includes(filter)) {
													row.style.display = '';
												} else {
													row.style.display = 'none';
												}
											});
										});
									</script>
									<!-- Modal Éxito -->
									<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
											<div class="modal-header bg-success text-white">
												<h5 class="modal-title">✅ Éxito</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
											</div>
											<div class="modal-body">
												¡Se procesó correctamente!
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
												Hubo un error al procesar la acción.
											</div>
											</div>
										</div>
									</div>
								</div>
							</div>
		
							<div class="custom-divider"></div>		
							<!-- Sección Lotes -->
							<div class="flex-fill ms-3 mb-3">
								<div class="d-flex justify-content-between mb-3">
									<div>
										<label for="loteSearch" class="fw-bold">Buscar Lote</label>
										<input type="search" class="form-control" id="loteSearch" placeholder="Buscar lote o producto">
									</div>
									<script>
									// Filtrar lotes por nombre de lote o producto
									document.getElementById('loteSearch').addEventListener('input', function () {
										const filter = this.value.toLowerCase();
										const rows = document.querySelectorAll('#loteTable tr');
										rows.forEach(row => {
											// Nombre del lote está en la columna 2, producto en la columna 3
											const loteName = row.children[1]?.textContent.toLowerCase() || '';
											const productoName = row.children[2]?.textContent.toLowerCase() || '';
											if (loteName.includes(filter) || productoName.includes(filter)) {
												row.style.display = '';
											} else {
												row.style.display = 'none';
											}
										});
									});
									</script>
									<button class="btn btn-primary" id="addLoteBtn" data-bs-toggle="modal" data-bs-target="#loteModal">
										Agregar Nuevo Lote
									</button>	
									<!-- Modal crear lote -->
									<div class="modal fade" id="loteModal" tabindex="-1" aria-labelledby="loteModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="loteModalLabel">Registrar Nuevo Lote</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<form id="loteForm" action="" method="POST">
														<input type="hidden" name="formType" value="createLote">
														<div class="mb-3">
															<label for="loteName" class="form-label">Nombre del Lote</label>
															<input type="text" class="form-control" id="loteName" name="loteName" required>
														</div>
														<div class="mb-3">
															<label for="productoId" class="form-label">Producto</label>
															<select class="form-select" id="productoId" name="productoId" required>
																<option value="">Seleccione un producto</option>
																<?php
																$sqlProd = "SELECT Id, Nombre FROM productos ORDER BY Nombre ASC";
																$resultProd = $conn->query($sqlProd);
																if ($resultProd && $resultProd->num_rows > 0) {
																	while ($row = $resultProd->fetch_assoc()) {
																		echo "<option value='".$row['Id']."'>".$row['Nombre']."</option>";
																	}
																}
																?>
															</select>
														</div>
														<div class="mb-3">
															<label for="loteCantidad" class="form-label">Cantidad Inicial</label>
															<input type="number" class="form-control" id="loteCantidad" name="loteCantidad" min="0" required>
														</div>
														<div class="mb-3">
															<label for="loteFecha" class="form-label">Fecha de Registro</label>
															<input type="date" class="form-control" id="loteFecha" name="loteFecha" value="<?php echo date('Y-m-d'); ?>" required>
														</div>
														<button type="submit" class="btn btn-primary">Guardar</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive mb-3">
									<table class="table mb-0">
										<thead class="table-light">
											<tr>
												<th>ID</th>
												<th>Lote</th>
												<th>Producto</th>
												<th>Fecha Registro</th>
												<th>Cantidad</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tbody id="loteTable">
											<?php
											$sql = "SELECT lote.Id, lote.Lote, productos.Nombre AS Producto, lote.Fecha_Registro, lote.Cantidad, lote.Id_Producto
													FROM lote
													INNER JOIN productos ON lote.Id_Producto = productos.Id";
											$result = $conn->query($sql);

											if ($result && $result->num_rows > 0) {
												while ($row = $result->fetch_assoc()) {
													echo "<tr>";
													echo "<td>" . htmlspecialchars($row['Id']) . "</td>";
													echo "<td>" . htmlspecialchars($row['Lote']) . "</td>";
													echo "<td>" . htmlspecialchars($row['Producto']) . "</td>";
													echo "<td>" . htmlspecialchars($row['Fecha_Registro']) . "</td>";
													echo "<td>" . htmlspecialchars($row['Cantidad']) . "</td>";
													echo "<td>
															<button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editLoteModal'
																onclick='populateEditLoteModal("
																	. htmlspecialchars($row['Id']) . ", \"" 
																	. htmlspecialchars($row['Lote']) . "\", \"" 
																	. htmlspecialchars($row['Id_Producto']) . "\", \"" 
																	. htmlspecialchars($row['Cantidad']) . "\", \"" 
																	. htmlspecialchars($row['Fecha_Registro']) . "\")'>Editar</button>
															<button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteLoteModal'
																onclick='setDeleteLoteId(" . htmlspecialchars($row['Id']) . ")'>Eliminar</button>
														</td>";
													echo "</tr>";
												}
											} else {
												echo "<tr><td colspan='6' class='text-center'>No se encontraron lotes</td></tr>";
											}
											?>
										</tbody>
									</table>
									<!-- Modal Editar Lote -->
									<div class="modal fade" id="editLoteModal" tabindex="-1" aria-labelledby="editLoteModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<form method="POST" action="">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Actualizar Lote</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
													</div>
													<div class="modal-body">
														<input type="hidden" name="formType" value="updateLote">
														<input type="hidden" name="loteId" id="editLoteId">

														<div class="mb-3">
															<label class="form-label">Nombre del Lote</label>
															<input type="text" class="form-control" name="loteName" id="editLoteName" required>
														</div>

														<div class="mb-3">
															<label class="form-label">Producto</label>
															<select class="form-select" name="productoId" id="editProductoId" required>
																<option value="">Seleccione un producto</option>
																<?php
																	$sqlProd = "SELECT Id, Nombre FROM productos ORDER BY Nombre ASC";
																	$resultProd = $conn->query($sqlProd);
																	while ($prod = $resultProd->fetch_assoc()) {
																		echo "<option value='".$prod['Id']."'>".$prod['Nombre']."</option>";
																	}
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">Cantidad</label>
															<input type="number" class="form-control" name="loteCantidad" id="editLoteCantidad" min="0" required>
														</div>

														<div class="mb-3">
															<label class="form-label">Fecha de Registro</label>
															<input type="date" class="form-control" name="loteFecha" id="editLoteFecha" required>
														</div>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-primary">Guardar Cambios</button>
													</div>
												</div>
											</form>
										</div>
									</div>
									<!-- Modal Eliminar Lote -->
									<div class="modal fade" id="deleteLoteModal" tabindex="-1" aria-labelledby="deleteLoteModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<form id="deleteLoteForm" action="" method="POST">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="deleteLoteModalLabel">Eliminar Lote</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
													</div>
													<div class="modal-body">
														<input type="hidden" name="formType" value="deleteLote">
														<input type="hidden" id="deleteLoteId" name="loteId">
														<p>¿Estás seguro de que deseas eliminar este lote?</p>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-danger">Eliminar</button>
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
													</div>
												</div>
											</form>
										</div>
									</div>
									<script>
										function populateEditLoteModal(id, nombre, productoId, cantidad, fecha) {
											// Setea los valores en los campos del modal
											document.getElementById('editLoteId').value = id;
											document.getElementById('editLoteName').value = nombre;
											document.getElementById('editLoteCantidad').value = cantidad;
											document.getElementById('editLoteFecha').value = fecha;

											// Manejo especial para el select de productos
											let select = document.getElementById('editProductoId');
											for (let i = 0; i < select.options.length; i++) {
												if (select.options[i].value == productoId) {
													select.options[i].selected = true;
													break;
												}
											}
										}

										function setDeleteLoteId(id) {
											document.getElementById('deleteLoteId').value = id;
										}
									</script>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<style>
			.custom-divider {
				display: block !important;
				border-left: 1px solid #ddd !important;
				height: 100% !important;
				width: 0;
			}
			/* Estilo para que las tablas se apilen verticalmente en pantallas pequeñas */
			@media (max-width: 1250px) {
				.custom-divider {
					border-top: 2px solid #ddd;  /* Cambio a 1px en lugar de border-left */
					margin: 25px 0;
					width: 100%; /* Hacemos que la línea ocupe todo el ancho */
					height: 0;  /* Removemos la altura para evitar que sea visible verticalmente */
				}
				.page-wrapper .row {
					flex-direction: column;
					align-items: stretch;
				}
				
				.col-md-6 {
					width: 100%;
					margin-bottom: 20px; /* Añadir margen entre las secciones */
				}
			}

			/* Estilo para la línea divisoria entre Categorías y Presentaciones */
			/* Línea divisoria entre Categorías y Presentaciones */
			.page-wrapper .col-md-12 {
				border-top: 1px solid #ddd; /* Asegura que la línea sea horizontal */
				margin-top: 20px; /* Da un poco de espacio entre las tablas y la línea */
				margin-bottom: 20px; /* Espacio debajo de la línea */
			}

		</style>		
		<div class="overlay toggle-icon"></div>
	</div>
	<style>
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
	<!-- search modal -->
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