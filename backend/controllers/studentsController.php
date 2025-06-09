<?php
/**
*    File        : backend/controllers/studentsController.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

// es un controlador, es decir, define funciones que se ejecutan cuando el usuario opera sobre los estudiantes
// estas funciones se llaman desde routesFactory.php()

require_once("./models/students.php");

function handleGet($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (isset($input['id'])) 
    {
        $student = getStudentById($conn, $input['id']);
        echo json_encode($student);
    } 
    else
    {
        $students = getAllStudents($conn);
        echo json_encode($students);
    }
}

function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    $result = createStudent($conn, $input['fullname'], $input['email'], $input['age']);
    if ($result['inserted'] > 0) 
    {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    $result = updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age']);
    if ($result['updated'] > 0) 
    {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    // Validar si el estudiante tiene materias asociadas
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM students_subjects WHERE student_id = ?");
    $stmt->bind_param("i", $input['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['count'] > 0) {
        http_response_code(400);
        echo json_encode(["error" => "No se puede eliminar el estudiante porque tiene materias asociadas."]);
        return;
    }

    $result = deleteStudent($conn, $input['id']);
    if ($result['deleted'] > 0) 
    {
        echo json_encode(["message" => "Estudiante eliminado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}


// cada funcion:
//  a) Lee los datos del cuerpo de la peticion
//  b) Llama a una funcion del modelo que interactua con la BD
//  c) Devuelve un resultado (mensaje de exito o error)

?>