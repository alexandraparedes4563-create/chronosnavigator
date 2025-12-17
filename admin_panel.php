<?php
require_once 'db_config.php';
include 'includes/header.php';

// Obtener todas las categorías para el select del formulario
$categorias_sql = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
$categorias_resultado = $conn->query($categorias_sql);

// Consulta para obtener TODOS los eventos para el listado de administración
$eventos_sql = "SELECT e.id, e.titulo, e.fecha_inicio, c.nombre AS categoria 
                FROM eventos e
                JOIN categorias c ON e.id_categoria = c.id
                ORDER BY e.fecha_inicio DESC"; // Ordenamos de más reciente a más antiguo
$eventos_resultado = $conn->query($eventos_sql);

// Variable para mensajes de éxito o error
$mensaje = '';
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status == 'success') {
        $mensaje = '<div class="alert alert-success">¡Evento añadido con éxito!</div>';
    } elseif ($status == 'error') {
        $mensaje = '<div class="alert alert-danger">Error al realizar la operación.</div>';
    } elseif ($status == 'updated') {
        $mensaje = '<div class="alert alert-success">¡Evento actualizado correctamente!</div>';
    } elseif ($status == 'deleted') {
        $mensaje = '<div class="alert alert-warning">¡Evento eliminado correctamente!</div>';
    }
}
?>

<h1 class="mb-4">Panel de Administración de Eventos</h1>

<?php echo $mensaje; ?>

<div class="card shadow mb-5">
    <div class="card-header bg-primary text-white">
        Añadir Nuevo Hito Histórico
    </div>
    <div class="card-body">
        <form action="insertar_evento.php" method="POST">
            
            <div class="mb-3">
                <label for="titulo" class="form-label">Título del Hito:</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>

            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha del Hito:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría:</label>
                <select class="form-select" id="id_categoria" name="id_categoria" required>
                    <option value="">Seleccione una Categoría</option>
                    <?php
                    if ($categorias_resultado->num_rows > 0) {
                        $categorias_resultado->data_seek(0); 
                        while($cat = $categorias_resultado->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($cat['id']) . '">' . htmlspecialchars($cat['nombre']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="descripcion_corta" class="form-label">Descripción Corta (Máx 500 Caracteres):</label>
                <textarea class="form-control" id="descripcion_corta" name="descripcion_corta" rows="2" maxlength="500" required></textarea>
            </div>

            <div class="mb-3">
                <label for="descripcion_larga" class="form-label">Descripción Detallada:</label>
                <textarea class="form-control" id="descripcion_larga" name="descripcion_larga" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Guardar Hito</button>
        </form>
    </div>
</div>

<h2 class="mt-5 mb-3">Eventos Existentes</h2>

<?php if ($eventos_resultado->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Categoría</th>
                    <th style="width: 180px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($evento = $eventos_resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evento['id']); ?></td>
                    <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($evento['fecha_inicio'])); ?></td>
                    <td><?php echo htmlspecialchars($evento['categoria']); ?></td>
                    <td>
                        <a href="editar_evento.php?id=<?php echo $evento['id']; ?>" class="btn btn-sm btn-info text-white me-2">Editar</a>
                        <a href="eliminar_evento.php?id=<?php echo $evento['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No hay eventos registrados aún.</div>
<?php endif; ?>

<?php
$conn->close();
include 'includes/footer.php';
?>