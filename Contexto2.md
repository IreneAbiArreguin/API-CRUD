# Contexto del Proyecto: Sistema de Control Académico

## Descripción General

Este sistema es una aplicación web para la gestión académica de una institución educativa. Permite administrar alumnos, profesores, materias, calificaciones, usuarios, grupos y archivos, así como la autenticación y autorización de usuarios. El sistema está dividido en dos grandes módulos: **backend** (API REST en PHP) y **frontend** (SPA en Vue 3).

---

## Cambios recientes (23 de mayo de 2025)

- **Unificación de roles:**  
  Ya no existen tablas separadas para usuarios y profesores; ahora todos los usuarios (profesores y administradores) están en una sola tabla de usuarios con un campo `rol`.  
  Los alumnos ya no pueden iniciar sesión ni acceder al sistema.
- **Gestión de grupos:**  
  Se agregó la entidad y gestión de grupos, permitiendo asignar alumnos a grupos y administrar estos desde el sistema.
- **Flujo de autenticación:**  
  El login y la gestión de usuarios se realiza desde un único formulario y flujo, sin distinción de roles en el frontend.
- **Frontend implementado completamente:**  
  El frontend en Vue 3 ya cubre todas las funcionalidades:  
  - CRUD de alumnos, profesores, materias, grupos, usuarios y calificaciones.
  - Subida y descarga de archivos.
  - Generación y descarga de reportes en PDF.
  - Protección de rutas y control de acceso por rol.
  - Interfaz moderna y responsiva.
- **Manejo de archivos:**  
  El manejo de archivos ahora sigue un patrón RESTful, igual que el resto de las entidades.
- **Mejoras de seguridad:**  
  Contraseñas encriptadas, validaciones de datos, control de acceso y manejo seguro de archivos.

---

## Arquitectura y Estructura de Carpetas

```
control-academico/
├── backend/                # Lógica y API del servidor (PHP)
│   ├── archivos/           # Archivos subidos por los usuarios
│   ├── database/           # Scripts SQL y backups
│   ├── public/             # Punto de entrada (index.php)
│   ├── src/                # Código fuente (controladores, modelos)
│   │   ├── controllers/
│   │   └── models/
│   ├── vistas/             # Vistas y mensajes personalizados
│   └── vendor/             # Dependencias de Composer
├── docker/                 # Configuración de contenedores y servicios
│   ├── mysql/              # Script de inicialización de la base de datos
│   ├── php.ini             # Configuración personalizada de PHP
│   └── phpmyadmin/         # Configuración de phpMyAdmin (opcional)
├── frontend/               # Aplicación cliente (Vue 3)
│   ├── public/             # Archivos públicos del frontend
│   ├── src/                # Código fuente Vue (componentes, router, servicios)
│   └── node_modules/       # Dependencias de Node
├── nginx/                  # Configuración personalizada de Nginx (opcional)
├── docker-compose.yml      # Orquestación de servicios
```

---

## Tecnologías Utilizadas

- **Backend:** PHP 8.1 (API RESTful, arquitectura tipo MVC)
- **Frontend:** Vue 3, Vite, TypeScript, Bootstrap 5
- **Base de datos:** MySQL 8
- **Contenedores:** Docker y Docker Compose
- **Otros:** Nginx, phpMyAdmin, Bootstrap Icons

---

## Principales Funcionalidades

- **Autenticación y autorización:**  
  - Login y logout de usuarios con sesiones PHP.
  - Control de acceso por roles (profesor, admin).
- **Gestión de entidades:**  
  - CRUD de alumnos, materias, profesores, usuarios, grupos y calificaciones.
- **Manejo de archivos:**  
  - Subida y descarga de archivos asociados a alumnos y otras entidades, siguiendo un patrón RESTful.
- **Reportes:**  
  - Generación y descarga de reportes de calificaciones en PDF.
- **Alertas y notificaciones:**  
  - El frontend muestra alertas visuales (éxito/error) usando Bootstrap.
- **Protección de rutas:**  
  - El frontend oculta opciones y protege rutas según el estado de autenticación.

---

## Seguridad

- **Contraseñas:**  
  - Las contraseñas de usuarios se almacenan encriptadas usando `password_hash`.
- **CORS:**  
  - Configuración para permitir solo solicitudes desde el frontend.
- **Sesiones:**  
  - Uso de cookies de sesión para mantener la autenticación.
- **Validaciones:**  
  - Validación de datos en backend y frontend.
- **Permisos de archivos:**  
  - Archivos subidos se almacenan en carpetas protegidas y se renombran para evitar sobrescritura.

---

## Flujo de Uso

1. El usuario inicia sesión desde el frontend.
2. Según su rol, accede a las secciones permitidas.
3. Puede consultar, crear, editar o eliminar alumnos, materias, profesores, usuarios, grupos y calificaciones.
4. Puede subir y descargar archivos.
5. Puede generar y descargar reportes en PDF.
6. El sistema notifica al usuario mediante alertas visuales.

---

## Inicialización y Persistencia de Datos

- La base de datos se inicializa automáticamente con el script `init.sql` al crear el volumen de datos por primera vez.
- Los datos de MySQL se mantienen persistentes gracias al volumen `db_data` definido en `docker-compose.yml`.
- El script de inicialización **solo se ejecuta si el volumen es nuevo**.

---

## Notas Finales

- El sistema está preparado para desarrollo y pruebas locales.
- La estructura de carpetas está pensada para claridad y separación de responsabilidades.
- El uso de Docker facilita la portabilidad y despliegue del sistema.

---

**Última actualización:** 23 de mayo de 2025