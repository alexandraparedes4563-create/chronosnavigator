<?php
require_once 'db_config.php';

// Asegurarse de que el ID del evento a editar fue pasado por URL
if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    header("location: admin_panel.php");
    exit();
}

$id_evento = trim($_GET["id"]);

// 1. Obtener los datos del evento específico
$sql_evento = "SELECT titulo, fecha_inicio, id_categoria, descripcion_corta, descripcion_larga 
               FROM eventos WHERE id = ?";

if ($stmt_evento = $conn->prepare($sql_evento)) {
    $stmt_evento->bind_param("i", $param_id);
    $param_id = $id_evento;
    
    if ($stmt_evento->execute()) {
        $resultado = $stmt_evento->get_result();
        
        if ($resultado->num_rows == 1) {
            $evento = $resultado->fetch_assoc();
            $titulo = $evento['titulo'];
            $fecha_inicio = $evento['fecha_inicio'];
            $id_categoria_actual = $evento['id_categoria'];
            $descripcion_corta = $evento['descripcion_corta'];
            $descripcion_larga = $evento['descripcion_larga'];
        } else {
            header("location: admin_panel.php?status=error");
            exit();
        }
    } else {
        header("location: admin_panel.php?status=error");
        exit();
    }
    $stmt_evento->close();
}

// 2. Obtener todas las categorías para el select
$categorias_sql = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
$categorias_resultado = $conn->query($categorias_sql);

include 'includes/header.php';
?>

<h1 class="mb-4">Editar Hito Histórico (ID: <?php echo htmlspecialchars($id_evento); ?>)</h1>

<div class="card shadow">
    <div class="card-header bg-info text-white">
        Modificar Datos del Evento
    </div>
    <div class="card-body">
        <form action="procesar_edicion.php" method="POST">
            
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_evento); ?>">

            <div class="mb-3">
                <label for="titulo" class="form-label">Título del Hito:</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
            </div>

            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha del Hito:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría:</label>
                <select class="form-select" id="id_categoria" name="id_categoria" required>
                    <option value="">Seleccione una Categoría</option>
                    <?php
                    if ($categorias_resultado->num_rows > 0) {
                        while($cat = $categorias_resultado->fetch_assoc()) {
                            // Marca como 'selected' la categoría actual del evento
                            $selected = ($cat['id'] == $id_categoria_actual) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($cat['id']) . '" ' . $selected . '>' . htmlspecialchars($cat['nombre']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="descripcion_corta" class="form-label">Descripción Corta (Máx 500 Caracteres):</label>
                <textarea class="form-control" id="descripcion_corta" name="descripcion_corta" rows="2" maxlength="500" required><?php echo htmlspecialchars($descripcion_corta); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="descripcion_larga" class="form-label">Descripción Detallada:</label>
                <textarea class="form-control" id="descripcion_larga" name="descripcion_larga" rows="5" required><?php echo htmlspecialchars($descripcion_larga); ?></textarea>
            </div>

            <button type="submit" class="btn btn-info text-white">Actualizar Hito</button>
            <a href="admin_panel.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>