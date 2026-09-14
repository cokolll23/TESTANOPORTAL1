import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePersonalStore } from 'stores/personal'

export function useAvatarUploader () {
  const { t } = useI18n()
  const store = usePersonalStore()

  let resCamera: any
  const isCameraBtnVisible = ref(true)

  const createPhoto = () => {
    if (typeof resCamera !== 'undefined' && typeof resCamera.show === 'function') {
      resCamera.show('camera')
    }
  }

  const uploadPhoto = () => {
    if (typeof resCamera !== 'undefined' && typeof resCamera.show === 'function') {
      resCamera.show('file')
    }
  }

  const removePhoto = async () => {
    if (!store.AVATAR) { return }

    await store.deletePhoto()
  }

  const changePhoto = async (dataObj: FormData) => {
    await store.loadPhoto(dataObj)
  }

  const init = () => {
    if (typeof window.BX === 'undefined' || typeof window.BX.AvatarEditor !== 'function') { return }

    resCamera = new window.BX.AvatarEditor({
      enableCamera : true
    })
    resCamera.getLimitText = () => t('avatarUploader.limitText')

    if (!window.BX.AvatarEditor.isCameraAvailable()) {
      isCameraBtnVisible.value = false
    }

    window.BX.addCustomEvent(resCamera, 'onApply', (file: any) => {
      const formObj = new FormData()

      if (!file.name) {
        file.name = 'tmp.png'
      }

      formObj.append('newPhoto', file, file.name)

      changePhoto(formObj)
    })
  }

  onMounted(init)

  return {
    isCameraBtnVisible,
    createPhoto,
    uploadPhoto,
    removePhoto
  }
}
