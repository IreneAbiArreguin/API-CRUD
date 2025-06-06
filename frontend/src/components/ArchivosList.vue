<template>
  <div class="archivos-container">
    <div class="archivos-header">
      <h2 class="archivos-title">
        <i class="bi bi-file-earmark-fill me-2"></i>Archivos
      </h2>
      <button class="btn btn-agregar-header" @click="abrirModalAgregar">
        <i class="bi bi-cloud-upload-fill me-2"></i>
        <span>Subir archivo</span>
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
            placeholder="Buscar archivo..." 
            v-model="busqueda"
          >
        </div>
      </div>
      
      <div class="filter-container">
        <select class="form-select" v-model="filtroTipo">
          <option value="">Todos los tipos</option>
          <option value="1">Imágenes</option>
          <option value="2">PDF</option>
        </select>
      </div>
    </div>
    
    <div class="archivos-content">
      <div v-if="archivosFiltrados.length > 0" class="archivos-grid">
        <div v-for="archivo in archivosFiltrados" :key="archivo.id" class="archivo-card">
          <div class="archivo-icon">
            <i :class="obtenerIconoArchivo(archivo.categoria)"></i>
          </div>
          <div class="archivo-info">
            <h4 class="archivo-nombre">{{ archivo.nombre }}</h4>
            <p class="archivo-meta">
              <span class="archivo-tipo">{{ obtenerNombreTipo(archivo.categoria) }}</span>
              <span class="archivo-fecha" v-if="archivo.fecha_subida">
                <i class="bi bi-calendar3 me-1"></i>
                {{ formatearFecha(archivo.fecha_subida) }}
              </span>
            </p>
            <div class="archivo-acciones">
              <button class="btn btn-sm btn-descargar" @click="descargarArchivo(archivo)" title="Descargar">
                <i class="bi bi-download me-1"></i>Descargar
              </button>
              <button class="btn btn-sm btn-eliminar" @click="abrirModalEliminar(archivo)" title="Eliminar">
                <i class="bi bi-trash me-1"></i>Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="estado-vacio">
        <div class="vacio-ilustracion">
          <i class="bi bi-file-earmark-x"></i>
        </div>
        <h3 class="vacio-titulo">No hay archivos disponibles</h3>
        <p class="vacio-descripcion">
          {{ busqueda || filtroTipo ? 'No se encontraron archivos con los criterios de búsqueda.' : 'Sube archivos para comenzar a gestionar tu biblioteca digital.' }}
        </p>
        <button class="btn btn-agregar" @click="abrirModalAgregar">
          <i class="bi bi-cloud-upload me-2"></i>
          Subir archivo
        </button>
      </div>
    </div>
    
    <!-- Modal para Subir Archivo -->
    <div class="modal-backdrop" v-if="modalAgregar" @click="cerrarModalAgregar"></div>
    <div class="modal-container" v-if="modalAgregar">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-cloud-upload-fill me-2"></i>Subir Archivo
          </h3>
          <button class="modal-close" @click="cerrarModalAgregar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="subirNuevoArchivo" ref="uploadForm">
            <div class="upload-area" 
                 @dragover.prevent="highlightDropZone = true"
                 @dragleave.prevent="highlightDropZone = false"
                 @drop.prevent="onFileDrop"
                 :class="{ 'highlight': highlightDropZone }">
              <div v-if="!archivoSeleccionado">
                <i class="bi bi-cloud-arrow-up-fill"></i>
                <p>Arrastra y suelta archivos aquí o</p>
                <label for="file-upload" class="btn btn-outline-primary">
                  Seleccionar archivo
                </label>
                <input 
                  type="file" 
                  id="file-upload"
                  name="archivo"
                  @change="onFileSelected" 
                  style="display: none"
                  accept=".jpg,.jpeg,.png,.pdf"
                >
                <p class="upload-info mt-3">
                  <small>Formatos permitidos: JPG, PNG, PDF</small><br>
                  <small>Tamaño máximo: 5MB</small>
                </p>
              </div>
              <div v-else class="selected-file">
                <div class="file-preview">
                  <i :class="obtenerIconoArchivo(obtenerCategoriaArchivo(archivoSeleccionado.type))"></i>
                </div>
                <div class="file-info">
                  <h5>{{ archivoSeleccionado.name }}</h5>
                  <p>{{ formatearTamano(archivoSeleccionado.size) }}</p>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" @click="archivoSeleccionado = null">
                  <i class="bi bi-x"></i>
                </button>
              </div>
            </div>
            
            <div class="mb-3 mt-4">
              <div class="form-floating">
                <select 
                  class="form-select" 
                  id="categoria" 
                  name="categoria"
                  v-model="nuevoArchivo.categoria"
                  required
                >
                  <option value="" disabled selected>Selecciona una categoría</option>
                  <option value="1">Imágenes</option>
                  <option value="2">PDF</option>
                </select>
                <label for="categoria">Categoría</label>
              </div>
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-cancelar" @click="cerrarModalAgregar">
                Cancelar
              </button>
              <button type="submit" class="btn btn-guardar" :disabled="!archivoSeleccionado">
                <i class="bi bi-cloud-upload me-2"></i>Subir archivo
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Modal para Eliminar Archivo -->
    <div class="modal-backdrop" v-if="modalEliminar" @click="cerrarModalEliminar"></div>
    <div class="modal-container" v-if="modalEliminar">
      <div class="modal-content modal-sm" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Eliminar Archivo
          </h3>
          <button class="modal-close" @click="cerrarModalEliminar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <div class="archivo-preview-eliminar">
              <i :class="archivoEliminando ? obtenerIconoArchivo(archivoEliminando.categoria) : ''"></i>
            </div>
            <h4 class="mt-3">{{ archivoEliminando ? archivoEliminando.nombre : '' }}</h4>
          </div>
          
          <p class="alert alert-danger">
            ¿Estás seguro de que deseas eliminar este archivo? Esta acción no se puede deshacer.
          </p>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-cancelar" @click="cerrarModalEliminar">
              Cancelar
            </button>
            <button type="button" class="btn btn-eliminar" @click="confirmarEliminarArchivo">
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
// Importa las funciones de la API para archivos
import { subirArchivo, deleteArchivo, getArchivos } from '../services/api'

