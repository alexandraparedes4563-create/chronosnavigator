<?php
require_once 'db_config.php';
include 'includes/header.php';

$sql = "SELECT e.titulo, e.descripcion_corta, e.fecha_inicio, c.nombre AS categoria, c.color 
        FROM eventos e
        JOIN categorias c ON e.id_categoria = c.id
        ORDER BY e.fecha_inicio ASC"; 

$resultado = $conn->query($sql);
?>

<h1 class="text-center mb-5">Mi Línea de Tiempo: The Chronos Navigator</h1>

<div class="timeline">
    <?php
    if ($resultado && $resultado->num_rows > 0) {
        $count = 0; 
        
        while($fila = $resultado->fetch_assoc()) {
            $count++;
            $category_color = htmlspecialchars($fila['color']);
            
            $badge_text_color = ($category_color == '#FFC107' || $category_color == '#FFFF00') ? '#1e1e1e' : '#FFFFFF';
            
            $is_odd = ($count % 2 != 0);
            
            // Borde para el Dark Mode moderno
            $side_style = $is_odd ? 'style="border-right: 5px solid ' . $category_color . ';"' : 'style="border-left: 5px solid ' . $category_color . ';"';

            echo '<div class="timeline-event ' . ($is_odd ? 'odd' : 'even') . '" ' . $side_style . '>';
            
            // Inyectar CSS para el círculo del hito
            echo '<style>.timeline-event:nth-child(' . $count . ')::after { background: ' . $category_color . ' !important; }</style>';
            
            echo '<div class="card shadow-sm">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($fila['titulo']) . '</h5>';
            
            echo '<h6 class="card-subtitle mb-2" style="color: #bbb;">' . date('d F Y', strtotime($fila['fecha_inicio'])) . ' - <span class="badge" style="background-color: ' . $category_color . '; color: ' . $badge_text_color . '; font-weight: 600;">' . htmlspecialchars($fila['categoria']) . '</span></h6>';
            
            echo '<p class="card-text">' . htmlspecialchars($fila['descripcion_corta']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p class="text-center">Aún no hay eventos registrados en la línea de tiempo. ¡Agrégalos desde la sección de administración!</p>';
    }
    ?>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>