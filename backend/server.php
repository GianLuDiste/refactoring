<?php
/**
*    File        : backend/server.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/**FOR DEBUG: */
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// punto de entrada al backend, recibe solicitudes desde el front y las redirige al modulo correspondiente

header("Access-Control-Allow-Origin: *");  // permite que cualquier navegador se comunique con este servidor
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // indica que metodos HTTP se permiten
header("Access-Control-Allow-Headers: Content-Type"); // solicitudes que incluyan el tipo de contenido

function sendCodeMessage($code, $message = "")  // responder con codigo http
{
    http_response_code($code);
    echo json_encode(["message" => $message]);
    exit();
}

// Respuesta correcta para solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
{
    sendCodeMessage(200); // 200 OK
}

// Obtener el módulo desde la query string
$uri = parse_url($_SERVER['REQUEST_URI']);
$query = $uri['query'] ?? '';
parse_str($query, $query_array);
$module = $query_array['module'] ?? null;

// Validación de existencia del módulo
if (!$module)
{
    sendCodeMessage(400, "Módulo no especificado");
}

// Validación de caracteres seguros: solo letras, números y guiones bajos
if (!preg_match('/^\w+$/', $module))  // protege contra inyecciones de codigo o rutas maliciosas
{
    sendCodeMessage(400, "Nombre de módulo inválido");
}

// Buscar el archivo de ruta correspondiente
$routeFile = __DIR__ . "/routes/{$module}Routes.php";

if (file_exists($routeFile))
{
    require_once($routeFile);
}
else
{
    sendCodeMessage(404, "Ruta para el módulo '{$module}' no encontrada");
}


// Resumen:
// reibe solicitud del navegador
// valida si el modulo es correcto
// redirige la peticion a; archivo PHP adecuado segun el modulo
// maneja solicitudes OPTIONS automaticamente pata CORS
// si algo fallaa respone con un mensaje de error en JSON