import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'

// Importa Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css'
// (Opcional) Importa Bootstrap JS si necesitas componentes interactivos
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
// Importa Bootstrap iconos
import 'bootstrap-icons/font/bootstrap-icons.css'



createApp(App).use(router).mount('#app')
