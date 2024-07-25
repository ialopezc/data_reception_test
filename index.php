<?php
$path = 'logs';

// Verifica si el directorio 'logs' no existe y lo crea si es necesario
if (!is_dir($path)) {
    if (!mkdir($path, 0777, true)) {
        throw new Exception("Error al crear el directorio.");
    }
}

// Genera un nombre de archivo de log basado en la fecha y hora actual
$dateLog = date("Y-m-d__H-i-s");
$dateRegister = date("Y/m/d H:i:s");
$logFile = fopen("$path/log-$dateLog.log", 'a') or die("Error creando archivo");

try {
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
    fwrite($logFile, "$dateRegister Solicitud desde = $httpRefererName: $httpReferer \n") or die("Error escribiendo en el archivo");

    // Guarda el método de la solicitud en el archivo de log
    $method = $_SERVER["REQUEST_METHOD"];
    $datos['method'] = $method;
    fwrite($logFile, "$dateRegister Método = $method \n") or die("Error escribiendo en el archivo");

    // Guarda el puerto de la solicitud en el archivo de log
    $httpRefererPort = $_SERVER['SERVER_PORT'];
    $datos['httpRefererPort'] = $httpRefererPort;
    fwrite($logFile, "$dateRegister Puerto = $httpRefererPort \n") or die("Error escribiendo en el archivo");

    // Guarda el tipo de datos de la solicitud en el archivo de log
    $typeData = gettype($_REQUEST);
    $datos['typeData'] = $typeData;
    fwrite($logFile, "$dateRegister Tipo de datos = $typeData \n") or die("Error escribiendo en el archivo");

    // Verifica si no hay parámetros en la solicitud
    if (!$_REQUEST) {
        throw new Exception("Sin parámetros");
    }

    // Guarda cada uno de los campos incluidos en la solicitud en el archivo de log
    foreach ($_REQUEST as $name => $value) {
        $assignment = $name . " = '" . trim($value) . "'";
        $datos[$name] = trim($value);
        fwrite($logFile, "$dateRegister $assignment \n") or die("Error escribiendo en el archivo");
    }

    // Cierra el archivo de log
    fclose($logFile);

    var_dump(['validacion' => true, 'msg' => "Datos recibidos", 'datos' => $datos]);
} catch (Exception $e) {
    // Manejo de excepciones
    fwrite($logFile, "$dateRegister Error = " .  $e->getMessage() . " \n") or die("Error escribiendo en el archivo");
    var_dump(['validacion' => false, 'msg' => "Error: " . $e->getMessage()]);
}
