<?php
include_once '../../../config/database.php';
// --- AJAX para guardar dirección ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_guardar_direccion'])) {
    $ciudad = $_POST['ciudad'] ?? '';
    $calle = $_POST['calle'] ?? null;
    $referencia = $_POST['referencia'] ?? null;

    $stmt = $conn->prepare("INSERT INTO direccion (CIUDAD, Calle, Referencia) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ciudad, $calle, $referencia);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// Paso 1: Guardar venta y mostrar formulario de detalles
$ventaCreada = false;
$idVenta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_venta'])) {
    $fecha_pedido = $_POST['fecha_pedido'];
    $fecha_envio = $_POST['fecha_envio'];
    $tipo = $_POST['tipo'];
    $id_usuario = $_POST['id_usuario'];
    $id_comprobante = $_POST['id_comprobante'];
    $ruc = isset($_POST['ruc']) && $_POST['ruc'] !== '' ? $_POST['ruc'] : null;
    $id_metodo_pago = $_POST['id_metodo_pago'];
    $id_direccion = isset($_POST['id_direccion']) && $_POST['id_direccion'] !== '' ? $_POST['id_direccion'] : null;
    $estado = 'Pendiente';

    $stmt = $conn->prepare("INSERT INTO ventas 
    (Fecha_Pedido, Fecha_Envio, Tipo, Id_Usuario, Id_Comprobante, Estado, RUC, Id_Metodo_Pago, Id_Direccion) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssisssii", $fecha_pedido, $fecha_envio, $tipo, $id_usuario, 
        $id_comprobante, $estado, $ruc, $id_metodo_pago, $id_direccion);
    $stmt->execute();
    $idVenta = $conn->insert_id;
    $ventaCreada = true;
    $stmt->close();
}

