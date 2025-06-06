<template>
  <transition name="fade-slide">
    <!-- Muestra una alerta si 'visible' es true -->
    <div v-if="visible" :class="['alerta-moderna', tipo]">
      <div class="alerta-contenido">
        <div class="alerta-icono">
          <i :class="iconoTipo"></i>
        </div>
        <div class="alerta-mensaje">{{ mensaje }}</div>
      </div>
      <button class="alerta-cerrar" @click="cerrarAlerta">
        <i class="bi bi-x"></i>
      </button>
    </div>
  </transition>
</template>

<script setup lang="ts">
// Componente de alerta reutilizable.
// Props:
// - mensaje: Texto a mostrar.
// - tipo: 'exito' o 'error' (define el color).
// - tiempo: milisegundos que la alerta permanece visible (opcional).

import { ref, watch, computed } from 'vue'

const props = defineProps<{
  mensaje: string
  tipo?: string // 'exito' | 'error'
  tiempo?: number
}>()

const visible = ref(!!props.mensaje)

// Determinar el icono según el tipo de alerta
const iconoTipo = computed(() => {
  switch(props.tipo) {
    case 'exito':
      return 'bi bi-check-circle-fill'
    case 'error':
      return 'bi bi-exclamation-triangle-fill'
    default:
      return 'bi bi-info-circle-fill'
  }
})

// Función para cerrar manualmente la alerta
function cerrarAlerta() {
  visible.value = false
}

watch(() => props.mensaje, (nuevo) => {
  visible.value = !!nuevo
  if (nuevo && props.tiempo) {
    setTimeout(() => visible.value = false, props.tiempo)
  }
})
</script>

<style scoped>
/* Estilos base modernos para la alerta */
.alerta-moderna {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  margin: 16px 0;
  border-radius: 10px;
  font-weight: 500;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
  backdrop-filter: blur(10px);
  border-left: 5px solid;
  animation: pulse 2s infinite;
}

.alerta-contenido {
  display: flex;
  align-items: center;
  gap: 15px;
}

.alerta-icono {
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.alerta-mensaje {
  font-size: 1rem;
  line-height: 1.5;
}

.alerta-cerrar {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  opacity: 0.7;
  transition: opacity 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  margin: 0;
}

.alerta-cerrar:hover {
  opacity: 1;
}

/* Estilos específicos por tipo */
.exito {
  background: linear-gradient(135deg, rgba(212, 237, 218, 0.9), rgba(212, 237, 218, 0.7));
  color: #155724;
  border-left-color: #28a745;
}

.exito .alerta-icono {
  color: #28a745;
}

.exito .alerta-cerrar {
  color: #155724;
}

.error {
  background: linear-gradient(135deg, rgba(248, 215, 218, 0.9), rgba(248, 215, 218, 0.7));
  color: #721c24;
  border-left-color: #dc3545;
}

.error .alerta-icono {
  color: #dc3545;
}

.error .alerta-cerrar {
  color: #721c24;
}

/* Animación de pulso sutil */
@keyframes pulse {
  0% {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }
  50% {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  }
  100% {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }
}

/* Animaciones de entrada/salida */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.5s ease;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-20px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>