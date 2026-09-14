import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtWidgetLayoutRow } from 'components/widget-layout-row'

describe('KtWidgetLayoutRow.vue', () => {
  const selectors = {
    label   : '.kt-widget-layout-row__label',
    content : '.kt-widget-layout-row__content'
  }

  const requiredProps = {
    text: 'Книги'
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtWidgetLayoutRow, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render label if it was passed', async () => {
      const label = 'Label'

      expect(wrapper.find(selectors.label).exists()).toBe(false)

      await wrapper.setProps({ label })
      expect(wrapper.find(selectors.label).exists()).toBe(true)
      expect(wrapper.find(selectors.label).text()).toContain(label)
    })

    it('Should render text if it was passed', async () => {
      const text = 'Some text'

      await wrapper.setProps({ text })
      expect(wrapper.find(selectors.content).text()).toContain(text)
    })

    describe('Layout classes', () => {
      it('Should set yAligment css-class', async () => {
        expect(wrapper.classes()).toContain('items-start')

        await wrapper.setProps({ yAligment: 'center' })
        expect(wrapper.classes()).toContain('items-center')

        await wrapper.setProps({ yAligment: 'end' })
        expect(wrapper.classes()).toContain('items-end')
      })
    })
  })

  describe('SLOTS', () => {
    it('Should render default slot', () => {
      const defaultSlotContent = '<span>Текст</span>'

      wrapper = mount(KtWidgetLayoutRow, {
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

      expect(wrapper.find(selectors.content).html()).toContain(defaultSlotContent)
    })
  })
})
