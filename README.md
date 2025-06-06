# Contexto del Proyecto: Sistema de Control Académico

## Descripción General

Este proyecto es un sistema de control académico desarrollado con un backend en PHP (API RESTful) y un frontend en Vue 3. Permite la gestión de alumnos, materias, profesores, usuarios, calificaciones, generación de reportes y manejo de archivos asociados a entidades académicas.

---

## Arquitectura

- **Backend:** PHP puro, arquitectura tipo MVC, expuesto como API RESTful.
- **Frontend:** Vue 3 + Vite + TypeScript.
- **Base de datos:** MySQL.
- **Contenedores:** Docker y Docker Compose para orquestar servicios (nginx, php, mysql, phpmyadmin, node para frontend).
- **Comunicación:** El frontend consume la API del backend usando fetch/axios y mantiene la sesión mediante cookies.

---

## Funcionalidades principales

- **Autenticación y autorización:**  
  - Login y logout de usuarios.
  - Protección de rutas y control de acceso por roles (profesor, alumno, admin).
- **Gestión de entidades:**  
  - CRUD de alumnos, materias, profesores, usuarios y calificaciones.
- **Reportes:**  
  - Consulta y descarga de reportes de calificaciones en PDF.
- **Manejo de archivos:**  
  - Subida y descarga de archivos asociados a usuarios y entidades (por ejemplo, tareas, constancias).
  - Validación de tipo y tamaño de archivo.
- **Alertas y notificaciones:**  
  - El frontend muestra alertas visuales para informar al usuario sobre el resultado de las acciones (éxito o error).

---

## Seguridad

- **CORS:** Configurado para permitir solo el origen del frontend.
- **Sesiones:** Uso de cookies de sesión para mantener la autenticación.
- **Validaciones:**  
  - En backend y frontend para datos de entrada y archivos.
  - Solo usuarios autenticados pueden acceder a rutas protegidas.
- **Permisos de archivos:**  
  - Los archivos subidos se almacenan en una carpeta protegida del backend y se renombran para evitar sobrescribir.

---

## Flujo de uso

1. El usuario inicia sesión desde el frontend.
2. Según su rol, puede acceder a las diferentes secciones del sistema.
3. Puede consultar, crear, editar o eliminar alumnos, materias, profesores, usuarios y calificaciones.
4. Puede subir archivos asociados a entidades y descargarlos.
5. Puede generar y descargar reportes en PDF.
6. El sistema notifica al usuario mediante alertas visuales.

---

## Pruebas y documentación

- Todos los endpoints están documentados en `Endpoints.md` con ejemplos de peticiones y respuestas.
- El sistema ha sido probado con Postman y desde el frontend.
- Se recomienda probar todos los flujos antes de la entrega final.

---

## Última actualización

- Se integró el manejo de archivos, reportes PDF, alertas visuales y protección de rutas en el frontend.
- Documentación y pruebas completas.

# Sistema de Control Académico

## Descripción

Sistema web para la gestión académica de alumnos, materias, profesores, usuarios, calificaciones y archivos, con generación de reportes en PDF.  
Desarrollado con PHP (backend), Vue 3 (frontend) y MySQL, orquestado con Docker.

---

## Tecnologías utilizadas

- **Backend:** PHP 8.1, arquitectura MVC, API RESTful
- **Frontend:** Vue 3, Vite, TypeScript
- **Base de datos:** MySQL 8
- **Contenedores:** Docker, Docker Compose
- **Otros:** Nginx, phpMyAdmin

---

## Instalación y ejecución

1. **Clona el repositorio**
   ```sh
   git clone <url-del-repo>
   cd control-academico
   ```

2. **Levanta los servicios con Docker Compose**
   ```sh
   docker-compose up --build
   ```

3. **Accede a las aplicaciones**
   - **Frontend:** [http://localhost:5173](http://localhost:5173)
   - **Backend/API:** [http://localhost](http://localhost)
   - **phpMyAdmin:** [http://localhost:8080](http://localhost:8080)

---

## Uso

- Inicia sesión con un usuario registrado.
- Navega por las secciones: alumnos, materias, profesores, usuarios, calificaciones.
- Realiza operaciones CRUD y sube/descarga archivos.
- Genera y descarga reportes de calificaciones en PDF.
- El sistema muestra alertas visuales para cada acción.

---

## Documentación

- **Endpoints de la API:** Ver `Endpoints.md` para ejemplos de uso y respuestas.
- **Contexto y arquitectura:** Ver `Contexto.md`.

---

## Notas

- El sistema requiere que los contenedores estén corriendo para funcionar correctamente.
- Los archivos subidos se almacenan en `backend/archivos/`.
- Para desarrollo, el frontend y backend están configurados para comunicarse vía CORS seguro.

---

## Créditos

Desarrollado por Sergio Garcia, Daris Martinez e Irene Arreguin 
Escuela: ITCH
Fecha: 30/05/2025
