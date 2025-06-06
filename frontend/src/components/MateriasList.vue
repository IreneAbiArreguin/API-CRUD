<template>
  <div class="materias-container">
    <div class="materias-header">
      <h2 class="materias-title">
        <i class="bi bi-people-fill me-2"></i>Materias
      </h2>
      <button class="btn btn-agregar-header" @click="abrirModalAgregar">
        <i class="bi bi-person-plus-fill me-2"></i>
        <span>Agregar materia</span>
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
            placeholder="Buscar materia..." 
            v-model="busqueda"
          >
        </div>
      </div>
      
      <button class="btn btn-reporte" @click="generarReporte">
        <i class="bi bi-file-earmark-text me-2"></i>
        <span>Generar reporte</span>
      </button>
    </div>
    
    <div class="materias-content">
      <div v-if="materiasFiltrados.length > 0" class="materias-grid">
        <div v-for="materia in materiasFiltrados" :key="materia.id" class="materia-card">
          <div class="materia-avatar">
            {{ obtenerIniciales(materia.nombre) }}
          </div>
          <div class="materia-info">
            <h4 class="materia-nombre">{{ materia.nombre }}</h4>
          </div>
          <div class="materia-acciones">
            <button class="btn btn-icon btn-ver">
              <i class="bi bi-eye-fill"></i>
            </button>
            <button class="btn btn-icon btn-editar" @click="abrirModalEditar(materia)">
              <i class="bi bi-pencil-fill"></i>
            </button>
            <button class="btn btn-icon btn-eliminar" @click="abrirModalEliminar(materia)">
              <i class="bi bi-trash-fill"></i>
            </button>
          </div>
        </div>
      </div>
      
      <div v-else class="estado-vacio">
        <div class="vacio-ilustracion">
          <i class="bi bi-people"></i>
        </div>
        <h3 class="vacio-titulo">No hay materias registrados</h3>
        <p class="vacio-descripcion">
          Cuando se registren materias, aparecerán en esta lista.
        </p>
        <button class="btn btn-agregar" @click="abrirModalAgregar">
          <i class="bi bi-plus-circle me-2"></i>
          Agregar materia
        </button>
      </div>
    </div>
    
    <!-- Modal para Agregar materia -->
    <div class="modal-backdrop" v-if="modalAgregar" @click="cerrarModalAgregar"></div>
    <div class="modal-container" v-if="modalAgregar">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-person-plus-fill me-2"></i>Agregar materia
          </h3>
          <button class="modal-close" @click="cerrarModalAgregar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="guardarNuevoMateria">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="text" 
                    class="form-control" 
                    id="nombre" 
                    placeholder="Nombre"
                    v-model="nuevoMateria.nombre"
                    required
                  >
                  <label for="nombre">Nombre</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="text" 
                    class="form-control" 
                    id="id_profesor" 
                    placeholder="Id_Profesor"
                    v-model="nuevoMateria.id_profesor"
                    required
                  >
                  <label for="id_profesor">Profesor</label>
                </div>
              </div>
              
              <div class="col-12">
                <div class="form-floating">
                  <textarea 
                    class="form-control" 
                    id="descripcion" 
                    placeholder="Descripción"
                    style="height: 100px"
                    v-model="nuevoMateria.descripcion"
                  ></textarea>
                  <label for="descripcion">Descripción</label>
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
    
    <!-- Modal para Editar materia -->
    <div class="modal-backdrop" v-if="modalEditar" @click="cerrarModalEditar"></div>
    <div class="modal-container" v-if="modalEditar">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>Editar materia
          </h3>
          <button class="modal-close" @click="cerrarModalEditar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="guardarEdicionMateria">
            <div class="row g-3">
                            <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="text" 
                    class="form-control" 
                    id="nombre" 
                    placeholder="Nombre"
                    v-model="nuevoMateria.nombre"
                    required
                  >
                  <label for="nombre">Nombre</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating">
                  <input 
                    type="text" 
                    class="form-control" 
                    id="id_profesor" 
                    placeholder="Id_Profesor"
                    v-model="nuevoMateria.id_profesor"
                    required
                  >
                  <label for="id_profesor">Profesor</label>
                </div>
              </div>
              
              <div class="col-12">
                <div class="form-floating">
                  <textarea 
                    class="form-control" 
                    id="descripcion" 
                    placeholder="Descripción"
                    style="height: 100px"
                    v-model="nuevoMateria.descripcion"
                  ></textarea>
                  <label for="descripcion">Descripción</label>
                </div>
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
          </form>
        </div>
      </div>
    </div>
    
    <!-- Modal para Eliminar materia -->
    <div class="modal-backdrop" v-if="modalEliminar" @click="cerrarModalEliminar"></div>
    <div class="modal-container" v-if="modalEliminar">
      <div class="modal-content modal-sm" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Eliminar materia
          </h3>
          <button class="modal-close" @click="cerrarModalEliminar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <div class="avatar-eliminar">
              {{ materiaEliminando ? obtenerIniciales(materiaEliminando.nombre, materiaEliminando.apellido) : '' }}
            </div>
            <h4 class="mt-3">{{ materiaEliminando ? `${materiaEliminando.nombre} ${materiaEliminando.apellido}` : '' }}</h4>
          </div>
          
          <p class="alert alert-danger">
            ¿Estás seguro de que deseas eliminar a esta materia? Esta acción no se puede deshacer.
          </p>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-cancelar" @click="cerrarModalEliminar">
              Cancelar
            </button>
            <button type="button" class="btn btn-eliminar" @click="confirmarEliminarMateria">
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
// Importa las funciones de la API para materias
import { getMaterias, createMateria, updateMateria, deleteMateria } from '../services/api'

