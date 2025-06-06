<template>
  <div class="calificaciones-container">
    <div class="calificaciones-header">
      <h2 class="calificaciones-title">
        <i class="bi bi-people-fill me-2"></i>Calificaciones
      </h2>
      <button class="btn btn-agregar-header" @click="abrirModalAgregar">
        <i class="bi bi-person-plus-fill me-2"></i>
        <span>Agregar calificacion</span>
      </button>
    </div>
    
    <div class="search-actions-container">
      <div class="search-container">
        <div class="input-group">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input 
            type="text" 
            class="form-control" 
            placeholder="Buscar calificacion..." 
            v-model="busqueda"
          >
        </div>
      </div>
      
      <button class="btn btn-reporte" @click="generarReporte">
        <i class="bi bi-file-earmark-text me-2"></i>
        <span>Generar reporte</span>
      </button>
    </div>
    
    <div class="calificaciones-content">
      <div v-if="calificacionesFiltrados.length > 0" class="calificaciones-grid">
        <div v-for="calificacion in calificacionesFiltrados" :key="calificacion.id" class="calificacion-card">
          <div class="calificacion-avatar">
            {{ obtenerIniciales(calificacion.calificacion) }}
          </div>
          <div class="calificacion-info">
            <h4 class="calificacion-nombre">Alumno: {{ obtenerNombreAlumno(calificacion.id_alumno) }}</h4>
            <p class="calificacion-id">Materia: {{ obtenerNombreMateria(calificacion.id_materia) }}</p>
          </div>
          <div class="calificacion-acciones">
            <button class="btn btn-icon btn-ver">
              <i class="bi bi-eye-fill"></i>
            </button>
            <button class="btn btn-icon btn-editar" @click="abrirModalEditar(calificacion)">
              <i class="bi bi-pencil-fill"></i>
            </button>
            <button class="btn btn-icon btn-eliminar" @click="abrirModalEliminar(calificacion)">
              <i class="bi bi-trash-fill"></i>
            </button>
          </div>
        </div>
      </div>
      
      <div v-else class="estado-vacio">
        <div class="vacio-ilustracion">
          <i class="bi bi-people"></i>
        </div>
        <h3 class="vacio-titulo">No hay calificaciones registrados</h3>
        <p class="vacio-descripcion">
          Cuando se registren calificaciones, aparecerán en esta lista.
        </p>
        <button class="btn btn-agregar" @click="abrirModalAgregar">
          <i class="bi bi-plus-circle me-2"></i>
          Agregar calificacion
        </button>
      </div>
    </div>
    
    <!-- Modal para Agregar Calificaciones -->
    <div class="modal-backdrop" v-if="modalAgregar" @click="cerrarModalAgregar"></div>
    <div class="modal-container" v-if="modalAgregar">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-person-plus-fill me-2"></i>Agregar Calificaciones
          </h3>
          <button class="modal-close" @click="cerrarModalAgregar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="guardarNuevoCalificaciones">
            <div class="row g-3">

              <div class="col-12">
                <div class="form-floating">
                    <select 
                      class="form-select" 
                      id="alumno" 
                      v-model="nuevoCalificaciones.id_alumno"
                      required
                    >
                      <option value="" disabled selected>Selecciona un alumno</option>
                      <option v-for="alumno in alumnos" :key="alumno.id" :value="alumno.id">
                        {{ alumno.nombre }}
                      </option>
                    </select>
                    <label for="alumno">Alumno</label>
                </div>
              </div>

              <div class="col-12">
                <div class="form-floating">
                    <select 
                      class="form-select" 
                      id="materia" 
                      v-model="nuevoCalificaciones.id_materia"
                      required
                    >
                      <option value="" disabled selected>Selecciona una materia</option>
                      <option v-for="materia in materias" :key="materia.id" :value="materia.id">
                        {{ materia.nombre }}
                      </option>
                    </select>
                    <label for="materia">Materia</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="date" 
                    class="form-control" 
                    id="fecha" 
                    placeholder="Fecha"
                    v-model="nuevoCalificaciones.fecha"
                    required
                  >
                  <label for="fecha">Fecha de calificacion</label>
                </div>
              </div>

              
              <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="integer" 
                    class="form-control" 
                    id="calificacion" 
                    placeholder="Calificacion"
                    v-model="nuevoCalificaciones.calificacion"
                    required
                  >
                  <label for="calificacion">Calificacion</label>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-cancelar" @click="cerrarModalAgregar">
                Cancelar
              </button>
              <button type="submit" class="btn btn-guardar">
                <i class="bi bi-save me-2"></i>Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Modal para Editar Calificaciones -->
    <div class="modal-backdrop" v-if="modalEditar" @click="cerrarModalEditar"></div>
    <div class="modal-container" v-if="modalEditar && calificacionEditando">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>Editar Calificaciones
          </h3>
          <button class="modal-close" @click="cerrarModalEditar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="guardarEdicionCalificaciones">
            <div class="row g-3">

              <div class="col-12">
                <div class="form-floating">
                    <select 
                      class="form-select" 
                      id="edit-alumno" 
                      v-model="calificacionEditando.id_alumno"
                      required
                    >
                      <option value="" disabled selected>Selecciona un alumno</option>
                      <option v-for="alumno in alumnos" :key="alumno.id" :value="alumno.id">
                        {{ alumno.nombre }}
                      </option>
                    </select>
                    <label for="edit-alumno">Alumno</label>
                </div>
              </div>

              <div class="col-12">
                <div class="form-floating">
                    <select 
                      class="form-select" 
                      id="edit-materia" 
                      v-model="calificacionEditando.id_materia"
                      required
                    >
                      <option value="" disabled selected>Selecciona una materia</option>
                      <option v-for="materia in materias" :key="materia.id" :value="materia.id">
                        {{ materia.nombre }}
                      </option>
                    </select>
                    <label for="edit-materia">Materia</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="date" 
                    class="form-control" 
                    id="edit-fecha" 
                    placeholder="Fecha"
                    v-model="calificacionEditando.fecha"
                    required
                  >
                  <label for="edit-fecha">Fecha de calificacion</label>
                </div>
              </div>

              
              <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="integer" 
                    class="form-control" 
                    id="edit-calificacion" 
                    placeholder="Calificacion"
                    v-model="calificacionEditando.calificacion"
                    required
                  >
                  <label for="edit-calificacion">Calificacion</label>
                </div>
              </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-cancelar" @click="cerrarModalEditar">
                Cancelar
              </button>
              <button type="submit" class="btn btn-guardar">
                <i class="bi bi-save me-2"></i>Guardar cambios
              </button>
            </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Modal para Eliminar Calificaciones -->
    <div class="modal-backdrop" v-if="modalEliminar" @click="cerrarModalEliminar"></div>
    <div class="modal-container" v-if="modalEliminar">
      <div class="modal-content modal-sm" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Eliminar Calificaciones
          </h3>
          <button class="modal-close" @click="cerrarModalEliminar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          
          <p class="alert alert-danger">
            ¿Estás seguro de que deseas eliminar a este calificacion? Esta acción no se puede deshacer.
          </p>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-cancelar" @click="cerrarModalEliminar">
              Cancelar
            </button>
            <button type="button" class="btn btn-eliminar" @click="confirmarEliminarCalificaciones">
              <i class="bi bi-trash me-2"></i>Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Alerta para mensajes -->
    <div class="alerta-container" v-if="alerta.visible">
      <div class="alerta" :class="alerta.tipo">
        <div class="alerta-icono">
          <i :class="alerta.tipo === 'exito' ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-triangle-fill'"></i>
        </div>
        <div class="alerta-mensaje">{{ alerta.mensaje }}</div>
        <button class="alerta-cerrar" @click="alerta.visible = false">
          <i class="bi bi-x"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
