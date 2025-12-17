<?php
require_once 'db_config.php';

// Verificar que se haya pasado un ID por URL (GET)
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    
    $id = trim($_GET["id"]);

    // Sentencia SQL para eliminar
    $sql = "DELETE FROM eventos WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        
        $stmt->bind_param("i", $param_id);
        $param_id = $id;
        
        if ($stmt->execute()) {
            // Éxito
            header("location: admin_panel.php?status=deleted");
            exit();
        } else {
            // Error
            header("location: admin_panel.php?status=error");
            exit();
        }

        $stmt->close();
    } else {
        header("location: admin_panel.php?status=error");
        exit();
    }
    
    $conn->close();
} else {
    // Si no hay ID, redirigir
    header("location: admin_panel.php");
    exit();
}
?>