import { ref } from 'vue'

/**
 * Estado reactivo global para la autenticación del usuario.
 * Se sincroniza con localStorage para persistencia entre recargas.
 */
export const isAuthenticated = ref(!!localStorage.getItem('usuario_autenticado'))
export const usuarioId = ref(localStorage.getItem('usuario_id') || '')
/**
 * Actualiza el estado de autenticación y sincroniza con localStorage.
 * @param value true si el usuario está autenticado, false si no.
 */
export function setAuthenticated(value: boolean, id?: string) {
  isAuthenticated.value = value
  if (value && id) {
    localStorage.setItem('usuario_autenticado', '1')
    localStorage.setItem('usuario_id', id)
    usuarioId.value = id
  } else {
    localStorage.removeItem('usuario_autenticado')
    localStorage.removeItem('usuario_id')
    usuarioId.value = ''
  }
}

