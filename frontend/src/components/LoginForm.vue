<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="tabs">
          <button 
            class="tab-btn" 
            :class="{ active: activeTab === 'login' }" 
            @click="activeTab = 'login'"
          >
            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
          </button>
          <button 
            class="tab-btn" 
            :class="{ active: activeTab === 'register' }" 
            @click="activeTab = 'register'"
          >
            <i class="bi bi-person-plus me-2"></i>Registrarse
          </button>
        </div>
      </div>
      
      <transition name="fade" mode="out-in">
        <!-- Formulario de Login -->
        <form v-if="activeTab === 'login'" @submit.prevent="handleLogin" class="login-form">
          <h3 class="form-title">Bienvenido de nuevo</h3>
          <p class="form-subtitle">Ingresa tus credenciales para acceder</p>
          
          <div class="form-floating mb-4">
            <input 
              v-model="usuario" 
              type="text" 
              class="form-control" 
              id="usuario" 
              placeholder="Usuario"
              required
            >
            <label for="usuario">
              <i class="bi bi-person-fill me-2"></i>Usuario
            </label>
          </div>
          
          <div class="form-floating mb-4">
            <input 
              v-model="contrasena" 
              type="password" 
              class="form-control" 
              id="contrasena" 
              placeholder="Contraseña"
              required
            >
            <label for="contrasena">
              <i class="bi bi-lock-fill me-2"></i>Contraseña
            </label>
          </div>
                    
          <div class="d-grid">
            <button type="submit" class="btn btn-login">
              <span class="btn-text">Iniciar sesión</span>
              <i class="bi bi-arrow-right-circle-fill ms-2"></i>
            </button>
          </div>
        </form>
        
        <!-- Formulario de Registro -->
        <form v-else @submit.prevent="handleRegister" class="login-form">
          <h3 class="form-title">Crear una cuenta</h3>
          <p class="form-subtitle">Completa tus datos para registrarte</p>
          
           <div class="form-floating mb-3">
            <input 
              v-model="registerDataUser.nombres" 
              type="text" 
              class="form-control" 
              id="nombre" 
              placeholder="Nombre/s"
              required
            >
            <label for="nombre">
              <i class="bi bi-person-badge me-2"></i>Nombre/s
            </label>
          </div>

          <div class="form-floating mb-3">
            <input 
              v-model="registerDataUser.apellidos" 
              type="text" 
              class="form-control" 
              id="apellido" 
              placeholder="Apellido/s"
              required
            >
            <label for="apellido">
              <i class="bi bi-person-badge me-2"></i>Apellido/s
            </label>
          </div>

          <div class="form-floating mb-3">
            <input 
              v-model="registerDataUser.nombre_usuario" 
              type="text" 
              class="form-control" 
              id="register-usuario" 
              placeholder="Nombre de usuario"
              required
            >
            <label for="register-usuario">
              <i class="bi bi-person-fill me-2"></i>Nombre de usuario
            </label>
          </div>
          
          <div class="form-floating mb-3">
            <input 
              v-model="registerDataUser.correo" 
              type="email" 
              class="form-control" 
              id="email" 
              placeholder="Correo electrónico"
              required
            >
            <label for="email">
              <i class="bi bi-envelope-fill me-2"></i>Correo electrónico
            </label>
          </div>
          

          
          <div class="form-floating mb-3">
            <input 
              v-model="registerDataUser.contrasena" 
              type="password" 
              class="form-control" 
              id="register-contrasena" 
              placeholder="Contraseña"
              required
            >
            <label for="register-contrasena">
              <i class="bi bi-lock-fill me-2"></i>Contraseña
            </label>
          </div>

          <div class="form-floating mb-3">
            <select 
              v-model="registerDataUser.id_especialidad" 
              class="form-control" 
              id="register-especialidad" 
              required
            >
              <option value="" disabled>Selecciona una especialidad</option>
              <option value="1">Especialidad 1</option>
              <option value="2">Especialidad 2</option>
              <option value="3">Especialidad 3</option>
            </select>
            <label for="register-especialidad">
              <i class="bi bi-book-fill me-2"></i>Especialidad
            </label>
          </div>
          
          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terminos" required>
            <label class="form-check-label" for="terminos">
              Acepto los <a href="#" class="terms-link">términos y condiciones</a>
            </label>
          </div>
          
          <div class="d-grid">
            <button type="submit" class="btn btn-register">
              <span class="btn-text">Crear cuenta</span>
              <i class="bi bi-person-check-fill ms-2"></i>
            </button>
          </div>
        </form>
      </transition>
      
      <Alerta v-if="mensaje" :mensaje="mensaje" :tipo="tipoAlerta" :tiempo="3000" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { createUsuario, login } from '../services/api'
