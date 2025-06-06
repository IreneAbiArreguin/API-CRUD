# Endpoints de la API - Control Académico

## Autenticación
- **POST /login**
  - Body:  
    ```json
    { "nombre_usuario": "usuario", "contrasena": "clave" }
    ```
  - Respuesta exitosa:
    ```json
    {
      "mensaje": "Autenticación exitosa",
      "datos": {
        "usuario": "usuario",
        "rol": "profesor",
        "datos_usuario": {
            "id": 1,
            "nombre_usuario": "admin",
            "email": "asdf",
            "password_hash": "$2y$10$nBMJp6/JdGOqJtAxDccFJONYubAijlvHDdonaBnOaIGEyoBaLZiRG",
            "rol": "admin",
            "nombres": "",
            "apellidos": "",
            "id_especialidad": 0
        }
      }
    }
    ```
  - Respuesta error:
    ```json
    { "error": "Credenciales incorrectas" }
    ```
- **POST /logout**
  - Respuesta exitosa:
    ```json
    { "mensaje": "Sesión cerrada" }
    ```

---

## Alumnos
- **GET /alumnos**
  - Devuelve: Lista de todos los alumnos.
  - Respuesta ejemplo:
    ```json
    [
      { "id": 1, "nombre": "Juan", "apellido": "Pérez", "fecha_nacimiento": "2000-01-01", "id_grupo": 1, "email": "abc@abc", "telefono": 123 },

      { "id": 2, "nombre": "Ana", "apellido": "López", "fecha_nacimiento": "2001-02-02", "id_grupo": 2, "email": "abc@abc", "telefono": 123 }
    ]
    ```
- **GET /alumnos/{id}**
  - Devuelve: Datos del alumno con ese ID.
  - Respuesta ejemplo:
    ```json
    { "id": 1, "nombre": "Juan", "apellido": "Pérez", "fecha_nacimiento": "2000-01-01", "id_grupo": 1, "email": "abc@abc", "telefono": 123 }
    ```
- **POST /alumnos**
  - Body:
    ```json
    {
      "nombre": "Juan",
      "apellido": "Pérez",
      "fecha_nacimiento": "2000-01-01",
      "id_grupo": 1,
      "email": "abc@abc",
      "telefono": 123
    }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Alumno creado correctamente" }
    ```
- **PUT /alumnos/{id}**
  - Body:
    ```json
    {
      "nombre": "Juan",
      "apellido": "Pérez",
      "fecha_nacimiento": "2000-01-01",
      "id_grupo": 1,
      "email": "abc@abc",
      "telefono": 123
    }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Alumno actualizado correctamente" }
    ```
- **DELETE /alumnos/{id}**
  - Respuesta exitosa:
    ```json
    { "mensaje": "Alumno eliminado correctamente" }
    ```
  **GET /alumnos/reporte/pdf**

  - Respuesta: Descarga de PDF con el listado de alumnos.
---

## Grupos
- **GET /grupos**
  - Devuelve: Lista de todos los grupos.
  - Respuesta ejemplo:
    ```json
    [
      { "id_grupo": 1, "nombre": "1A" },
      { "id_grupo": 2, "nombre": "2B" }
    ]
    ```
- **GET /grupos/{id}**
  - Devuelve: Datos del grupo con ese ID.
  - Respuesta ejemplo:
    ```json
    { "id_grupo": 1, "nombre": "1A" }
    ```
- **POST /grupos**
  - Body:
    ```json
    { "nombre": "3C" }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Grupo creado correctamente" }
    ```
- **PUT /grupos/{id}**
  - Body:
    ```json
    { "nombre": "3C" }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Grupo actualizado correctamente" }
    ```
- **DELETE /grupos/{id}**
  - Respuesta exitosa:
    ```json
    { "mensaje": "Grupo eliminado correctamente" }
    ```

---

## Materias
- **GET /materias**
  - Respuesta ejemplo:
    ```json
    [
      { "id": 1, "nombre": "Matemáticas", "descripcion": "Lorem pouse" },
      { "id": 2, "nombre": "Historia", "descripcion": "Lorem pouse" }
    ]
    ```
- **GET /materias/{id}**
  - Respuesta ejemplo:
    ```json
    { "id": 1, "nombre": "Matemáticas", "descripcion": "Lorem pouse" }
    ```
- **POST /materias**
  - Body:
    ```json
    { "nombre": "Matemáticas", "descripcion": "Lorem pouse" }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Materia creada correctamente" }
    ```
