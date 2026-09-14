import { describe, test, beforeEach, expect } from 'vitest'
import { shallowMount, mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtBtn } from '@/components/shared'

describe('KtBtn.vue', () => {
  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = shallowMount(KtBtn)
  })

  describe('Style cases', () => {
    describe('Theme className', () => {
      test('Should set `default` theme by default', () => {
        expect(wrapper.findComponent(KtBtn).classes('q-btn--default-theme')).toBe(true)
      })

      test('Should set theme', async () => {
        await wrapper.setProps({ theme: 'primary' })
        expect(wrapper.findComponent(KtBtn).classes('q-btn--primary-theme')).toBe(true)

        await wrapper.setProps({ theme: 'secondary' })
        expect(wrapper.findComponent(KtBtn).classes('q-btn--secondary-theme')).toBe(true)

        await wrapper.setProps({ theme: 'text' })
        expect(wrapper.findComponent(KtBtn).classes('q-btn--text-theme')).toBe(true)
      })
    })
  })

  describe('Slots cases', () => {
    test('Should render default slot', () => {
      const defaultSlotContent = '<span>Текст в дефолтный слот</span>'

      wrapper = mount(KtBtn, {
        global: {
          plugins: [Quasar]
        },
        slots: {
          default: defaultSlotContent
        }
      })

      expect(wrapper.html()).toContain(defaultSlotContent)
    })

    test('Should render loading slot', () => {
      const loadingSlotContent = '<span>Текст в слот загрузки</span>'

      wrapper = mount(KtBtn, {
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
  })
})
