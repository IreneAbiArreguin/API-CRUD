# contexto.md

Este documento tiene como propósito explicar la estructura interna del proyecto Control Académico, incluyendo las carpetas más relevantes, su función, y la arquitectura general. Esto permite entender cómo está organizado el sistema y por qué se toman ciertas decisiones técnicas.

---

## Estructura General del Proyecto

```
control-academico/
├── backend/
│   ├── public/
│   ├── src/
│   │   ├── controllers/
│   │   └── models/
│   └── database/
├── composer.json
├── frontend/
│   ├── public/
│   └── src/
│       ├── components/
│       └── types/
├── docker/
├── phpmyadmin/
├── docker-compose.yml
└── README.md
```

---

## backend/
Contiene toda la lógica del backend desarrollado en PHP siguiendo el patrón MVC (Modelo-Vista-Controlador).

- public/: Punto de entrada del backend. Aquí se ubica el archivo index.php que recibe todas las peticiones y las redirige según las rutas definidas.
- src/: Carpeta principal de código fuente backend.
  - controllers/: Contiene la lógica de negocio para cada entidad (Alumno, Materia, Calificación, etc.).
  - models/: Define la estructura y el acceso a la base de datos para cada entidad.
  - core/ (opcional): Se puede agregar para incluir clases base comunes como Controller.php, Model.php, Router.php.
  - utils/ (opcional): Clases auxiliares para autenticación, envío de correos, manejo de archivos, etc.
  - routes/ (opcional): Definición de rutas centralizadas.
- database/: Archivo connection.php para conexión a la base de datos. Aquí también se pueden agregar scripts de migraciones en el futuro.

## frontend/
Contiene todo el código del frontend desarrollado en Vue 3, TypeScript y Bootstrap 5.

- public/: Archivos públicos como index.html, favicon, etc.
- src/: Código fuente de Vue.
  - components/: Componentes reutilizables y específicos de cada entidad.
  - types/: Archivos .ts con tipos y modelos para TypeScript.
  - pages/ (opcional): Vistas completas asociadas a rutas.
  - services/ (opcional): Módulos para hacer peticiones a la API.

## docker/
Contiene los Dockerfile y configuraciones necesarias para levantar los servicios (backend, frontend, base de datos, etc.).

- Dockerfile.backend: Imagen para PHP + Apache.
- Dockerfile.frontend: Imagen para Vite + Node.

## phpmyadmin/
Configuraciones personalizadas para levantar phpMyAdmin junto a MySQL mediante docker-compose.

## docker-compose.yml
Archivo raíz para definir y orquestar los contenedores necesarios: backend, frontend, MySQL, phpMyAdmin.

## README.md
Manual de inicio del proyecto: cómo instalar dependencias, levantar los servicios, realizar pruebas, y usar el sistema.

---

Con esta estructura, el proyecto está preparado para crecer, escalar, y mantenerse de forma ordenada. El uso de contexto.md como documento técnico complementa al README y permite entender las decisiones de diseño y arquitectura sin contaminar el archivo de bienvenida.

---

## Actualización de entidades y funcionalidades (mayo 2025)

Se han integrado al backend las siguientes entidades adicionales, cada una con su propio modelo y controlador siguiendo el patrón MVC:

- **Materia:** Permite gestionar las materias del sistema académico. Se pueden realizar operaciones CRUD (crear, consultar, actualizar y eliminar materias). El modelo encapsula el acceso a la tabla `materias` y el controlador expone los endpoints RESTful.
- **Calificación:** Permite registrar y consultar las calificaciones de los alumnos en las distintas materias, asociando también al profesor responsable. Incluye operaciones CRUD sobre la tabla `calificaciones`.
- **Profesor:** Permite administrar la información de los profesores, incluyendo su relación con usuarios del sistema. Incluye operaciones CRUD sobre la tabla `profesores`.
- **Usuario:** Permite gestionar los usuarios del sistema, necesarios para la autenticación y la seguridad. Incluye operaciones CRUD sobre la tabla `usuarios`.

Cada entidad cuenta con:
- Un modelo en `src/models/` que encapsula la lógica de acceso a datos usando métodos estáticos.
- Un controlador en `src/controllers/` que gestiona las peticiones HTTP y responde en formato JSON.
- Rutas definidas en `public/index.php` que direccionan las peticiones a los controladores correspondientes según el verbo HTTP y la URL.

