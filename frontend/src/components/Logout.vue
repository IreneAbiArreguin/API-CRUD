<template>
  <button @click="handleLogout" class="btn-logout">
    <span class="btn-text">Cerrar sesión</span>
    <i class="bi bi-box-arrow-right ms-2"></i>
  </button>
  <Alerta v-if="mensaje" :mensaje="mensaje" tipo="exito" :tiempo="2000" />
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { logout } from '../services/api'
import Alerta from './Alerta.vue'
import { setAuthenticated } from '../authState'

const mensaje = ref('')
const router = useRouter()

async function handleLogout() {
  const res = await logout()
  if (res.mensaje) {
    mensaje.value = res.mensaje // Muestra el mensaje del backend
    localStorage.removeItem('usuario_autenticado')
    setAuthenticated(false)
    setTimeout(() => {
      mensaje.value = ''
      router.push('/')
    }, 1500)
  }
}
</script>

<style scoped>
.btn-logout {
  background: transparent;
  color: #dc3545;
  border: 2px solid #dc3545;
  border-radius: 10px;
  padding: 10px 20px;
  font-weight: 600;
  display: flex;
  align-items: center;
  transition: all 0.3s;
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.btn-logout::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 0%;
  height: 100%;
  background: #dc3545;
  transition: all 0.3s;
  z-index: -1;
}

.btn-logout:hover {
  color: white;
}

.btn-logout:hover::before {
  width: 100%;
}

.btn-logout i {
  transition: transform 0.3s;
}

.btn-logout:hover i {
  transform: translateX(5px);
}
</style>