import Alerta from './Alerta.vue'
import { setAuthenticated } from '../authState'

const router = useRouter()
const activeTab = ref('login')
const mensaje = ref('')
const tipoAlerta = ref<'exito' | 'error'>('error')

// Datos de login
const usuario = ref('')
const contrasena = ref('')

// Datos de registro usuario
const registerDataUser = ref({
  nombres: '',
  apellidos: '',
  correo: '',
  nombre_usuario: '',
  contrasena: '',
  id_especialidad: '',
  rol: 'profesor'
})


const handleLogin = async () => {
  const res = await login(usuario.value, contrasena.value)
  if (res.mensaje) {
    mensaje.value = res.mensaje
    tipoAlerta.value = 'exito'
    localStorage.setItem('usuario_autenticado', '1')
    // Redirige a la ruta de alumnos tras login exitoso
    setAuthenticated(true, res.datos.datos_usuario.id)
    setTimeout(() => router.push('/alumnos'), 500)
  } else if (res.error) {
    mensaje.value = res.error
    tipoAlerta.value = 'error'
  }
}

const handleRegister = async () => {
  try {
    const res = await createUsuario(registerDataUser.value)
    if (res.mensaje) {
      mensaje.value = res.mensaje
      tipoAlerta.value = 'exito'
      // Limpiar el formulario y cambiar a login
      registerDataUser.value = {
        nombres: '',
        apellidos: '',
        correo: '',
        nombre_usuario: '',
        contrasena: '',
        id_especialidad: '',
        rol: 'profesor'
      }
      setTimeout(() => {
        activeTab.value = 'login'
      }, 1500)
    } else if (res.error) {
      mensaje.value = res.error
      tipoAlerta.value = 'error'
    }
  } catch (error) {
    mensaje.value = 'Error al registrar el usuario'
    tipoAlerta.value = 'error'
  }
}

</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  padding: 20px;
}

.login-card {
  width: 100%;
  max-width: 500px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.login-header {
  margin-bottom: 30px;
}

.tabs {
  display: flex;
  border-radius: 12px;
  overflow: hidden;
  background: #f0f0f0;
  padding: 5px;
}

.tab-btn {
  flex: 1;
  background: transparent;
  border: none;
  padding: 12px;
  font-weight: 600;
  color: #666;
  transition: all 0.3s;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.tab-btn.active {
  background: white;
  color: #2575fc;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.form-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 10px;
  text-align: center;
}

.form-subtitle {
  color: #666;
  font-size: 1rem;
  text-align: center;
  margin-bottom: 25px;
}

.login-form .form-control {
  border-radius: 10px;
  padding: 12px 20px;
  height: 60px;
  border: 2px solid #e1e1e1;
  transition: all 0.3s;
}

.login-form .form-control:focus {
  border-color: #2575fc;
  box-shadow: 0 0 0 0.25rem rgba(37, 117, 252, 0.25);
}

.login-form .form-floating label {
  padding-left: 20px;
  color: #666;
}

.form-check {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.forgot-password {
  color: #2575fc;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.3s;
}

.forgot-password:hover {
  color: #1a65e6;
  text-decoration: underline;
}

.terms-link {
  color: #2575fc;
  text-decoration: none;
  transition: color 0.3s;
}

.terms-link:hover {
  color: #1a65e6;
  text-decoration: underline;
}

.btn-login {
  height: 55px;
  border-radius: 10px;
  background: linear-gradient(to right, #6a11cb, #2575fc);
  border: none;
  color: white;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-login:hover {
  background: linear-gradient(to right, #5a0cb0, #1a65e6);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(37, 117, 252, 0.4);
}

.btn-register {
  height: 55px;
  border-radius: 10px;
  background: linear-gradient(to right, #11998e, #38ef7d);
  border: none;
  color: white;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-register:hover {
  background: linear-gradient(to right, #0e8a7e, #32d56f);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(56, 239, 125, 0.4);
}

.btn-text {
  transition: transform 0.3s;
}

.btn-login:hover .btn-text,
.btn-register:hover .btn-text {
  transform: translateX(-5px);
}

.btn-login i,
.btn-register i {
  transition: transform 0.3s;
}

.btn-login:hover i,
.btn-register:hover i {
  transform: translateX(5px);
}

/* Animaciones para las transiciones entre formularios */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}

.fade-enter-from {
  opacity: 0;
  transform: translateX(20px);
}

.fade-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}

@media (max-width: 576px) {
  .login-card {
    padding: 20px;
  }
  
  .form-check {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .forgot-password {
    align-self: flex-end;
  }
}
</style>