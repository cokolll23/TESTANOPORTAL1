import { ref, Ref } from 'vue'

export function usePreparedTags () {
  const newTagModel = ref('')
  const preparedTags: Ref<string[]> = ref([])
  const isHintPopupVisible = ref(false)

  const addPrepared = (interest?: string) => {
    if (typeof interest !== 'undefined') {
      if (!preparedTags.value.includes(interest)) {
        preparedTags.value.push(interest)
        newTagModel.value = ''
      }
      return
    }

    if (newTagModel.value === '') {
      return
    }

    preparedTags.value.push(newTagModel.value)
    newTagModel.value = ''
  }

  const removePrepared = (interest?: string) => {
    if (newTagModel.value === '' && typeof interest === 'undefined') {
      interest = preparedTags.value[preparedTags.value.length - 1]
    }

    preparedTags.value = preparedTags.value.filter(_ => _ !== interest)
  }
  const hideHintPopup = () => {
    isHintPopupVisible.value = false
  }
  const showHintPopup = () => {
    isHintPopupVisible.value = true
  }

  return {
    newTagModel,
    preparedTags,
    isHintPopupVisible,

    addPrepared,
    removePrepared,
    hideHintPopup,
    showHintPopup
  }
}
