const API_URL = "http://localhost";

// ==========================
// INICIO DE SESIÓN
// ==========================


// Realiza una petición POST al endpoint /login del backend.
// Envía el nombre de usuario y la contraseña en formato JSON.
// Incluye las cookies de sesión para mantener la autenticación.
export async function login(nombre_usuario: string, contrasena: string) {
  const response = await fetch(`${API_URL}/login`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify({ nombre_usuario, contrasena }),
  });
  return response.json();
}

// Realiza una petición POST al endpoint /logout del backend.
// Cierra la sesión del usuario autenticado.
export async function logout() {
  const response = await fetch(`${API_URL}/logout`, {
    method: "POST",
    credentials: "include"
  });
  return response.json();
}


// ==========================
// ALUMNOS
// ==========================


// Realiza una petición GET al endpoint /alumnos del backend.
// Incluye las cookies de sesión para mantener la autenticación.
export async function getAlumnos() {
  const res = await fetch(`${API_URL}/alumnos`, {
    credentials: "include"
  })
  return res.json()
}

/**
 * Obtiene los datos de un alumno por ID.
 * @param id ID del alumno.
 * @returns Datos del alumno.
 */
export async function getAlumnoById(id: number) {
  const res = await fetch(`${API_URL}/alumnos/${id}`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Crea un nuevo alumno.
 * @param alumno Objeto con los datos del alumno.
 * @returns Mensaje de éxito o error.
 */
export async function createAlumno(alumno: any) {
  const res = await fetch(`${API_URL}/alumnos`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(alumno)
  });
  return res.json();
}

/**
 * Actualiza los datos de un alumno existente.
 * @param id ID del alumno.
 * @param alumno Objeto con los datos actualizados.
 * @returns Mensaje de éxito o error.
 */
export async function updateAlumno(id: number, alumno: any) {
  const res = await fetch(`${API_URL}/alumnos/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(alumno)
  });
  return res.json();
}

/**
 * Elimina un alumno por ID.
 * @param id ID del alumno.
 * @returns Mensaje de éxito o error.
 */
export async function deleteAlumno(id: number) {
  const res = await fetch(`${API_URL}/alumnos/${id}`, {
    method: "DELETE",
    credentials: "include"
  });
  return res.json();
}


export async function generarReporteAlumnos() {
  const res = await fetch(`${API_URL}/alumnos/reporte/pdf`, {
    credentials: "include"
  });
  return res.json();
}


// ==========================
// GRUPOS
// ==========================

/**
 * Obtiene la lista de todos los grupos.
 * @returns Array de grupos.
 */
export async function getGrupos() {
  const res = await fetch(`${API_URL}/grupos`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene los datos de un grupo por ID.
 * @param id ID del grupo.
 * @returns Datos del grupo.
 */
export async function getGrupoById(id: number) {
  const res = await fetch(`${API_URL}/grupos/${id}`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Crea un nuevo grupo.
 * @param grupo Objeto con los datos del grupo.
 * @returns Mensaje de éxito o error.
 */
export async function createGrupo(grupo: any) {
  const res = await fetch(`${API_URL}/grupos`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(grupo)
  });
  return res.json();
}

/**
 * Actualiza los datos de un grupo existente.
 * @param id ID del grupo.
 * @param grupo Objeto con los datos actualizados.
 * @returns Mensaje de éxito o error.
 */
export async function updateGrupo(id: number, grupo: any) {
  const res = await fetch(`${API_URL}/grupos/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(grupo)
  });
  return res.json();
}

/**
 * Elimina un grupo por ID.
 * @param id ID del grupo.
 * @returns Mensaje de éxito o error.
 */
export async function deleteGrupo(id: number) {
  const res = await fetch(`${API_URL}/grupos/${id}`, {
    method: "DELETE",
    credentials: "include"
  });
  return res.json();
}

// ==========================
// MATERIAS
// ==========================

/**
 * Obtiene la lista de todas las materias.
 * @returns Array de materias.
 */