// ===============================
// Obtener informacion de los grupos para llenar el select
// ===============================
const grupos = ref<{ id_grupo: number, nombre: string }[]>([])


// ===============================
// Definición de la interfaz materia
// ===============================
interface Materia {
  id: number
  nombre: string
  descripcion: string
  id_profesor: number
}

// ===============================
// Estado reactivo para la lista de materias y búsqueda
// ===============================
const materias = ref<Materia[]>([])
const busqueda = ref('')

// ===============================
// Estado para los modales y Materia en edición/eliminación
// ===============================
const modalAgregar = ref(false)
const modalEditar = ref(false)
const modalEliminar = ref(false)
const materiaEditando = ref<Materia | null>(null)
const materiaEliminando = ref<Materia | null>(null)

// ===============================
// Estado para el nuevo materia (sin id)
// ===============================
const nuevoMateria = ref<Omit<Materia, 'id'>>({
    nombre: '',
    descripcion: '',
    id_profesor: 0
})

// ===============================
// Estado para alertas visuales
// ===============================
const alerta = ref({
  visible: false,
  mensaje: '',
  tipo: 'exito'
})

// ===============================
// Computed para filtrar materias según la búsqueda
// ===============================
const materiasFiltrados = computed(() => {
  if (!busqueda.value) return materias.value
  const termino = busqueda.value.toLowerCase()
  return materias.value.filter(materia => 
    materia.nombre.toLowerCase().includes(termino)
  )
})

// ===============================
// Obtiene iniciales para el avatar del materia
// ===============================
function obtenerIniciales(nombre: string): string {
  return (nombre.charAt(0) +nombre.charAt(1)).toUpperCase()
}

// ===============================
// Modal: Abrir y cerrar para agregar materia
// ===============================
function abrirModalAgregar() {
  // Reinicia el formulario de nuevo materia
  nuevoMateria.value = {
    nombre: '',
    descripcion: '',
    id_profesor: 0
  }
  modalAgregar.value = true
}

function cerrarModalAgregar() {
  modalAgregar.value = false
}

// ===============================
// Guardar un nuevo materia usando la API
// ===============================
async function guardarNuevoMateria() {
  try {
    const res = await createMateria(nuevoMateria.value)
    if (res.mensaje) {
      mostrarAlerta(res.mensaje, 'exito')
      await cargarMaterias() // Recarga la lista tras agregar
      cerrarModalAgregar()
    } else {
      mostrarAlerta(res.error || 'Error al agregar materia', 'error')
    }
  } catch (error) {
    mostrarAlerta('Error al agregar materia', 'error')
  }
}

// ===============================
// Modal: Abrir y cerrar para editar materia
// ===============================
function abrirModalEditar(materia: Materia) {
  materiaEditando.value = { ...materia } // Clona el materia
  modalEditar.value = true
}

function cerrarModalEditar() {
  modalEditar.value = false
  materiaEditando.value = null
}

// ===============================
// Guardar los cambios de un materia usando la API
// ===============================
async function guardarEdicionMateria() {
  if (materiaEditando.value) {
    try {
      const res = await updateMateria(materiaEditando.value.id, materiaEditando.value)
      if (res.mensaje) {
        mostrarAlerta(res.mensaje, 'exito')
        await cargarMaterias() // Recarga la lista tras editar
        cerrarModalEditar()
      } else {
        mostrarAlerta(res.error || 'Error al actualizar materia', 'error')
      }
    } catch (error) {
      mostrarAlerta('Error al actualizar materia', 'error')
    }
  }
}

// ===============================
// Modal: Abrir y cerrar para eliminar materia
// ===============================
function abrirModalEliminar(materia: Materia) {
  materiaEliminando.value = materia
  modalEliminar.value = true
}

function cerrarModalEliminar() {
  modalEliminar.value = false
  materiaEliminando.value = null
}

// ===============================
// Confirmar y eliminar un materia usando la API
// ===============================
async function confirmarEliminarMateria() {
  if (materiaEliminando.value) {
    try {
      const res = await deleteMateria(materiaEliminando.value.id)
      if (res.mensaje) {
        mostrarAlerta(res.mensaje, 'exito')
        await cargarMaterias() // Recarga la lista tras eliminar
        cerrarModalEliminar()
      } else {
        mostrarAlerta(res.error || 'Error al eliminar materia', 'error')
      }
    } catch (error) {
      mostrarAlerta('Error al eliminar materia', 'error')
    }
  }
}

// ===============================
// Cargar la lista de materias desde la API
// ===============================
async function cargarMaterias() {
  try {
    const data = await getMaterias()
    materias.value = data || []
  } catch (error) {
    mostrarAlerta('Error al cargar los materias', 'error')
  }
}

// ===============================
// Función para generar reporte (placeholder)
// ===============================
async function generarReporte() {
  try {
    const response = await fetch('http://localhost/materias/reporte/pdf', {
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
  await cargarMaterias()
})


</script>

<style scoped>
.materias-container {
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  padding: 30px;
  margin: 20px 0;
  position: relative;
}

.materias-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.materias-title {
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

.materias-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.materia-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  align-items: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s;
  border: 1px solid #f0f0f0;
}

.materia-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border-color: #e0e0e0;
}

.materia-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.2rem;
  margin-right: 15px;
}

.materia-info {
  flex: 1;
}

.materia-nombre {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0 0 5px 0;
  color: #333;
}

.materia-id {
  font-size: 0.9rem;
  color: #666;
  margin: 0;
}

.materia-acciones {
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
  .materias-header {
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
  
  .materias-grid {
    grid-template-columns: 1fr;
  }
  
  .modal-content {
    max-height: 85vh;
  }
}
</style>