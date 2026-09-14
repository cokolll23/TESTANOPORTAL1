import { ref } from 'vue'

export function useLegacy () {
  const isLegacyMode = ref(document.body.classList.contains('pgk'))

  return {
    isLegacyMode
  }
}
