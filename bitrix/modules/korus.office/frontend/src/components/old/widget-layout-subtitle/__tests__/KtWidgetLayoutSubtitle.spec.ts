import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtWidgetLayoutSubtitle } from 'components/widget-layout-subtitle'

describe('KtWidgetLayoutText.vue', () => {
  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtWidgetLayoutSubtitle, {
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render text', async () => {
      const text = 'Text'

      await wrapper.setProps({ text })
      expect(wrapper.text()).toContain(text)
    })
  })

  describe('SLOTS', () => {
    it('Should render default slot', () => {
      const defaultSlotContent = '<span>Текст</span>'

      wrapper = mount(KtWidgetLayoutSubtitle, {
        slots: {
          default: defaultSlotContent
        },
        global: {
          plugins: [Quasar]
        }
      })

      expect(wrapper.html()).toContain(defaultSlotContent)
    })
  })
})