// Paso 2: Guardar detalles de venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_detalles'])) {
    $idVenta = intval($_POST['id_venta']);
    $total = 0;
    foreach ($_POST['producto'] as $i => $id_producto) {
        $cantidad = intval($_POST['cantidad'][$i]);
        $precio_unit = floatval($_POST['precio_unit'][$i]);
        $subtotal = floatval($_POST['subtotal'][$i]);
        $total += $subtotal;
        $stmt = $conn->prepare("INSERT INTO detalle_venta (Id_Venta, Id_Producto, Cantidad, Costo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $idVenta, $id_producto, $cantidad, $subtotal);
        $stmt->execute();
        $stmt->close();
    }
    // Actualiza el total en la venta
    $stmt = $conn->prepare("UPDATE ventas SET Costo_total=? WHERE Id=?");
    $stmt->bind_param("di", $total, $idVenta);
    $stmt->execute();
    $stmt->close();
    header("Location: Gestion-Pedidos.php");
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

	<title>Administrador - Asignar Pedido</title>
</head>

<body>
	<div class="wrapper">
        <?php include_once '../../../config/sidebar.php'; ?>
    </div>
		<header>
            <?php include_once '../../../config/nav.php'; ?>
		</header>
	
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
    </style>

<!--start page wrapper -->
<div class="page-wrapper">
  <div class="page-content">
      <!-- Breadcrum -->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Comercio Electrónico</div>
          <div class="ps-3">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-0 p-0">
                      
                      <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                      <li class="breadcrumb-item active" aria-current="page">Asignar Pedidos</li>
                  </ol>
              </nav>
          </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="container mt-5">
            <h1>Registrar Venta</h1>
            <?php if (!$ventaCreada): ?>
            <!-- Paso 1: Formulario de venta -->
            <form method="POST" id="formVenta">
              <div class="row mb-3">
                <div class="col-md-3">
                  <label>Fecha Pedido</label>
                  <input type="date" name="fecha_pedido" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-3">
                  <label>Fecha Pedido</label>
                  <input type="date" name="fecha_envio" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-3">
                  <label>Tipo</label>
                  <select name="tipo" class="form-select" required>
                    <option value="Envio">Envio</option>
                    <option value="Recoger">Recoger</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label>Tipo de Comprobante</label>
                  <select name="id_comprobante" id="comprobanteSelect" class="form-select" required>
                    <option value="">Seleccione</option>
                    <?php
                    $comprobantes = $conn->query("SELECT Id, Nombre FROM comprobante");
                    while ($c = $comprobantes->fetch_assoc()) {
                      echo "<option value='{$c['Id']}' data-nombre='{$c['Nombre']}'>{$c['Nombre']}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="row mb-3" id="rucRow" style="display:none;">
                <div class="col-md-4">
                  <label>RUC</label>
                  <input type="text" name="ruc" id="rucInput" class="form-control">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-3">
                  <label>Usuario</label>
                  <select name="id_usuario" class="form-select" required>
                    <option value="">Seleccione</option>
                    <?php
                    $usuarios = $conn->query("SELECT Id, Nombre FROM usuario");
                    while ($u = $usuarios->fetch_assoc()) {
                      echo "<option value='{$u['Id']}'>{$u['Nombre']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label>Método de Pago</label>
                  <select name="id_metodo_pago" class="form-select" required>
                    <option value="">Seleccione</option>
                    <?php
                    $metodos = $conn->query("SELECT Id, Nombre FROM metodo_pago");
                    while ($m = $metodos->fetch_assoc()) {
                      echo "<option value='{$m['Id']}'>{$m['Nombre']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-3" id="direccionCol" style="display:none;">
                  <label>Dirección de Envío</label><br>
                  <button type="button" class="btn btn-secondary" id="btnDireccion" data-bs-toggle="modal" data-bs-target="#modalDireccion">
                    Agregar Dirección
                  </button>
                  <input type="hidden" name="id_direccion" id="idDireccionInput">
                  <span id="iconoGuardado" style="display:none; color:green; font-size:22px; vertical-align:middle;">
                    <i class="bx bx-check-circle"></i>
                  </span>
                </div>
              </div>
              <button type="submit" name="crear_venta" class="btn btn-primary">Crear Venta</button>
            </form>
            <?php else: ?>
            <!-- Paso 2: Formulario de detalles de venta -->
            <form method="POST" id="formDetalles">
              <input type="hidden" name="id_venta" value="<?php echo $idVenta; ?>">
              <table class="table table-bordered" id="tablaDetalles">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Sub Total</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <select name="producto[]" class="form-select producto-select" required>
                        <option value="">Seleccione</option>
                        <?php
                        $productos = $conn->query("SELECT Id, Nombre, Costo_unit FROM productos WHERE Estado='Abastecido'");
                        while ($p = $productos->fetch_assoc()) {
                          echo "<option value='{$p['Id']}' data-precio='{$p['Costo_unit']}'>{$p['Nombre']}</option>";
                        }
                        ?>
                      </select>
                    </td>
                    <td><input type="number" name="cantidad[]" class="form-control cantidad-input" min="1" value="1" required></td>
                    <td><input type="text" name="precio_unit[]" class="form-control precio-unit-input" readonly></td>
                    <td><input type="text" name="subtotal[]" class="form-control subtotal-input" readonly></td>
                    <td>
                      <button type="button" class="btn btn-success btn-add-row"><i class="bx bx-plus"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="text-end mb-3">
                <strong>Total (S/):</strong> <span id="totalVenta">0.00</span>
              </div>
              <button type="submit" name="guardar_detalles" class="btn btn-primary">Guardar Detalles y Finalizar</button>
            </form>
            <?php endif; ?>
            <!-- Modal Dirección -->
            <div class="modal fade" id="modalDireccion" tabindex="-1" aria-labelledby="modalDireccionLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form id="formDireccion" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalDireccionLabel">Agregar Dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label>Ciudad</label>
                      <input type="text" name="ciudad" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label>Calle</label>
                      <input type="text" name="calle" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label>Referencia</label>
                      <textarea name="referencia" class="form-control" rows="2"></textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
    <style>
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


            .btn-action-details, .btn-action-update {
                display: flex;
                justify-content: center;
                align-items: center;
                color: #f2f2f2;
                padding: 10px;
                border-radius: 20%;
                width: 35px;
                height: 35px;
                border: none;
            }

            .btn-action-details .bx, .btn-action-update .bx{
                font-weight: 400 !important;
                font-size: 18px;
            }

            .btn-action-details:hover, .btn-action-update:hover {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }


            .btn-action-details {
                background-color: #17a2b8;
            }

            .btn-action-details:hover {
                background-color: #138496;
            }

            .btn-action-update {
                background-color: #29b466;
            }

            .btn-action-update:hover {
                background-color: #30955c;
            }
    </style>
<script>
// Mostrar/ocultar campo Dirección según el campo "Tipo"
document.querySelector('select[name="tipo"]').addEventListener('change', function() {
  if (this.value === 'Envio') {
    document.getElementById('direccionCol').style.display = '';
  } else {
    document.getElementById('direccionCol').style.display = 'none';
    document.getElementById('idDireccionInput').value = '';
    document.getElementById('iconoGuardado').style.display = 'none';
  }
});
// Mostrar el campo si ya está seleccionado "Envio" al cargar la página
if (document.querySelector('select[name="tipo"]').value === 'Envio') {
  document.getElementById('direccionCol').style.display = '';
}

// AJAX para guardar dirección (POST a la misma página)
document.getElementById('formDireccion').addEventListener('submit', function(e) {
  e.preventDefault();
  var form = this;
  var datos = new FormData(form);
  datos.append('ajax_guardar_direccion', 1); // importante para que el PHP lo detecte
  fetch('', {
    method: 'POST',
    body: datos
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      document.getElementById('idDireccionInput').value = data.id;
      document.getElementById('iconoGuardado').style.display = '';
      var modal = bootstrap.Modal.getInstance(document.getElementById('modalDireccion'));
      modal.hide();
      form.reset();
    } else {
      alert('Error al guardar dirección');
    }
  })
  .catch(() => alert('Error de conexión'));
});
// Mostrar/ocultar campo RUC según el comprobante seleccionado
document.getElementById('comprobanteSelect').addEventListener('change', function() {
  var selected = this.options[this.selectedIndex].getAttribute('data-nombre');
  if (selected && selected.toLowerCase() === 'factura') {
    document.getElementById('rucRow').style.display = '';
    document.getElementById('rucInput').required = true;
  } else {
    document.getElementById('rucRow').style.display = 'none';
    document.getElementById('rucInput').required = false;
  }
});
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
    <script>
    // Función para actualizar subtotales y total
    function actualizarTotales() {
      let total = 0;
      document.querySelectorAll('#tablaDetalles tbody tr').forEach(row => {
        const cantidad = parseInt(row.querySelector('.cantidad-input').value) || 0;
        const precioUnit = parseFloat(row.querySelector('.precio-unit-input').value) || 0;
        const subtotal = cantidad * precioUnit;
        row.querySelector('.subtotal-input').value = subtotal.toFixed(2);
        total += subtotal;
      });
      document.getElementById('totalVenta').innerText = total.toFixed(2);
    }

    // Evento para agregar nueva fila
    document.querySelector('#tablaDetalles').addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-add-row') || e.target.parentElement.classList.contains('btn-add-row')) {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>
            <select name="producto[]" class="form-select producto-select" required>
              <option value="">Seleccione</option>
              <?php
              $productos = $conn->query("SELECT Id, Nombre, Costo_unit FROM productos WHERE Estado='Abastecido'");
              while ($p = $productos->fetch_assoc()) {
                echo "<option value='{$p['Id']}' data-precio='{$p['Costo_unit']}'>{$p['Nombre']}</option>";
              }
              ?>
            </select>
          </td>
          <td><input type="number" name="cantidad[]" class="form-control cantidad-input" min="1" value="1" required></td>
          <td><input type="text" name="precio_unit[]" class="form-control precio-unit-input" readonly></td>
          <td><input type="text" name="subtotal[]" class="form-control subtotal-input" readonly></td>
          <td>
            <button type="button" class="btn btn-danger btn-remove-row"><i class="bx bx-minus"></i></button>
          </td>
        `;
        this.querySelector('tbody').appendChild(newRow);
      }
      // Evento para eliminar fila
      if (e.target.classList.contains('btn-remove-row') || e.target.parentElement.classList.contains('btn-remove-row')) {
        const row = e.target.closest('tr');
        row.parentNode.removeChild(row);
        actualizarTotales();
      }
    });
    // Evento para actualizar precio unitario y subtotales al cambiar producto o cantidad
    document.querySelector('#tablaDetalles').addEventListener('change', function(e) {
      if (e.target.classList.contains('producto-select')) {
        const precio = e.target.options[e.target.selectedIndex].getAttribute('data-precio') || 0;
        const row = e.target.closest('tr');
        row.querySelector('.precio-unit-input').value = parseFloat(precio).toFixed(2);
        actualizarTotales();
      }
      if (e.target.classList.contains('cantidad-input')) {
        actualizarTotales();
      }
    });
    // Validar y enviar formulario de detalles
    document.getElementById('formDetalles').addEventListener('submit', function(e)
    {
      const filas = document.querySelectorAll('#tablaDetalles tbody tr');
      if (filas.length === 0) {
        e.preventDefault();
        alert('Agregue al menos un producto.');
        return;
      }
      for (const fila of filas) {
        const producto = fila.querySelector('.producto-select').value;
        const cantidad = fila.querySelector('.cantidad-input').value;
        if (!producto || !cantidad || cantidad <= 0) {
          e.preventDefault();
          alert('Complete todos los campos correctamente.');
          return;
        }
      }
    });
    // Advertencia y redirección si se abandona el formulario de detalles de venta
  document.addEventListener('DOMContentLoaded', function() {
    const formDetalles = document.getElementById('formDetalles');
    if (!formDetalles) return;

    let submitOk = false;

    // Advertencia al intentar salir o recargar
    window.addEventListener('beforeunload', function(e) {
      if (!submitOk) {
        e.preventDefault();
        e.returnValue = '¿Estás seguro de que deseas salir? Si recargas o sales, perderás el detalle de la venta actual.';
        return e.returnValue;
      }
    });

    // Si realmente recarga, redirige a crear venta
    window.addEventListener('unload', function() {
      if (!submitOk) {
        // Redirige solo si no se está enviando el formulario
        window.location.href = 'Gestion-Pedidos.php';
      }
    });

    // Si el usuario envía el formulario, no mostrar advertencia ni redirigir
    formDetalles.addEventListener('submit', function() {
      submitOk = true;
    });
  });
    </script>
</body>

</html>