// Importa las funciones de la API para calificaciones
import { getCalificaciones, createCalificacion, updateCalificacion, deleteCalificacion, getMaterias, getAlumnos } from '../services/api'
import { usuarioId } from '../authState'
// ===============================
// Obtener informacion de los grupos para llenar el select
// ===============================
const alumnos = ref<{ id: number, nombre: string, apellido: string }[]>([])
const materias = ref<{ id: number, nombre: string, descripcion: string }[]>([])


// ===============================
// Definición de la interfaz Calificaciones
// ===============================
interface Calificaciones {
  id: number
  id_alumno: number
  id_materia: number
  calificacion: number
  fecha: string
  id_profesor: number
}

// ===============================
// Estado reactivo para la lista de calificaciones y búsqueda
// ===============================
const calificaciones = ref<Calificaciones[]>([])
const busqueda = ref('')

// ===============================
// Estado para los modales y calificacion en edición/eliminación
// ===============================
const modalAgregar = ref(false)
const modalEditar = ref(false)
const modalEliminar = ref(false)
const calificacionEditando = ref<Calificaciones | null>(null)
const calificacionEliminando = ref<Calificaciones | null>(null)

// ===============================
// Estado para el nuevo calificacion (sin id)
// ===============================
const nuevoCalificaciones = ref<Omit<Calificaciones, 'id'>>({
  id_alumno: 0,
  id_materia: 0,
  calificacion: 0,
  fecha: '',
  id_profesor: Number(usuarioId.value)
})

