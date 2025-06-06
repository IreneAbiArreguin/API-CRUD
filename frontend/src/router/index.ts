import { createRouter, createWebHistory } from 'vue-router'
import AlumnosList from '../components/AlumnosList.vue'
import LoginForm from '../components/LoginForm.vue'
import MateriasList from '../components/MateriasList.vue'
import GruposList from '../components/GruposList.vue'
import CalificacionesList from '../components/CalificacionesList.vue'
import ArchivosList from '../components/ArchivosList.vue'
// importa otros componentes según los crees

const routes = [
  { path: '/', component: LoginForm },
  { path: '/alumnos', component: AlumnosList },
  { path: '/materias', component: MateriasList },
  { path: '/grupos', component: GruposList },
  { path: '/calificaciones', component: CalificacionesList },
  { path: '/archivos', component: ArchivosList },
  // agrega aquí las rutas de materias, profesores, etc.
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Guard global para proteger rutas
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('usuario_autenticado')
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/')
  } else {
    next()
  }
})

export default router