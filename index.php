<?php
if (!$_REQUEST) {
    return ['validacion' => false, 'msg' => "Sin parámetros"];
}

try {
    $path = 'logs';

    if (!is_dir($path)) {
        // El directorio no existe, así que lo creamos
        if (mkdir($path, 0777, true)) {
            echo "Directorio creado exitosamente.";
        } else {
            echo "Error al crear el directorio.";
        }
    } else {
        echo "El directorio ya existe.";
    }
    
    $dateLog = date("Y-m-d__H-i-s");

    $logFile = fopen("logs/log-$dateLog.log", 'a') or die("Error creando archivo");

    $httpReferer = isset($_SERVER['HTTP_REFERER']) ?
        $_SERVER['HTTP_REFERER'] : (isset($_SERVER['HTTP_X_APP_ORIGIN']) ?
            $_SERVER['HTTP_X_APP_ORIGIN'] : (isset($_SERVER['HTTP_AUTHORIZATION']) ?
                $_SERVER['HTTP_AUTHORIZATION'] : $_SERVER['REMOTE_ADDR']));

    $tipo = gettype($_REQUEST);

    $metodo = $_SERVER["REQUEST_METHOD"];

    // Guarda de donde proviene
    fwrite($logFile, date("Y/m/d H:i:s") . " Viene desede = $httpReferer \n") or die("Error escribiendo en el archivo");

    // Guarda de donde proviene
    fwrite($logFile, date("Y/m/d H:i:s") . " Utilizó = $metodo \n") or die("Error escribiendo en el archivo");

    // Guarda el registro completo
    fwrite($logFile, date("Y/m/d H:i:s") . " Tipo de datos = $tipo \n") or die("Error escribiendo en el archivo");

    // Guarda cada uno de los campos incluidos
    foreach ($_REQUEST as $nombre_campo => $valor) {
        $asignacion = $nombre_campo . " = '" . trim($valor) . "'";
        fwrite($logFile, date("Y/m/d H:i:s") . " $asignacion \n") or die("Error escribiendo en el archivo");
    }

    fclose($logFile);
    return ['validacion' => true, 'msg' => "Datos recibidos"];
} catch (Exception $e) {
    return ['validacion' => false, 'msg' => "Error al crear log \n $e"];
}
