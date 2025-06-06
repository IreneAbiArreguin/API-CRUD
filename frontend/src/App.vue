<script setup lang="ts">
//import { computed, ref, onMounted, watch } from 'vue'
import { ref, onMounted, watch } from 'vue'
import { isAuthenticated } from './authState'
import { useRoute } from 'vue-router'
import LogoutButton from './components/Logout.vue'

//const isAuthenticated = computed(() => !!localStorage.getItem('usuario_autenticado'))
const route = useRoute()
const isMenuOpen = ref(false)
const currentPage = ref('')

// Actualizar el título de la página según la ruta actual
const updateCurrentPage = (path: string) => {
  switch(path) {
    case '/':
      currentPage.value = 'Inicio de Sesión'
      break
    case '/alumnos':
      currentPage.value = 'Alumnos'
      break
    case '/materias':
      currentPage.value = 'Materias'
      break
    case '/grupos':
      currentPage.value = 'Grupos'
      break
    case '/calificaciones':
      currentPage.value = 'Calificaciones'
      break    
    case '/archivos':
      currentPage.value = 'Archivos'
      break
    default:
      currentPage.value = 'Sistema Escolar'
  }
}

watch(() => route.path, updateCurrentPage)

onMounted(() => {
  updateCurrentPage(route.path)
})

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value
}
</script>

<template>
  <div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar" :class="{ 'sidebar-open': isMenuOpen }">
      <div class="sidebar-header">
        <div class="logo-container">
          <div class="logo">SE</div>
          <h1 class="logo-text">Sistema<span>Escolar</span></h1>
        </div>
        <button class="sidebar-close d-md-none" @click="toggleMenu">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
      
      <div class="sidebar-content">
        <div class="nav-section">
          <h5 class="nav-title">MENÚ</h5>
          <ul class="nav-list">

            <template v-if="!isAuthenticated">
            <li class="nav-item" :class="{ active: route.path === '/' }">
              <router-link to="/" class="nav-link">
                <i class="bi bi-house-door-fill"></i>
                <span>Inicio</span>
              </router-link>
            </li>
            </template>

            <template v-if="isAuthenticated">
              <li class="nav-item" :class="{ active: route.path === '/alumnos' }">
                <router-link to="/alumnos" class="nav-link">
                  <i class="bi bi-people-fill"></i>
                  <span>Alumnos</span>
                </router-link>
              </li>
              <li class="nav-item" :class="{ active: route.path === '/materias' }">
                <router-link to="/materias" class="nav-link">
                  <i class="bi bi-book-fill"></i>
                  <span>Materias</span>
                </router-link>
              </li>
              <li class="nav-item" :class="{ active: route.path === '/grupos' }">
                <router-link to="/grupos" class="nav-link">
                  <i class="bi bi-diagram-3-fill"></i>
                  <span>Grupos</span>
                </router-link>
              </li>
              <li class="nav-item" :class="{ active: route.path === '/calificaciones' }">
                <router-link to="/calificaciones" class="nav-link">
                  <i class="bi bi-card-checklist"></i>
                  <span>Calificaciones</span>
                </router-link>
              </li>
              <li class="nav-item" :class="{ active: route.path === '/archivos' }">
                <router-link to="/archivos" class="nav-link">
                  <i class="bi bi-file-earmark-text"></i>
                  <span>Archivos</span>
                </router-link>
              </li>
            </template>
          </ul>
        </div>
        
        <div class="nav-section mt-auto" v-if="isAuthenticated">
          <h5 class="nav-title">CUENTA</h5>
          <ul class="nav-list">
            <li class="nav-item logout-item">
              <LogoutButton />
            </li>
          </ul>
        </div>
      </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
      <!-- Top Navigation Bar -->
      <header class="top-navbar">
        <button class="menu-toggle d-md-none" @click="toggleMenu">
          <i class="bi bi-list"></i>
        </button>
        
        <h2 class="page-title">{{ currentPage }}</h2>
        
        <div class="navbar-actions">
          <div class="user-profile" v-if="isAuthenticated">
            <div class="user-avatar">
              <span>👍</span>
            </div>
          </div>
        </div>
      </header>
      
      <!-- Page Content -->
      <div class="page-content">
        <transition name="fade" mode="out-in">
          <router-view />
        </transition>
      </div>
    </main>
  </div>
</template>

<style>
/* Estilos globales */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root {
  --primary-color: #6a11cb;
  --primary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
  --secondary-color: #2575fc;
  --text-color: #333;
  --text-light: #666;
  --bg-color: #f5f8fa;
  --sidebar-width: 280px;
  --sidebar-collapsed-width: 80px;
  --header-height: 70px;
  --border-radius: 15px;
  --box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
  --transition-speed: 0.3s;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: var(--bg-color);
  color: var(--text-color);
  overflow-x: hidden;
}

/* Animaciones para las transiciones de página */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<style scoped>
/* Layout principal */
.app-container {
  display: flex;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  width: var(--sidebar-width);
  background: white;
  box-shadow: var(--box-shadow);
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 1000;
  transition: transform var(--transition-speed);
}

.sidebar-header {
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.logo-container {
  display: flex;
  align-items: center;
}

.logo {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: var(--primary-gradient);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin-right: 15px;
}

.logo-text {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-color);
}

.logo-text span {
  color: var(--secondary-color);
}

.sidebar-close {
  background: transparent;
  border: none;
  color: var(--text-light);
  font-size: 1.2rem;
  cursor: pointer;
}

.sidebar-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 20px 0;
  overflow-y: auto;
}

.nav-section {
  margin-bottom: 30px;
}

.nav-title {
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--text-light);
  padding: 0 20px;
  margin-bottom: 15px;
  letter-spacing: 1px;
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-item {
  margin-bottom: 5px;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: var(--text-color);
  text-decoration: none;
  transition: all var(--transition-speed);
  border-radius: 0 30px 30px 0;
  margin-right: 20px;
}

.nav-link i {
  font-size: 1.2rem;
  margin-right: 15px;
  transition: all var(--transition-speed);
}

.nav-item.active .nav-link {
  background: var(--primary-gradient);
  color: white;
  font-weight: 500;
}

.nav-link:hover:not(.nav-item.active .nav-link) {
  background: rgba(0, 0, 0, 0.05);
}

.logout-item {
  padding: 0 20px;
}

/* Main Content */
.main-content {
  flex: 1;
  margin-left: var(--sidebar-width);
  transition: margin var(--transition-speed);
}

.top-navbar {
  height: var(--header-height);
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 30px;
  position: sticky;
  top: 0;
  z-index: 900;
}

.menu-toggle {
  background: transparent;
  border: none;
  font-size: 1.5rem;
  color: var(--text-color);
  cursor: pointer;
  display: none;
}

.page-title {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--text-color);
  margin: 0;
}

.navbar-actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.action-button {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  color: var(--text-color);
  cursor: pointer;
  position: relative;
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #dc3545;
  color: white;
  font-size: 0.7rem;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-profile {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: var(--primary-gradient);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.page-content {
  padding: 30px;
  min-height: calc(100vh - var(--header-height));
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }
  
  .sidebar-open {
    transform: translateX(0);
  }
  
  .main-content {
    margin-left: 0;
  }
  
  .menu-toggle {
    display: block;
  }
}
</style>