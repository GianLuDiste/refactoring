/**
*    File        : frontend/js/api/apiFactory.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

// este archivo define la funcion generica createAPI() que permite crear objetos de acceso al backend para cualquier modulo

export function createAPI(moduleName, config = {}) 
{
    const API_URL = config.urlOverride ?? `../../backend/server.php?module=${moduleName}`; // construccion de la URL de acceso al servidor
                                        // ?? significa "si el valor de la izquierda es null o undefined, usar el de la derecha"
    async function sendJSON(method, data) 
    {
        const res = await fetch(API_URL,
        {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)  // devuelve un string en formato JSON con los datos de DATA 
        });

        if (!res.ok) throw new Error(`Error en ${method}`);
        return await res.json();
    }

    return {  // devuelve un objeto con 4 funciones
        async fetchAll()
        {
            const res = await fetch(API_URL);
            if (!res.ok) throw new Error("No se pudieron obtener los datos");
            return await res.json();
        },
        async create(data)
        {
            return await sendJSON('POST', data);
        },
        async update(data)
        {
            return await sendJSON('PUT', data);
        },
        async remove(id)
        {
            return await sendJSON('DELETE', { id });
        }
    };
}
