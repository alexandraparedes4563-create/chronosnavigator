<?php
// Incluir configuración de la base de datos
require_once 'db_config.php';

// Verificar que se haya enviado el formulario por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo = trim($_POST['titulo']);
    $fecha_inicio = $_POST['fecha_inicio']; 
    $id_categoria = (int)$_POST['id_categoria'];
    $descripcion_corta = trim($_POST['descripcion_corta']);
    $descripcion_larga = trim($_POST['descripcion_larga']);
    $ruta_imagen = null; 

    // Sentencia SQL para la inserción con placeholders (?)
    $sql = "INSERT INTO eventos (titulo, fecha_inicio, id_categoria, descripcion_corta, descripcion_larga, ruta_imagen) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        
        // Vincular los parámetros ('ssisss')
        $stmt->bind_param("ssisss", $param_titulo, $param_fecha, $param_categoria, $param_corta, $param_larga, $param_imagen);
        
        // Asignar valores
        $param_titulo = $titulo;
        $param_fecha = $fecha_inicio;
        $param_categoria = $id_categoria;
        $param_corta = $descripcion_corta;
        $param_larga = $descripcion_larga;
        $param_imagen = $ruta_imagen;
        
        if ($stmt->execute()) {
            // Éxito
            header("location: admin_panel.php?status=success");
            exit();
        } else {
            // Error en la ejecución
            header("location: admin_panel.php?status=error");
            exit();
        }

        $stmt->close();
    } else {
        // Error en la preparación de la sentencia
        error_log("Error al preparar la sentencia: " . $conn->error);
        header("location: admin_panel.php?status=error");
        exit();
    }

    $conn->close();
} else {
    header("location: admin_panel.php");
    exit();
}
?>