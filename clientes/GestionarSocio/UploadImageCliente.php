<?php
// Asegúrate de que esta ruta sea correcta para tu conexión.
include_once '../../conexion/conexion.php'; 
header("Content-Type: application/json");

try {
    // Leer datos del formulario enviado como FormData
    $id_usuario = $_POST['id_usuario'] ?? null;
    $archivo_subido = $_FILES['nueva_foto'] ?? null; // 'nueva_foto' es el name del input file

    // 1. Validar datos
    if (empty($id_usuario)) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado un ID de usuario."]);
        exit;
    }

    if (empty($archivo_subido) || $archivo_subido['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => "No se ha proporcionado una imagen válida para subir."]);
        exit;
    }

    // 2. Definir rutas y sanitizar
    $id_usuario = filter_var($id_usuario, FILTER_SANITIZE_NUMBER_INT);
    
    $directorio_destino_FISICO = '../../img/ImgUser/';
    // RUTA ESTABLE para la Base de Datos y el Frontend (Relativa a la carpeta 'clientes/')
    $url_para_db_y_frontend = '/Fortaleza_Cross/img/ImgUser/';

    // ... (Sanitización y verificación de existencia del directorio) ...
    if (!is_dir($directorio_destino_FISICO)) {
         // Intentar crear el directorio si no existe
        if (!mkdir($directorio_destino_FISICO, 0777, true)) {
            throw new Exception("No se pudo crear el directorio de destino.");
        }
    }

    // 3. Generar nuevo nombre de archivo
    $extension = pathinfo($archivo_subido['name'], PATHINFO_EXTENSION);
    $nuevo_nombre = $id_usuario . '_' . time() . '.' . $extension;
    $ruta_almacenamiento_servidor = $directorio_destino_FISICO . $nuevo_nombre;

    if (!move_uploaded_file($archivo_subido['tmp_name'], $ruta_almacenamiento_servidor)) {
        // Si falla, el problema es SÓLO de PERMISOS en la carpeta ImgUser o que la ruta es diferente.
        throw new Exception("Error al mover el archivo. Problemas de permisos o ruta incorrecta: " . $ruta_almacenamiento_servidor);
    }

    // 5. Definir la URL a guardar en la BD y devolver al Frontend
    $url_final_bd = $url_para_db_y_frontend . $nuevo_nombre;

    // 6. Actualizar la base de datos (Transacción - La lógica es correcta)
    $pdo->beginTransaction();

    //Antes de Acutalizar la nueva imagen, obtener la antigua para eliminarla
    // 6.a. OBTENER LA RUTA ANTIGUA DE LA BD ANTES DE ACTUALIZAR
    $sql_select = "SELECT foto FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt_select->execute();
    $resultado = $stmt_select->fetch(PDO::FETCH_ASSOC);
    $foto_antigua_url = $resultado['foto'] ?? '';

    // 6.b. ACTUALIZAR LA BD CON LA NUEVA FOTO
    $sql_update = "UPDATE usuarios SET foto = :nueva_foto WHERE id_usuario = :id_usuario";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':nueva_foto', $url_final_bd, PDO::PARAM_STR); 
    $stmt_update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $UpdateValido = $stmt_update->execute();

    if (!$UpdateValido) {
        $pdo->rollBack();
        // Si falla la BD, eliminamos la imagen recién subida para limpiar
        if (file_exists($ruta_almacenamiento_servidor)) { unlink($ruta_almacenamiento_servidor); } 
        throw new Exception("Error de BD al actualizar la imagen.");
    }

    // 7 ELIMINAR LA IMAGEN ANTIGUA DEL SERVIDOR (Solo si la BD se actualizó con éxito)
    // La ruta de la BD es ABSOLUTA: /Fortaleza_Cross/img/ImgUser/vieja.png
    // Necesitamos convertirla a la ruta FÍSICA para PHP: ../../img/ImgUser/vieja.png
    
    // 7.a Quitar el prefijo web: /Fortaleza_Cross/
    $nombre_archivo_relativo = str_replace('/Fortaleza_Cross/', '', $foto_antigua_url); 
    
    // 7.b Determinar la ruta física completa
    $ruta_fisica_antigua = '../../' . $nombre_archivo_relativo;

    // Prevenir la eliminación de la imagen por defecto o si la ruta estaba vacía
    if (!empty($foto_antigua_url) && 
        !strpos($foto_antigua_url, 'user_default.png') && 
        file_exists($ruta_fisica_antigua)) 
    {
        // Intentar eliminar el archivo
        if (!unlink($ruta_fisica_antigua)) {
            // No hacemos rollback si falla la eliminación (es menos crítico que la BD)
            error_log("Advertencia: No se pudo eliminar la imagen antigua: " . $ruta_fisica_antigua);
        }
    }

    // 8. Si todo está bien, hacemos commit
    $pdo->commit();

    // 9. Devolver la URL ESTABLE
    echo json_encode([
        "success" => true, 
        "message" => "Foto de perfil actualizada correctamente.", 
        "newImageUrl" => $url_final_bd // Ej: /Fortaleza_Cross/img/ImgUser/33_1632000000.jpg
    ]);
    exit;

} catch (PDOException $e) {
    // Manejar errores de PDO
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error de PDO en Upload: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error de base de datos."]);
    exit;
} catch (Exception $e) {
    error_log("Error general en Upload: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error al procesar la solicitud."]);
    exit;
} catch (Error $e) {
    error_log("Error fatal en Upload: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error de servidor interno."]);
    exit;
}
?>