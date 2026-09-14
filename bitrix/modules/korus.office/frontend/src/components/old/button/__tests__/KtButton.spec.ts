import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar, QBtn, QBtnDropdown } from 'quasar'
import { KtButton } from 'components/button'

describe('KtButton.vue', () => {
  const themes = {
    default : 'kt-button--default-theme',
    primary : 'kt-button--primary-theme',
    info    : 'kt-button--info-theme',
    warn    : 'kt-button--warn-theme',
    danger  : 'kt-button--danger-theme',
    text    : 'kt-button--text-theme'
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtButton, {
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render QBtn Component by default', () => {
      expect(wrapper.findComponent(QBtn).exists()).toBe(true)
    })

    it('Should render QBtnDropdown Component if `props.dropdown` equals true', async () => {
      await wrapper.setProps({ dropdown: true })
      expect(wrapper.findComponent(QBtnDropdown).exists()).toBe(true)
    })

    describe('Layout classes', () => {
      it('Should set "default" theme if `prop.theme` equals `default`', () => {
        expect(wrapper.classes()).toContain(themes.default)
      })

      it('Should set "primary" theme if `prop.theme` equals `primary`', async () => {
        await wrapper.setProps({ theme: 'primary' })
        expect(wrapper.classes()).toContain(themes.primary)
      })

      it('Should set "info" theme if `prop.theme` equals `info`', async () => {
        await wrapper.setProps({ theme: 'info' })
        expect(wrapper.classes()).toContain(themes.info)
      })

      it('Should set "warn" theme if `prop.theme` equals `warn`', async () => {
        await wrapper.setProps({ theme: 'warn' })
        expect(wrapper.classes()).toContain(themes.warn)
      })

      it('Should set "danger" theme if `prop.theme` equals `danger`', async () => {
        await wrapper.setProps({ theme: 'danger' })
        expect(wrapper.classes()).toContain(themes.danger)
      })

      it('Should set "text" theme if `prop.theme` equals `text`', async () => {
        await wrapper.setProps({ theme: 'text' })
        expect(wrapper.classes()).toContain(themes.text)
      })
    })
  })

  describe('EMITS', () => {
    it('Should emit `click` event by click', async () => {
      await wrapper.trigger('click')
      expect(wrapper.emitted()).toHaveProperty('click')
    })
  })
})
