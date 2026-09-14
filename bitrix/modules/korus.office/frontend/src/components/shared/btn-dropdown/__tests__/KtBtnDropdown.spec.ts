import { describe, test, beforeEach, expect } from 'vitest'
import { shallowMount, mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtBtnDropdown } from '@/components/shared'

describe('KtBtnDropdown.vue', () => {
  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = shallowMount(KtBtnDropdown)
  })

  describe('Style cases', () => {
    describe('Theme className', () => {
      test('Should set `default` theme by default', () => {
        expect(wrapper.findComponent(KtBtnDropdown).classes('q-btn--default-theme')).toBe(true)
      })

      test('Should set theme', async () => {
        await wrapper.setProps({ theme: 'primary' })
        expect(wrapper.findComponent(KtBtnDropdown).classes('q-btn--primary-theme')).toBe(true)

        await wrapper.setProps({ theme: 'secondary' })
        expect(wrapper.findComponent(KtBtnDropdown).classes('q-btn--secondary-theme')).toBe(true)

        await wrapper.setProps({ theme: 'text' })
        expect(wrapper.findComponent(KtBtnDropdown).classes('q-btn--text-theme')).toBe(true)
      })
    })
  })

  describe('Slots cases', () => {
    test('Should render default slot', () => {
      const defaultSlotContent = '<span>Текст в дефолтный слот</span>'

      wrapper = mount(KtBtnDropdown, {
        global: {
          plugins: [Quasar]
        },
        props: {
          transitionDuration: 0
        },
        slots: {
          default: defaultSlotContent
        }
      })

      setTimeout(() => {
        expect(wrapper.html()).toContain(defaultSlotContent)
      }, 500)
    })

    test('Should render loading slot', () => {
      const loadingSlotContent = '<span>Текст в слот загрузки</span>'

      wrapper = mount(KtBtnDropdown, {
        global: {
          plugins: [Quasar]
        },
        props: {
          loading: true
        },
        slots: {
          loading: loadingSlotContent
        }
      })

      expect(wrapper.html()).toContain(loadingSlotContent)
    })

    test('Should render label slot', () => {
      const labelSlotContent = '<span>Текст в слот заголовка</span>'

      wrapper = mount(KtBtnDropdown, {
        global: {
          plugins: [Quasar]
        },
        props: {
          loading: true
        },
        slots: {
          label: labelSlotContent
        }
      })

      expect(wrapper.html()).toContain(labelSlotContent)
    })
  })
})