// ===============================
// Estado para alertas visuales
// ===============================
const alerta = ref({
  visible: false,
  mensaje: '',
  tipo: 'exito'
})

// Devuelve el nombre completo del alumno dado su id
function obtenerNombreAlumno(id_alumno: number): string {
  const alumno = alumnos.value.find(a => a.id === id_alumno)
  return alumno ? `${alumno.nombre} ${alumno.apellido}` : 'Desconocido'
}

// Devuelve el nombre de la materia dado su id
function obtenerNombreMateria(id_materia: number): string {
  const materia = materias.value.find(m => m.id === id_materia)
  return materia ? materia.nombre : 'Desconocida'
}

// ===============================
// Computed para filtrar calificaciones según la búsqueda
// ===============================
const calificacionesFiltrados = computed(() => {
  if (!busqueda.value) return calificaciones.value
  const termino = busqueda.value.toLowerCase()
  return calificaciones.value.filter(calificacion => 
    calificacion.calificacion.toString().includes(termino) ||
    calificacion.fecha.includes(termino)
  )
})

// ===============================
// Obtiene iniciales para el avatar del calificacion
// ===============================
function obtenerIniciales(calificacion: number): number {
  return (calificacion)
}

// ===============================
// Modal: Abrir y cerrar para agregar calificacion
// ===============================
function abrirModalAgregar() {
  // Reinicia el formulario de nuevo calificacion
  nuevoCalificaciones.value = {
  id_alumno: 0,
  id_materia: 0,
  calificacion: 0,
  fecha: '',
  id_profesor: Number(usuarioId.value)
  }
  modalAgregar.value = true
}

function cerrarModalAgregar() {
  modalAgregar.value = false
}

// ===============================
// Guardar un nuevo calificacion usando la API
// ===============================
async function guardarNuevoCalificaciones() {
  try {
    console.log(nuevoCalificaciones.value)
    const res = await createCalificacion(nuevoCalificaciones.value)
    if (res.mensaje) {
      mostrarAlerta(res.mensaje, 'exito')
      await cargarCalificaciones() // Recarga la lista tras agregar
      cerrarModalAgregar()
    } else {
      mostrarAlerta(res.error || 'Error al agregar calificacion', 'error')
    }
  } catch (error) {
    mostrarAlerta('Error al agregar calificacion', 'error')
  }
}

// ===============================
// Modal: Abrir y cerrar para editar calificacion
// ===============================
function abrirModalEditar(calificacion: Calificaciones) {
  calificacionEditando.value = { ...calificacion } // Clona el calificacion
  modalEditar.value = true
}

function cerrarModalEditar() {
  modalEditar.value = false
  calificacionEditando.value = null
}

// ===============================
// Guardar los cambios de un calificacion usando la API
// ===============================
async function guardarEdicionCalificaciones() {
  if (calificacionEditando.value) {
    try {
      const res = await updateCalificacion(calificacionEditando.value.id, calificacionEditando.value)
      if (res.mensaje) {
        mostrarAlerta(res.mensaje, 'exito')
        await cargarCalificaciones() // Recarga la lista tras editar
        cerrarModalEditar()
      } else {
        mostrarAlerta(res.error || 'Error al actualizar calificacion', 'error')
      }
    } catch (error) {
      mostrarAlerta('Error al actualizar calificacion', 'error')
    }
  }
}

// ===============================
// Modal: Abrir y cerrar para eliminar calificacion
// ===============================
function abrirModalEliminar(calificacion: Calificaciones) {
  calificacionEliminando.value = calificacion
  modalEliminar.value = true
}

function cerrarModalEliminar() {
  modalEliminar.value = false
  calificacionEliminando.value = null
}