Estas integraciones permiten que el sistema soporte un flujo académico completo, donde los usuarios pueden gestionar alumnos, materias, profesores y calificaciones, sentando la base para implementar seguridad, reportes y otras funcionalidades avanzadas.

---

## Actualización: Sistema de autenticación y vistas centralizadas (mayo 2025)

Se ha implementado un sistema de autenticación de usuarios en el backend, siguiendo el patrón MVC y la estructura del proyecto. Ahora, los usuarios pueden iniciar y cerrar sesión mediante los endpoints `/login` y `/logout`. El sistema utiliza sesiones PHP para mantener la autenticación y proteger el acceso a rutas sensibles.

**Detalles de la implementación:**
- Se añadió un método `autenticar` en el modelo `Usuario` para validar credenciales.
- Se creó el controlador `AuthController` para manejar el inicio y cierre de sesión.
- Se agregaron rutas `/login` y `/logout` en el archivo de ruteo principal.
- Los controladores de las entidades ahora verifican la autenticación antes de ejecutar operaciones sensibles.
- Las respuestas y mensajes del backend se centralizan usando la clase `VistaJson`, ubicada en la carpeta `vistas/`. Esto permite respuestas uniformes y mensajes personalizados para errores y éxitos.

**Ventajas:**
- Seguridad básica para el acceso a la API.
- Respuestas JSON consistentes y centralizadas.
- Base para implementar roles y permisos en el futuro.

Esta integración mejora la robustez y mantenibilidad del sistema, permitiendo un control más claro sobre la autenticación y la gestión de mensajes en las respuestas de la API.

# Actualización detallada (mayo 2025)

### Seguridad y manejo de archivos

- Se implementó la subida y descarga de archivos en el backend. Los archivos se almacenan en la carpeta `backend/archivos/` y su nombre único se registra en la base de datos junto con el usuario que lo subió y la fecha.
- Solo se permiten archivos de tipo imagen (`jpg`, `png`) y PDF, con un tamaño máximo de 5MB. Los archivos se renombran automáticamente para evitar sobrescribir.
- Se agregaron validaciones de seguridad para tipo y tamaño de archivo.
- Los endpoints de archivos son:
  - `POST /archivos/subir` para subir archivos (requiere autenticación).
  - `GET /archivos/descargar/{nombre}` para descargar archivos (requiere autenticación).

### Endpoints y flujos principales

- Todos los endpoints CRUD para alumnos, materias, profesores, usuarios y calificaciones están implementados y protegidos por autenticación.
- El flujo académico principal permite que solo los profesores registrados puedan asignar calificaciones a alumnos en materias.
- Se implementó un reporte de calificaciones que puede consultarse en JSON (`/calificaciones/reporte`) o descargarse en PDF (`/calificaciones/reporte/pdf`).

### Documentación y pruebas

- Todos los endpoints están documentados en un archivo markdown aparte, con ejemplos de uso y explicación de las respuestas esperadas.
- Se recomienda probar los endpoints con Postman o Insomnia antes de la integración con el frontend.

### Consideraciones de seguridad

- El backend valida el rol del usuario antes de permitir acciones sensibles (por ejemplo, solo profesores pueden registrar calificaciones).
- Las sesiones PHP se usan para la autenticación y autorización.
- Se recomienda revisar los permisos de la carpeta de archivos y limitar el acceso en producción.

---

**Última actualización:**  
Se completó la integración de manejo seguro de archivos, validaciones y documentación de endpoints.

### Asociación de archivos a entidades

- Ahora los archivos pueden asociarse a entidades como alumnos, materias, etc.
- En la tabla `archivos` se agregó el campo `id_alumno` (puedes agregar otros como `id_materia` según lo requieras).
- Al subir un archivo, puedes enviar el ID de la entidad a asociar (por ejemplo, `id_alumno`) como campo adicional en el formulario.
- Hay un endpoint para consultar todos los archivos asociados a un alumno:  
  - `GET /archivos/alumno/{id_alumno}`  
  - Devuelve la lista de archivos subidos para ese alumno.