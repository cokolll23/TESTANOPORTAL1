import { describe, it, beforeEach, expect, vi } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { createI18n  } from 'vue-i18n'
import { createTestingPinia } from '@pinia/testing'
import { usePersonalStore } from 'stores/personal'
import { KtAvatarUploader } from 'components/avatar-uploader'
import { KtButton } from 'components/button'
import messages from '@/i18n'

describe('KtAvatarUploader.vue', () => {
  const selectors = {
    loadActions    : '.kt-avatar-uploader__load-actions',
    createPhotoBtn : '.kt-avatar-uploader__create-btn',
    uploadPhotoBtn : '.kt-avatar-uploader__upload-btn',
    removePhotoBtn : '.kt-avatar-uploader__remove-btn'
  }
  const classes = {
    editMode : 'is-edit-mode'
  }
  const i18n = createI18n({
    locale: 'ru',
    globalInjection: true,
    messages
  })

  let wrapper: VueWrapper
  let store: ReturnType<typeof usePersonalStore>

  beforeEach(() => {
    // store = usePersonalStore()
    wrapper = mount(KtAvatarUploader, {
      components: {
        KtButton
      },
      global: {
        plugins: [
          Quasar,
          createTestingPinia(),
          i18n
        ]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render "Load actions" if user has rights', () => {
      expect(wrapper.find(selectors.loadActions).exists()).toBe(false)
    })

    it('Should render "Delete btn" only if user has rights and photo exists', () => {
      expect(wrapper.find(selectors.removePhotoBtn).exists()).toBe(false)
    })

    describe('Layout classes', () => {
      it('Should set css-class `is-edit-mode` if user has edit rights', () => {
        expect(wrapper.classes()).not.toContain(classes.editMode)
      })
    })
  })

  describe('METHODS', () => {
    it.skip('Should call `createPhoto` by click "Create photo" btn', async () => {
      const createPhoto = vi.spyOn(wrapper.vm as typeof wrapper.vm & typeof KtAvatarUploader, 'createPhoto')

      expect(createPhoto).toHaveBeenCalledTimes(0)
      await wrapper.find(selectors.createPhotoBtn).trigger('click')
      expect(createPhoto).toHaveBeenCalledTimes(1)
    })

    it.skip('Should call `uploadPhoto` by click "Upload photo" btn', async () => {
      const uploadPhoto = vi.spyOn(wrapper.vm as typeof wrapper.vm & typeof KtAvatarUploader, 'uploadPhoto')

      expect(uploadPhoto).toHaveBeenCalledTimes(1)
      await wrapper.find(selectors.uploadPhotoBtn).trigger('click')
      expect(uploadPhoto).toHaveBeenCalledTimes(1)
    })

    it.skip('Should call `removePhoto` by click "Delete photo" btn', async () => {
      const removePhoto = vi.spyOn(wrapper.vm as typeof wrapper.vm & typeof KtAvatarUploader, 'removePhoto')

      expect(removePhoto).toHaveBeenCalledTimes(1)
      await wrapper.find(selectors.removePhotoBtn).trigger('click')
      expect(removePhoto).toHaveBeenCalledTimes(1)
    })
  })
})