// ===============================
// Definición de la interfaz Archivo
// ===============================
interface Archivo {
  id: number
  nombre: string
  categoria: number // 1: imagen, 2: PDF
  fecha_subida?: string
  usuario_id?: number
  alumno_id?: number | null
}

// ===============================
// Estado reactivo para la lista de archivos y búsqueda
// ===============================
const archivos = ref<Archivo[]>([])
const busqueda = ref('')
const filtroTipo = ref('')

// ===============================
// Estado para los modales y archivo en edición/eliminación
// ===============================
const modalAgregar = ref(false)
const modalEliminar = ref(false)
const archivoEliminando = ref<Archivo | null>(null)
const highlightDropZone = ref(false)
const archivoSeleccionado = ref<File | null>(null)
const uploadForm = ref<HTMLFormElement | null>(null)

// ===============================
// Estado para el nuevo archivo
// ===============================
const nuevoArchivo = ref({
  categoria: ''
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
// Computed para filtrar archivos según la búsqueda y tipo
// ===============================
const archivosFiltrados = computed(() => {
  let resultado = archivos.value

  // Filtrar por término de búsqueda
  if (busqueda.value) {
    const termino = busqueda.value.toLowerCase()
    resultado = resultado.filter(archivo => 
      archivo.nombre.toLowerCase().includes(termino)
    )
  }
  
  // Filtrar por tipo de archivo
  if (filtroTipo.value) {
    resultado = resultado.filter(archivo => 
      archivo.categoria.toString() === filtroTipo.value
    )
  }
  
  return resultado
})

// ===============================
// Funciones para manejar archivos
// ===============================
function obtenerCategoriaArchivo(tipoMime: string): number {
  if (tipoMime.includes('image/')) {
    return 1 // Imágenes
  } else if (tipoMime === 'application/pdf') {
    return 2 // PDF
  }
  return 0 // Otro
}

function obtenerIconoArchivo(categoria: number): string {
  switch (categoria) {
    case 1: return 'bi bi-file-earmark-image-fill text-info'
    case 2: return 'bi bi-file-earmark-pdf-fill text-danger'
    default: return 'bi bi-file-earmark-fill text-secondary'
  }
}

function obtenerNombreTipo(categoria: number): string {
  switch (categoria) {
    case 1: return 'Imagen'
    case 2: return 'PDF'
    default: return 'Archivo'
  }
}

function formatearTamano(bytes: number): string {
  if (bytes === 0) return '0 Bytes'
  
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

function formatearFecha(fecha: string): string {
  return new Date(fecha).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

// ===============================
// Funciones para manejar eventos de archivos
// ===============================
function onFileSelected(event: Event) {
  const input = event.target as HTMLInputElement
  if (input.files && input.files.length > 0) {
    const file = input.files[0]
    
    // Validar tipo de archivo
    if (!['image/jpeg', 'image/png', 'application/pdf'].includes(file.type)) {
      mostrarAlerta('Tipo de archivo no permitido. Solo se permiten imágenes (JPG, PNG) y PDF.', 'error')
      return
    }
    
    // Validar tamaño máximo (5MB)
    const tamanoMaximo = 5 * 1024 * 1024 // 5MB
    if (file.size > tamanoMaximo) {
      mostrarAlerta('El archivo excede el tamaño máximo permitido (5MB).', 'error')
      return
    }
    
    archivoSeleccionado.value = file
    
    // Establecer categoría automáticamente según el tipo de archivo
    nuevoArchivo.value.categoria = obtenerCategoriaArchivo(file.type).toString()
  }
}

function onFileDrop(event: DragEvent) {
  highlightDropZone.value = false
  if (event.dataTransfer?.files && event.dataTransfer.files.length > 0) {
    const file = event.dataTransfer.files[0]
    
    // Validar tipo de archivo
    if (!['image/jpeg', 'image/png', 'application/pdf'].includes(file.type)) {
      mostrarAlerta('Tipo de archivo no permitido. Solo se permiten imágenes (JPG, PNG) y PDF.', 'error')
      return
    }
    
    // Validar tamaño máximo (5MB)
    const tamanoMaximo = 5 * 1024 * 1024 // 5MB
    if (file.size > tamanoMaximo) {
      mostrarAlerta('El archivo excede el tamaño máximo permitido (5MB).', 'error')
      return
    }
    
    archivoSeleccionado.value = file
    
    // Establecer categoría automáticamente según el tipo de archivo
    nuevoArchivo.value.categoria = obtenerCategoriaArchivo(file.type).toString()
  }
}

// ===============================
// Modal: Abrir y cerrar para agregar archivo
// ===============================
function abrirModalAgregar() {
  // Reinicia el formulario
  nuevoArchivo.value = {
    categoria: ''
  }
  archivoSeleccionado.value = null
  modalAgregar.value = true
}

function cerrarModalAgregar() {
  modalAgregar.value = false
}

// ===============================
// Subir un nuevo archivo usando la API
// ===============================
async function subirNuevoArchivo() {
  if (!archivoSeleccionado.value) {
    mostrarAlerta('Por favor selecciona un archivo', 'error')
    return
  }
  
  try {

    
    // Mostrar mensaje de carga
    mostrarAlerta('Subiendo archivo...', 'exito')
    
    // Enviar el archivo al servidor
    const res = await subirArchivo(archivoSeleccionado.value)
    
    if (res.mensaje) {
      mostrarAlerta(res.mensaje, 'exito')
      await cargarArchivos() // Recargar la lista tras subir
      cerrarModalAgregar()
    } else if (res.error) {
      mostrarAlerta(res.error, 'error')
    }
  } catch (error) {
    mostrarAlerta('Error al subir el archivo', 'error')
    console.error('Error al subir archivo:', error)
  }
}

// ===============================
// Modal: Abrir y cerrar para eliminar archivo
// ===============================
function abrirModalEliminar(archivo: Archivo) {
  archivoEliminando.value = archivo
  modalEliminar.value = true
}

function cerrarModalEliminar() {
  modalEliminar.value = false
  archivoEliminando.value = null
}

// ===============================
// Confirmar y eliminar un archivo usando la API
// ===============================
async function confirmarEliminarArchivo() {
  if (archivoEliminando.value) {
    try {
      // Mostrar mensaje de carga
      mostrarAlerta('Eliminando archivo...', 'exito')
      
      // Enviar solicitud para eliminar el archivo
      const res = await deleteArchivo(archivoEliminando.value.id)
        
      if (res.mensaje) {
        mostrarAlerta(res.mensaje, 'exito')
        await cargarArchivos() // Recargar la lista tras eliminar
        cerrarModalEliminar()
      } else if (res.error) {
        mostrarAlerta(res.error, 'error')
      }
    } catch (error) {
      mostrarAlerta('Error al eliminar el archivo', 'error')
      console.error('Error al eliminar archivo:', error)
    }
  }
}

// ===============================
// Función para descargar un archivo
// ===============================

import { descargarArchivo as descargarArchivoApi } from '../services/api'

// ===============================
// Función para descargar un archivo usando la API
// ===============================
async function descargarArchivo(archivo: Archivo) {
  try {
    mostrarAlerta('Preparando descarga...', 'exito')
    // Llama a la función de la API para obtener el blob
    const blob = await descargarArchivoApi(archivo.nombre)
    // Crea un enlace temporal para descargar el archivo
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = archivo.nombre
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)
    mostrarAlerta('Descarga iniciada', 'exito')
  } catch (error) {
    mostrarAlerta('Error al descargar el archivo', 'error')
    console.error('Error al descargar archivo:', error)
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
// Cargar la lista de archivos al montar el componente
// ===============================
async function cargarArchivos() {
  try {
    const data = await getArchivos()
    archivos.value = data || []
  } catch (error) {
    mostrarAlerta('Error al cargar los archivos', 'error')
    console.error('Error al cargar archivos:', error)
  }
}

onMounted(async () => {
  await cargarArchivos()
})
</script>

<style scoped>
.archivos-container {
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  padding: 30px;
  margin: 20px 0;
  position: relative;
}

.archivos-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.archivos-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #333;
  margin: 0;
  display: flex;
  align-items: center;
}

.btn-agregar-header {
  background: linear-gradient(to right, #4776E6, #8E54E9);
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
  background: linear-gradient(to right, #3A67D7, #7B46DA);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
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
  border-color: #8E54E9;
}

.filter-container {
  width: 180px;
}

.filter-container .form-select {
  border-radius: 10px;
  border: 1px solid #e1e1e1;
  padding: 12px 20px;
  height: 48px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.filter-container .form-select:focus {
  box-shadow: none;
  border-color: #8E54E9;
}

.archivos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.archivo-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  align-items: flex-start;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s;
  border: 1px solid #f0f0f0;
}

.archivo-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border-color: #e0e0e0;
}

.archivo-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  background: #f8f9fa;
  color: #333;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  margin-right: 15px;
  flex-shrink: 0;
}

.archivo-icon i {
  transition: transform 0.3s;
}

.archivo-card:hover .archivo-icon i {
  transform: scale(1.1);
}

.archivo-info {
  flex: 1;
  min-width: 0; /* Para que el texto se trunque correctamente */
}

.archivo-nombre {
  font-size: 1rem;
  font-weight: 600;
  margin: 0 0 10px 0;
  color: #333;
  word-break: break-word;
}

.archivo-meta {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 0 0 15px 0;
  font-size: 0.8rem;
  color: #666;
  flex-wrap: wrap;
}

.archivo-tipo {
  background: #f0f0f0;
  padding: 2px 8px;
  border-radius: 4px;
  font-weight: 500;
}

.archivo-fecha {
  font-size: 0.8rem;
  color: #888;
  display: flex;
  align-items: center;
}

.archivo-acciones {
  display: flex;
  gap: 10px;
  margin-top: 5px;
  flex-wrap: wrap;
}

.btn-descargar {
  background: #e3f2fd;
  color: #0d6efd;
  border: none;
  border-radius: 6px;
  transition: all 0.2s;
}

.btn-descargar:hover {
  background: #0d6efd;
  color: white;
}

.btn-eliminar {
  background: #ffebee;
  color: #dc3545;
  border: none;
  border-radius: 6px;
  transition: all 0.2s;
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
  background: linear-gradient(to right, #4776E6, #8E54E9);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 25px;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-agregar:hover {
  background: linear-gradient(to right, #3A67D7, #7B46DA);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
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
  background: linear-gradient(to right, #4776E6, #8E54E9);
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
  background: linear-gradient(to right, #3A67D7, #7B46DA);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
}

.btn-guardar:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
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

.archivo-preview-eliminar {
  width: 80px;
  height: 80px;
  border-radius: 15px;
  background: #f8f9fa;
  color: #333;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
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
  background: linear-gradient(to right, rgba(71, 118, 230, 0.95), rgba(142, 84, 233, 0.95));
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

/* Estilos para el área de subida de archivos */
.upload-area {
  border: 2px dashed #ccc;
  border-radius: 15px;
  padding: 30px;
  text-align: center;
  transition: all 0.3s;
  background: #f9f9f9;
  cursor: pointer;
}

.upload-area:hover, .upload-area.highlight {
  border-color: #8E54E9;
  background: #f5f0ff;
}

.upload-area i {
  font-size: 3rem;
  color: #8E54E9;
  margin-bottom: 15px;
}

.upload-area p {
  color: #666;
  margin-bottom: 15px;
}

.upload-info {
  color: #888;
}

.selected-file {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 10px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.file-preview {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  background: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}

.file-info {
  flex: 1;
  min-width: 0;
}

.file-info h5 {
  font-size: 1rem;
  margin: 0 0 5px 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.file-info p {
  font-size: 0.8rem;
  color: #666;
  margin: 0;
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
  border-color: #8E54E9;
  box-shadow: 0 0 0 0.25rem rgba(142, 84, 233, 0.25);
}

.form-floating label {
  padding-left: 15px;
  color: #666;
}

/* Responsive */
@media (max-width: 768px) {
  .archivos-header {
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
  
  .filter-container {
    width: 100%;
  }
  
  .archivos-grid {
    grid-template-columns: 1fr;
  }
  
  .modal-content {
    max-height: 85vh;
  }
  
  .archivo-acciones {
    flex-direction: column;
    width: 100%;
  }
  
  .archivo-acciones button {
    width: 100%;
  }
}
</style>