export async function getMaterias() {
  const res = await fetch(`${API_URL}/materias`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene los datos de una materia por ID.
 * @param id ID de la materia.
 * @returns Datos de la materia.
 */
export async function getMateriaById(id: number) {
  const res = await fetch(`${API_URL}/materias/${id}`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Crea una nueva materia.
 * @param materia Objeto con los datos de la materia.
 * @returns Mensaje de éxito o error.
 */
export async function createMateria(materia: any) {
  const res = await fetch(`${API_URL}/materias`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(materia)
  });
  return res.json();
}

/**
 * Actualiza los datos de una materia existente.
 * @param id ID de la materia.
 * @param materia Objeto con los datos actualizados.
 * @returns Mensaje de éxito o error.
 */
export async function updateMateria(id: number, materia: any) {
  const res = await fetch(`${API_URL}/materias/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(materia)
  });
  return res.json();
}

/**
 * Elimina una materia por ID.
 * @param id ID de la materia.
 * @returns Mensaje de éxito o error.
 */
export async function deleteMateria(id: number) {
  const res = await fetch(`${API_URL}/materias/${id}`, {
    method: "DELETE",
    credentials: "include"
  });
  return res.json();
}


// ==========================
// USUARIOS
// ==========================

/**
 * Obtiene la lista de todos los usuarios.
 * @returns Array de usuarios.
 */
export async function getUsuarios() {
  const res = await fetch(`${API_URL}/usuarios`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene los datos de un usuario por ID.
 * @param id ID del usuario.
 * @returns Datos del usuario.
 */
export async function getUsuarioById(id: number) {
  const res = await fetch(`${API_URL}/usuarios/${id}`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Crea un nuevo usuario.
 * @param usuario Objeto con los datos del usuario.
 * @returns Mensaje de éxito o error.
 */
export async function createUsuario(usuario: any) {
  const res = await fetch(`${API_URL}/usuarios`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(usuario)
  });
  return res.json();
}

/**
 * Actualiza los datos de un usuario existente.
 * @param id ID del usuario.
 * @param usuario Objeto con los datos actualizados.
 * @returns Mensaje de éxito o error.
 */
export async function updateUsuario(id: number, usuario: any) {
  const res = await fetch(`${API_URL}/usuarios/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(usuario)
  });
  return res.json();
}

/**
 * Elimina un usuario por ID.
 * @param id ID del usuario.
 * @returns Mensaje de éxito o error.
 */
export async function deleteUsuario(id: number) {
  const res = await fetch(`${API_URL}/usuarios/${id}`, {
    method: "DELETE",
    credentials: "include"
  });
  return res.json();
}

// ==========================
// CALIFICACIONES
// ==========================

/**
 * Obtiene la lista de todas las calificaciones.
 * @returns Array de calificaciones.
 */
export async function getCalificaciones() {
  const res = await fetch(`${API_URL}/calificaciones`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene los datos de una calificación por ID.
 * @param id ID de la calificación.
 * @returns Datos de la calificación.
 */
export async function getCalificacionById(id: number) {
  const res = await fetch(`${API_URL}/calificaciones/${id}`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Crea una nueva calificación.
 * @param calificacion Objeto con los datos de la calificación.
 * @returns Mensaje de éxito o error.
 */
export async function createCalificacion(calificacion: any) {
  const res = await fetch(`${API_URL}/calificaciones`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(calificacion)
  });
  return res.json();
}

/**
 * Actualiza los datos de una calificación existente.
 * @param id ID de la calificación.
 * @param calificacion Objeto con los datos actualizados.
 * @returns Mensaje de éxito o error.
 */
export async function updateCalificacion(id: number, calificacion: any) {
  const res = await fetch(`${API_URL}/calificaciones/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify(calificacion)
  });
  return res.json();
}

/**
 * Elimina una calificación por ID.
 * @param id ID de la calificación.
 * @returns Mensaje de éxito o error.
 */
export async function deleteCalificacion(id: number) {
  const res = await fetch(`${API_URL}/calificaciones/${id}`, {
    method: "DELETE",
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene el reporte de calificaciones (JSON).
 * @returns Array de calificaciones.
 */
export async function getReporteCalificaciones() {
  const res = await fetch(`${API_URL}/calificaciones/reporte`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Descarga el reporte de calificaciones en PDF.
 * @returns Blob del PDF.
 */
export async function getReporteCalificacionesPDF() {
  const res = await fetch(`${API_URL}/calificaciones/reporte/pdf`, {
    credentials: "include"
  });
  return res.blob();
}

// ==========================
// ARCHIVOS
// ==========================

/**
 * Sube un archivo al servidor.
 * @param archivo Archivo a subir (File).
 * @returns Mensaje de éxito y datos del archivo.
 */
export async function subirArchivo(archivo: File) {
  const formData = new FormData();
  formData.append("archivo", archivo);
  const res = await fetch(`${API_URL}/archivos`, {
    method: "POST",
    credentials: "include",
    body: formData
  });
  return res.json();
}

/**
 * Descarga un archivo por id.
 * @param id ID del archivo a descargar.
 * @returns Blob del archivo.
 */
export async function descargarArchivo(nombre: string) {
  const res = await fetch(`${API_URL}/archivos/descargar/${nombre}`, {
    credentials: "include"
  });
  return res.blob();
}


export async function deleteArchivo(id: number) {
  const res = await fetch(`${API_URL}/archivos/${id}`, {
    method: "DELETE",
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene los archivos asociados a un alumno.
 * @param idAlumno ID del alumno.
 * @returns Array de archivos.
 */
export async function getArchivosPorAlumno(idAlumno: number) {
  const res = await fetch(`${API_URL}/archivos/alumno/${idAlumno}`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene los archivos asociados a un alumno.
 * @param idAlumno ID del alumno.
 * @returns Array de archivos.
 */
export async function getArchivos() {
  const res = await fetch(`${API_URL}/archivos`, {
    credentials: "include"
  });
  return res.json();
}

/**
 * Obtiene los archivos asociados a un alumno.
 * @param idAlumno ID del alumno.
 * @returns Array de archivos.
 */
export async function getArchivosById(id: number) {
  const res = await fetch(`${API_URL}/archivos/${id}`, {
    credentials: "include"
  });
  return res.json();
}

