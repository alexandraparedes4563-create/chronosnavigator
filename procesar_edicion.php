<?php
require_once 'db_config.php';

// Verificar que se haya enviado el formulario por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = (int)$_POST['id'];
    $titulo = trim($_POST['titulo']);
    $fecha_inicio = $_POST['fecha_inicio']; 
    $id_categoria = (int)$_POST['id_categoria'];
    $descripcion_corta = trim($_POST['descripcion_corta']);
    $descripcion_larga = trim($_POST['descripcion_larga']);

    // Sentencia SQL para la actualización (UPDATE)
    $sql = "UPDATE eventos SET 
                titulo = ?, 
                fecha_inicio = ?, 
                id_categoria = ?, 
                descripcion_corta = ?, 
                descripcion_larga = ?
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        
        // Vincular los parámetros: (ssissi)
        $stmt->bind_param("ssissi", $param_titulo, $param_fecha, $param_categoria, $param_corta, $param_larga, $param_id);
        
        // Asignar valores
        $param_titulo = $titulo;
        $param_fecha = $fecha_inicio;
        $param_categoria = $id_categoria;
        $param_corta = $descripcion_corta;
        $param_larga = $descripcion_larga;
        $param_id = $id; // El ID del evento a actualizar
        
        if ($stmt->execute()) {
            // Éxito
            header("location: admin_panel.php?status=updated");
            exit();
        } else {
            // Error
            header("location: admin_panel.php?status=error");
            exit();
        }

        $stmt->close();
    } else {
        // Error en la preparación de la sentencia
        header("location: admin_panel.php?status=error");
        exit();
    }

    $conn->close();
} else {
    header("location: admin_panel.php");
    exit();
}
?>