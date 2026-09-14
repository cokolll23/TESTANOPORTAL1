import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtWidgetLayoutTitle } from '@/components/widget-layout-title'

describe('KtWidgetLayoutTitle.vue', () => {
  const selectors = {
    title       : '.kt-widget-layout-title',
    titleText   : '.kt-widget-layout-title__text',
    titlePrefix : '.kt-widget-layout-title__prefix',
    titleSuffix : '.kt-widget-layout-title__suffix',
    iconWrapper : '.kt-icon-wrapper'
  }
  const requiredOptions = {
    title: 'Карточка виджета'
  }
  const defaultSlotContent = '<div>Main content</div>'
  const footerSlotContent  = '<div>Footer content</div>'

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtWidgetLayoutTitle, {
      props: {
        ...requiredOptions
      },
      slots: {
        default : defaultSlotContent,
        footer  : footerSlotContent
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render title if it was passed', async () => {
      expect(wrapper.find(selectors.titleText).exists()).toBe(true)
      expect(wrapper.find(selectors.titleText).text()).toBe(requiredOptions.title)

      const title = ''
      await wrapper.setProps({ title })
      expect(wrapper.find(selectors.titleText).exists()).toBe(false)
    })

    it('Should render header icon if it was passed', async () => {
      expect(wrapper.find(selectors.titlePrefix).exists()).toBe(false)
      expect(wrapper.find(selectors.iconWrapper).exists()).toBe(false)

      await wrapper.setProps({ icon: 'kt:palm' })
      expect(wrapper.find(selectors.titlePrefix).exists()).toBe(true)
      expect(wrapper.find(selectors.iconWrapper).exists()).toBe(true)
    })

    describe('Title css-classes', () => {
      it('Should set text color class', async () => {
        await wrapper.setProps({ titleColor: 'primary' })
        expect(wrapper.find(selectors.titleText).classes()).toContain('text-primary')

        await wrapper.setProps({ titleColor: 'secondary' })
        expect(wrapper.find(selectors.titleText).classes()).toContain('text-secondary')
      })
    })
  })

  describe('SLOTS', () => {
    it('Should render `suffix` slot if this one is defined', () => {
      const suffixSlotContent = '<div>Suffix content</div>'

      expect(wrapper.find(selectors.titleSuffix).exists()).toBe(false)

      wrapper = mount(KtWidgetLayoutTitle, {
        props: {
          ...requiredOptions
        },
        slots: {
          'title-suffix' : suffixSlotContent
        },
        global: {
          plugins: [Quasar]
        }
      })

      expect(wrapper.find(selectors.titleSuffix).exists()).toBe(true)
      expect(wrapper.find(selectors.titleSuffix).html()).toContain(suffixSlotContent)
    })
  })
})
