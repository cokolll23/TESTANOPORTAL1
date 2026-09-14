import { ref } from 'vue'

export function useWidgetStub () {
  const stubVisible = ref(true)

  const showStub = () => {
    stubVisible.value = true
  }
  const hideStub = () => {
    stubVisible.value = false
  }

  return {
    stubVisible,
    showStub,
    hideStub
  }
}
