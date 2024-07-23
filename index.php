<?php
// Verifica si no hay parámetros en la solicitud
if (!$_REQUEST) {
    var_dump(['validacion' => false, 'msg' => "Sin parámetros"]);
}

try {
    $path = 'logs';

    // Verifica si el directorio 'logs' no existe y lo crea si es necesario
    if (!is_dir($path)) {
        if (mkdir($path, 0777, true)) {
            var_dump("Directorio creado exitosamente.");
        } else {
            var_dump("Error al crear el directorio.");
        }
    } else {
        var_dump("El directorio ya existe.");
    }

    // Genera un nombre de archivo de log basado en la fecha y hora actual
    $dateLog = date("Y-m-d__H-i-s");
    $logFile = fopen("logs/log-$dateLog.log", 'a') or die("Error creando archivo");

    // Obtiene el recurso solicitado
    $httpRefererName = null;
    $httpReferer = null;
    $possibleSources = [
        'HTTP_HOST',
        'SERVER_NAME',
        'REQUEST_URI',
        'HTTP_REFERER',
        'HTTP_ORIGIN',
        'HTTP_X_APP_ORIGIN',
        'HTTP_AUTHORIZATION',
        'REMOTE_ADDR',
        'SERVER_ADDR'
    ];

    // Busca la primera referencia válida en las posibles fuentes
    foreach ($possibleSources as $source) {
        if (isset($_SERVER[$source])) {
            $httpRefererName = $source;
            $httpReferer = $_SERVER[$source];
            break;
        }
    }

    // Array de retorno
    $datos;

    // Guarda la referencia del solicitante en el archivo de log
    $datos['httpRefererName'] = $httpRefererName;
    $datos['httpReferer'] = $httpReferer;
    fwrite($logFile, date("Y/m/d H:i:s") . " Solicitud desde = $httpRefererName: $httpReferer \n") or die("Error escribiendo en el archivo");

    // Guarda el método de la solicitud en el archivo de log
    $method = $_SERVER["REQUEST_METHOD"];
    $datos['method'] = $method;
    fwrite($logFile, date("Y/m/d H:i:s") . " Método = $method \n") or die("Error escribiendo en el archivo");

    // Guarda el puerto de la solicitud en el archivo de log
    $httpRefererPort = $_SERVER['SERVER_PORT'];
    $datos['httpRefererPort'] = $httpRefererPort;
    fwrite($logFile, date("Y/m/d H:i:s") . " Puerto = $httpRefererPort \n") or die("Error escribiendo en el archivo");

    // Guarda el tipo de datos de la solicitud en el archivo de log
    $typeData = gettype($_REQUEST);
    $datos['typeData'] = $typeData;
    fwrite($logFile, date("Y/m/d H:i:s") . " Tipo de datos = $typeData \n") or die("Error escribiendo en el archivo");

    // Guarda cada uno de los campos incluidos en la solicitud en el archivo de log
    foreach ($_REQUEST as $name => $value) {
        $assignment = $name . " = '" . trim($value) . "'";
        $datos[$name] = trim($value);
        fwrite($logFile, date("Y/m/d H:i:s") . " $assignment \n") or die("Error escribiendo en el archivo");
    }

    // Cierra el archivo de log
    fclose($logFile);

    var_dump(['validacion' => true, 'msg' => "Datos recibidos", 'datos' => $datos]);
} catch (Exception $e) {
    // Manejo de excepciones
    var_dump(['validacion' => false, 'msg' => "Error: " . $e->getMessage()]);
}