// ===============================
// Confirmar y eliminar un calificacion usando la API
// ===============================
async function confirmarEliminarCalificaciones() {
  if (calificacionEliminando.value) {
    try {
      const res = await deleteCalificacion(calificacionEliminando.value.id)
      if (res.mensaje) {
        mostrarAlerta(res.mensaje, 'exito')
        await cargarCalificaciones() // Recarga la lista tras eliminar
        cerrarModalEliminar()
      } else {
        mostrarAlerta(res.error || 'Error al eliminar calificacion', 'error')
      }
    } catch (error) {
      mostrarAlerta('Error al eliminar calificacion', 'error')
    }
  }
}

// ===============================
// Cargar la lista de calificaciones desde la API
// ===============================
async function cargarCalificaciones() {
  try {
    const data = await getCalificaciones()
    calificaciones.value = data || []
  } catch (error) {
    mostrarAlerta('Error al cargar los calificaciones', 'error')
  }
}

// ===============================
// Función para generar reporte (placeholder)
// ===============================
async function generarReporte() {
  try {
    const response = await fetch('http://localhost/calificaciones/reporte/pdf', {
      credentials: 'include'
    })
    if (!response.ok) throw new Error('No se pudo generar el reporte')
    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    window.open(url, '_blank')
    mostrarAlerta('Reporte generado', 'exito')
    // Opcional: liberar el objeto URL después de un tiempo
    setTimeout(() => window.URL.revokeObjectURL(url), 10000)
  } catch (error) {
    mostrarAlerta('Error al generar el reporte', 'error')
  }
}

// ===============================
// Función para mostrar alertas visuales
// ===============================
function mostrarAlerta(mensaje: string, tipo: 'exito' | 'error') {
  alerta.value = {
    visible: true,
    mensaje,
    tipo
  }
  // Oculta la alerta después de 3 segundos
  setTimeout(() => {
    alerta.value.visible = false
  }, 3000)
}

// ===============================
// Cargar los elementos al montar el componente
// ===============================
onMounted(async () => {
  await cargarCalificaciones()
  await cargarAlumnos()
  await cargarMaterias()
})

async function cargarAlumnos() {
  try {
    const data = await getAlumnos()
    alumnos.value = data || []
  } catch (error) {
    mostrarAlerta('Error al cargar los alumnos', 'error')
  }
}

async function cargarMaterias() {
  try {
    const data = await getMaterias()
    materias.value = data || []
  } catch (error) {
    mostrarAlerta('Error al cargar las materias', 'error')
  }
}
</script>

<style scoped>
.calificaciones-container {
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  padding: 30px;
  margin: 20px 0;
  position: relative;
}

.calificaciones-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.calificaciones-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #333;
  margin: 0;
  display: flex;
  align-items: center;
}

