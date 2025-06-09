/**
*    File        : frontend/js/controllers/studentsController.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

// maneja la logica de vista del modulo de estudiantes

import { studentsAPI } from '../api/studentsAPI.js'; // importa las funciones del archivo studentsAPI.js que se comunican con el Backend

document.addEventListener('DOMContentLoaded', () => // document es el objeto raiz de la pagina, cuando se termina de cargar la pagina
                                                    // se ejecuta lo de esta funcion
{
    loadStudents(); // carga y muestra todos los estudiantes en la tabla
    setupFormHandler(); //prepara el formulario
    setupCancelHandler(); //configura el comportamiento del boton "Cancelar"
});
  
function setupFormHandler() // configura el envio del formulario
{
    const form = document.getElementById('studentForm');   
    form.addEventListener('submit', async e => //configura el form para que no recargue la pagina al enviarse
    {
        e.preventDefault(); // cancela el comportamiento por defecto del navegador
        const student = getFormData(); // se extraen los datos del form
    
        try 
        {
            if (student.id) // if (ya existe el estudiante)
            {
                await studentsAPI.update(student); // lo edita
            } 
            else 
            {
                await studentsAPI.create(student); // sino, lo crea
            }
            clearForm();
            loadStudents();
        }
        catch (err)   // catch: cualquier error que se produzca en el try, se atrapa 
        {
            console.error(err.message);
        }
    });
}

function setupCancelHandler()   //configurar la cancelacion del formulario 
{
    const cancelBtn = document.getElementById('cancelBtn');
    cancelBtn.addEventListener('click', () => 
    {
        document.getElementById('studentId').value = '';
    });
}
  
function getFormData() // obtener los datos del form
{
    return {
        id: document.getElementById('studentId').value.trim(),
        fullname: document.getElementById('fullname').value.trim(),
        email: document.getElementById('email').value.trim(),
        age: parseInt(document.getElementById('age').value.trim(), 10)
    };
}
  
function clearForm() // limpia el form
{
    document.getElementById('studentForm').reset();
    document.getElementById('studentId').value = '';
}
  
async function loadStudents() // cargar los estudiantes desde el backend
{
    try 
    {
        //const students = await studentsAPI.fetchAll(); //obtener todos los estudiantes desde el backend
        //renderStudentTable(students); //se muestran en pantalla
    } 
    catch (err) 
    {
        console.error('Error cargando estudiantes:', err.message);
    }
}
  
function renderStudentTable(students) // mostrar estudanetes en una tabla
{
    const tbody = document.getElementById('studentTableBody');
    tbody.replaceChildren();
  
    students.forEach(student =>  // para cada estudiante se crea una nueva fila <tr>
    {
        const tr = document.createElement('tr');
    
        tr.appendChild(createCell(student.fullname));
        tr.appendChild(createCell(student.email));
        tr.appendChild(createCell(student.age.toString()));
        tr.appendChild(createActionsCell(student));
    
        tbody.appendChild(tr);
    });
}
  
function createCell(text) //crea una celda
{
    const td = document.createElement('td');
    td.textContent = text;
    return td;
}
  
function createActionsCell(student) // botones editar y borrar
{
    const td = document.createElement('td');
  
    const editBtn = document.createElement('button');
    editBtn.textContent = 'Editar';
    editBtn.className = 'w3-button w3-blue w3-small';
    editBtn.addEventListener('click', () => fillForm(student));
  
    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Borrar';
    deleteBtn.className = 'w3-button w3-red w3-small w3-margin-left';
    deleteBtn.addEventListener('click', () => confirmDelete(student.id));
  
    td.appendChild(editBtn);
    td.appendChild(deleteBtn);
    return td;
}
  
function fillForm(student) // cargar datos en el formulario para edicion
{
    document.getElementById('studentId').value = student.id;
    document.getElementById('fullname').value = student.fullname;
    document.getElementById('email').value = student.email;
    document.getElementById('age').value = student.age;
}
  
async function confirmDelete(id) 
{
    if (!confirm('¿Estás seguro que deseas borrar este estudiante?')) return;
  
    try 
    {
        await studentsAPI.remove(id);
        loadStudents();
    } 
    catch (err) 
    {
        console.error('Error al borrar:', err.message);
    }
}
  