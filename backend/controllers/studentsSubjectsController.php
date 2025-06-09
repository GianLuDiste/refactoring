<?php
/**
*    File        : backend/controllers/studentsSubjectsController.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

require_once("./models/studentsSubjects.php");

function handleGet($conn) 
{
    $studentsSubjects = getAllSubjectsStudents($conn);
    echo json_encode($studentsSubjects);
}

function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    // Validar si ya existe esa relación
    $existingRelation = getStudentSubjectByIds($conn, $input['student_id'], $input['subject_id']);
    if ($existingRelation) {
        http_response_code(400);
        echo json_encode(["error" => "La relación entre estudiante y materia ya existe"]);
        return;
    }

    $result = createStudentSubject($conn, $input['student_id'], $input['subject_id']);
    if ($result['inserted'] > 0) 
    {
        echo json_encode(["message" => "Relación creada correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo crear la relación"]);
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id'], $input['student_id'], $input['subject_id'], $input['approved'])) 
    {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $result = updateStudentSubject($conn, $input['id'], $input['student_id'], $input['subject_id'], $input['approved']);
    if ($result['updated'] > 0) 
    {
        echo json_encode(["message" => "Actualización correcta"]);
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

    $result = removeStudentSubject($conn, $input['id']);
    if ($result['deleted'] > 0) 
    {
        echo json_encode(["message" => "Relación eliminada"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>