.btn-agregar-header {
  background: linear-gradient(to right, #11998e, #38ef7d);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-weight: 600;
  display: flex;
  align-items: center;
  transition: all 0.3s;
}

.btn-agregar-header:hover {
  background: linear-gradient(to right, #0e8a7e, #32d56f);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(56, 239, 125, 0.3);
}

.search-actions-container {
  display: flex;
  gap: 15px;
  margin-bottom: 25px;
  align-items: center;
}

.search-container {
  flex: 1;
}

.search-container .input-group {
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.search-container .input-group-text {
  background: white;
  border: 1px solid #e1e1e1;
  border-right: none;
  padding-left: 15px;
}

.search-container .form-control {
  border: 1px solid #e1e1e1;
  padding: 12px 20px;
  font-size: 1rem;
}

.search-container .form-control:focus {
  box-shadow: none;
  border-color: #0072ff;
}

.btn-reporte {
  background: linear-gradient(to right, #00c6ff, #0072ff);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-weight: 600;
  display: flex;
  align-items: center;
  transition: all 0.3s;
  white-space: nowrap;
}

.btn-reporte:hover {
  background: linear-gradient(to right, #00b4e6, #0065e0);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 114, 255, 0.3);
}

.calificaciones-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.calificacion-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  align-items: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s;
  border: 1px solid #f0f0f0;
}

.calificacion-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border-color: #e0e0e0;
}

.calificacion-avatar {
  width: 75px;
  height: 50px;
  border-radius: 20%;
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.2rem;
  margin-right: 15px;
}

.calificacion-info {
  flex: 1;
}

.calificacion-nombre {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0 0 5px 0;
  color: #333;
}

.calificacion-id {
  font-size: 0.9rem;
  color: #666;
  margin: 0;
}

.calificacion-acciones {
  display: flex;
  gap: 8px;
}

.btn-icon {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  transition: all 0.2s;
}

.btn-ver {
  background: #e3f2fd;
  color: #0d6efd;
}

.btn-ver:hover {
  background: #0d6efd;
  color: white;
}

.btn-editar {
  background: #fff8e1;
  color: #ffc107;
}

.btn-editar:hover {
  background: #ffc107;
  color: white;
}

.btn-eliminar {
  background: #ffebee;
  color: #dc3545;
}

.btn-eliminar:hover {
  background: #dc3545;
  color: white;
}

.estado-vacio {
  text-align: center;
  padding: 50px 20px;
  background: #f9f9f9;
  border-radius: 15px;
}

.vacio-ilustracion {
  font-size: 5rem;
  color: #ccc;
  margin-bottom: 20px;
}

.vacio-titulo {
  font-size: 1.5rem;
  color: #555;
  margin-bottom: 10px;
}

.vacio-descripcion {
  color: #777;
  margin-bottom: 25px;
}

.btn-agregar {
  background: linear-gradient(to right, #11998e, #38ef7d);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 25px;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-agregar:hover {
  background: linear-gradient(to right, #0e8a7e, #32d56f);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(56, 239, 125, 0.3);
}

/* Estilos para los modales */
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.modal-container {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1001;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 20px;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
  animation: slideIn 0.3s ease;
}

.modal-content.modal-sm {
  max-width: 400px;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #eee;
}

.modal-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0;
  display: flex;
  align-items: center;
}

.modal-close {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  color: #666;
  cursor: pointer;
  transition: color 0.3s;
}

.modal-close:hover {
  color: #dc3545;
}

.modal-body {
  padding: 20px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 20px;
  border-top: 1px solid #eee;
}

.btn-cancelar {
  background: #f0f0f0;
  color: #333;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-cancelar:hover {
  background: #e0e0e0;
}

.btn-guardar {
  background: linear-gradient(to right, #6a11cb, #2575fc);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-weight: 600;
  transition: all 0.3s;
  display: flex;
  align-items: center;
}

.btn-guardar:hover {
  background: linear-gradient(to right, #5a0cb0, #1a65e6);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(37, 117, 252, 0.4);
}

.btn-eliminar {
  background: linear-gradient(to right, #ff416c, #ff4b2b);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-weight: 600;
  transition: all 0.3s;
  display: flex;
  align-items: center;
}

.btn-eliminar:hover {
  background: linear-gradient(to right, #e03a61, #e64426);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(255, 75, 43, 0.4);
}

.avatar-eliminar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 2rem;
  margin: 0 auto;
}

/* Estilos para las alertas */
.alerta-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1100;
  animation: slideUp 0.3s ease;
}

.alerta {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  border-radius: 10px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
  min-width: 300px;
  max-width: 400px;
}

.alerta.exito {
  background: linear-gradient(to right, rgba(17, 153, 142, 0.95), rgba(56, 239, 125, 0.95));
  color: white;
}

.alerta.error {
  background: linear-gradient(to right, rgba(255, 65, 108, 0.95), rgba(255, 75, 43, 0.95));
  color: white;
}

.alerta-icono {
  font-size: 1.5rem;
  margin-right: 15px;
}

.alerta-mensaje {
  flex: 1;
  font-weight: 500;
}

.alerta-cerrar {
  background: transparent;
  border: none;
  color: white;
  opacity: 0.7;
  cursor: pointer;
  transition: opacity 0.3s;
}

.alerta-cerrar:hover {
  opacity: 1;
}

/* Animaciones */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { transform: translateY(50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes slideUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

/* Estilos para los formularios */
.form-floating {
  margin-bottom: 15px;
}

.form-control, .form-select {
  border-radius: 10px;
  border: 1px solid #e1e1e1;
  padding: 12px 20px;
  height: 58px;
  transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
  border-color: #2575fc;
  box-shadow: 0 0 0 0.25rem rgba(37, 117, 252, 0.25);
}

.form-floating label {
  padding-left: 15px;
  color: #666;
}

textarea.form-control {
  min-height: 100px;
}

/* Responsive */
@media (max-width: 768px) {
  .calificaciones-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .btn-agregar-header {
    width: 100%;
    justify-content: center;
  }
  
  .search-actions-container {
    flex-direction: column;
  }
  
  .btn-reporte {
    width: 100%;
    justify-content: center;
  }
  
  .calificaciones-grid {
    grid-template-columns: 1fr;
  }
  
  .modal-content {
    max-height: 85vh;
  }
}
</style>