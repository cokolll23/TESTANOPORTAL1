import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtLink } from '@/components/link'

describe('KtLink.vue', () => {
  const selectors = {
    icon : '.q-icon',
    text : '.kt-link__text'
  }

  const themes = {
    primary   : 'kt-link--primary-theme',
    secondary : 'kt-link--secondary-theme'
  }

  const requiredOptions = {
    text: 'Андреева Александра Сергеевна',
    href: '/company/personal/user/1/'
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtLink, {
      props: {
        ...requiredOptions
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render icon if it has been provided', async () => {
      expect(wrapper.find(selectors.icon).exists()).toBe(false)

      const icon = 'kt:compass'
      await wrapper.setProps({ icon })
      expect(wrapper.find(selectors.icon).exists()).toBe(true)
    })

    it('Should render link text', async () => {
      const text = 'Зыков Дмитрий Александрович'

      await wrapper.setProps({ text })
      expect(wrapper.find(selectors.text).text()).toBe(text)
    })

    it('Should set "href" link attribute', async () => {
      expect(wrapper.attributes('href')).toBe(requiredOptions.href)

      await wrapper.setProps({ href: '/company/personal/user/2/' })
      expect(wrapper.attributes('href')).toBe('/company/personal/user/2/')
    })

    describe('Layout classes', () => {
      it('Should set "primary" theme if `prop.theme` equals `primary`', () => {
        expect(wrapper.classes()).toContain(themes.primary)
      })

      it('Should set "secondary" theme if `prop.theme` equals `secondary`', async () => {
        await wrapper.setProps({ theme: 'secondary' })
        expect(wrapper.classes()).toContain(themes.secondary)
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
