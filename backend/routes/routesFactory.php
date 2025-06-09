<?php
/**
*    File        : backend/routes/routesFactory.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

// define una funcion reutilizable llamada routeRequest() que se encarga de gestionar 
// las rutas REST y de conectar cada metodoHTTP con la funcion que debe ejecutarse

function routeRequest($conn, $customHandlers = [], $prefix = 'handle') 
{
    $method = $_SERVER['REQUEST_METHOD']; // obtiene el metodo HTTP usado en la solicitud

    // Lista de handlers CRUD por defecto
    $defaultHandlers = [
        'GET'    => $prefix . 'Get',
        'POST'   => $prefix . 'Post',
        'PUT'    => $prefix . 'Put',
        'DELETE' => $prefix . 'Delete'
    ];

    // Sobrescribir handlers por defecto si hay personalizados
    $handlers = array_merge($defaultHandlers, $customHandlers);

    if (!isset($handlers[$method])) // validar si el metodo es soportado (que sea (GET, POST, DELETE o PUT))
    {
        http_response_code(405);
        echo json_encode(["error" => "Método $method no permitido"]);
        return;
    }

    $handler = $handlers[$method]; // guarda el nombre de la funcion que debe ejecutarse

    if (is_callable($handler))  // verifica que sea una funcion valida
    {
        $handler($conn);
    }
    else
    {
        http_response_code(500);
        echo json_encode(["error" => "Handler para $method no es válido"]);
    }
}

// "despachador de rutas". recibe peticiones HTTP y llama a la funcion que las maneja
// tiene comportamient por defecto y puede ser personalizado
?>