- **PUT /materias/{id}**
  - Body:
    ```json
    { "nombre": "Matemáticas", "descripcion": "Lorem pouse" }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Materia actualizada correctamente" }
    ```
- **DELETE /materias/{id}**
  - Respuesta exitosa:
    ```json
    { "mensaje": "Materia eliminada correctamente" }
    ```

---

## Usuarios
- **GET /usuarios**
  - Respuesta ejemplo:
    ```json
    [
      { "id": 1, "nombre_usuario": "admin", "email": "asdf", "password_hash": "$2y$10$nBMJp6/JdGOqJtAxDccFJONYubAijlvHDdonaBnOaIGEyoBaLZiRG","rol": "admin",
        "nombres": "", "apellidos": "", "id_especialidad": 0 }
    ]
    ```
- **GET /usuarios/{id}**
  - Respuesta ejemplo:
    ```json
    { "id": 3, "nombre_usuario": "pepe2", "email": "asdf", "password_hash": "$2y$10$nBMJp6/JdGOqJtAxDccFJONYubAijlvHDdonaBnOaIGEyoBaLZiRG","rol": "profesor",
        "nombres": "Jose", "apellidos": "Lopez", "id_especialidad": 1 }
    ```
- **POST /usuarios**
  - Body:
    ```json
    { "nombre_usuario": "nuevo", "contrasena": "clave", "rol": "profesor", "nombres": "Jose", "apellidos": "Lopez", "id_especialidad": 1 }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Usuario creado" }
    ```
- **PUT /usuarios/{id}**
  - Body:
    ```json
    { "nombre_usuario": "nuevo", "contrasena": "clave", "rol": "profesor", "nombres": "Jose", "apellidos": "Lopez", "id_especialidad": 1 }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Usuario actualizado" }
    ```
- **DELETE /usuarios/{id}**
  - Respuesta exitosa:
    ```json
    { "mensaje": "Usuario eliminado" }
    ```

---

## Calificaciones
- **GET /calificaciones**
  - Respuesta ejemplo:
    ```json
    [
      {
        "id": 1,
        "nombre_alumno": "Juan",
        "nombre_materia": "Matemáticas",
        "nombre_profesor": "Carlos",
        "calificacion": 9.5,
        "fecha": "2025-05-21"
      }
    ]
    ```
- **GET /calificaciones/{id}**
  - Respuesta ejemplo:
    ```json
    {
      "id": 1,
      "nombre_alumno": "Juan",
      "nombre_materia": "Matemáticas",
      "nombre_profesor": "Carlos",
      "calificacion": 9.5,
      "fecha": "2025-05-21"
    }
    ```
- **POST /calificaciones**
  - Body:
    ```json
    { "id_alumno": 1, "id_materia": 2, "calificacion": 9.5, "fecha": "2025-05-21" }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Calificación registrada" }
    ```
  - Respuesta error (no autorizado):
    ```json
    { "error": "No autorizado" }
    ```
- **PUT /calificaciones/{id}**
  - Body:
    ```json
    { "calificacion": 8.0 }
    ```
  - Respuesta exitosa:
    ```json
    { "mensaje": "Calificación actualizada" }
    ```
- **DELETE /calificaciones/{id}**
  - Respuesta exitosa:
    ```json
    { "mensaje": "Calificación eliminada" }
    ```
    ```
- **GET /calificaciones/reporte/pdf**
  - Respuesta: Descarga de PDF con el reporte de calificaciones.

---

## Archivos
- **POST /archivos**
  - Body: Form-data, clave `archivo` (tipo archivo).
  - Respuesta exitosa:
    ```json
    {
      "mensaje": "Archivo subido correctamente",
      "datos": {
        "nombre": "65f8c1e2b7a1d.pdf"
      }
    }
    ```
  - Respuesta error (tipo no permitido):
    ```json
    { "error": "Tipo de archivo no permitido" }
    ```
- **GET /archivos/descargar/{nombre}**
  - Respuesta: Descarga el archivo solicitado.
  - Respuesta error:
    ```json
    { "error": "Archivo no encontrado" }
    ```

---

**Notas:**
- Todos los endpoints protegidos requieren sesión iniciada.
- Los endpoints de calificaciones solo permiten registrar si el usuario es profesor.
- Los archivos subidos solo aceptan imágenes y PDF, máximo 5MB, y se renombran para evitar sobrescribir.