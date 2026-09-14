import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtWidgetLayoutText } from 'components/widget-layout-text'

describe('KtWidgetLayoutText.vue', () => {
  const requiredProps = {
    text: 'Книги'
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtWidgetLayoutText, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render text', () => {
      expect(wrapper.text()).toContain(requiredProps.text)
    })

    describe('Layout classes', () => {
      it('Should set text color class', async () => {
        await wrapper.setProps({ color: 'primary' })
        expect(wrapper.classes()).toContain('text-primary')

        await wrapper.setProps({ color: 'secondary' })
        expect(wrapper.classes()).toContain('text-secondary')
      })
    })
  })

  describe('SLOTS', () => {
    it('Should render default slot', () => {
      const defaultSlotContent = '<span>Текст</span>'

      wrapper = mount(KtWidgetLayoutText, {
        props: {
          ...requiredProps
        },
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
