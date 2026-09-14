import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar, colors } from 'quasar'
import { KtWidgetLayout } from '@/components/widget-layout'
import { KtWidgetLayoutTitle } from '@/components/widget-layout-title'

describe('KtWidgetLayout.vue', () => {
  const selectors = {
    header       : '.kt-widget-layout__header',
    headerBottom : '.kt-widget-layout__header-bottom',
    separator    : '.kt-widget-layout__separator',
    content      : '.kt-widget-layout__content',
    footer       : '.kt-widget-layout__footer'
  }
  const themes = {
    light : 'kt-widget-layout--light-theme',
    dark  : 'kt-widget-layout--dark-theme'
  }
  const requiredOptions = {
    title: 'Карточка виджета'
  }
  const defaultSlotContent = '<div>Main content</div>'
  const footerSlotContent  = '<div>Footer content</div>'

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtWidgetLayout, {
      components: {
        KtWidgetLayoutTitle
      },
      props: {
        ...requiredOptions
      },
      slots: {
        default : defaultSlotContent
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render separator if `props.separator` equals true', async () => {
      expect(wrapper.find(selectors.separator).exists()).toBe(true)

      await wrapper.setProps({ separator: false })
      expect(wrapper.find(selectors.separator).exists()).toBe(false)
    })

    describe('Layout classes', () => {
      it('Should set widget theme class', async () => {
        expect(wrapper.classes()).toContain(themes.dark)

        await wrapper.setProps({ theme: 'light' })
        expect(wrapper.classes()).toContain(themes.light)
      })

      it('Should set footer justify class', async () => {
        wrapper = mount(KtWidgetLayout, {
          components: {
            KtWidgetLayoutTitle
          },
          props: {
            ...requiredOptions
          },
          slots: {
            footer : footerSlotContent
          },
          global: {
            plugins: [Quasar]
          }
        })

        expect(wrapper.find(selectors.footer).classes()).toContain('justify-left')

        await wrapper.setProps({ actionsAlign: 'center' })
        expect(wrapper.find(selectors.footer).classes()).toContain('justify-center')

        await wrapper.setProps({ actionsAlign: 'evenly' })
        expect(wrapper.find(selectors.footer).classes()).toContain('justify-evenly')
      })
    })

    describe('Layout styles', () => {
      it('Should set widget paddings', async () => {
        expect(wrapper.attributes('style')).toContain('padding: 20px')

        await wrapper.setProps({
          paddingTop    : '30px',
          paddingRight  : '30px',
          paddingBottom : '30px',
          paddingLeft   : '30px'
        })

        expect(wrapper.attributes('style')).toContain('padding: 30px')
      })

      it('Should set widget background color', async () => {
        const { hexToRgb, getPaletteColor } = colors
        const backgroundColor = getPaletteColor('secondary')
        const backgroundColorRgb = Object.values(hexToRgb(backgroundColor)).join(', ')

        expect(wrapper.attributes('style')).toContain('background-color: white')

        await wrapper.setProps({ background: backgroundColor })
        expect(wrapper.attributes('style')).toContain(`background-color: rgb(${backgroundColorRgb})`)
      })

      it('Should set separator color', async () => {
        const { hexToRgb, getPaletteColor } = colors
        const separatorColor = getPaletteColor('primary')
        const separatorColorRgb = Object.values(hexToRgb(separatorColor)).join(', ')

        await wrapper.setProps({ separatorColor })
        expect(wrapper.find(selectors.separator).attributes('style')).toContain(`background-color: rgb(${separatorColorRgb})`)
      })
    })
  })

  describe('SLOTS', () => {
    it('Should render `header-bottom` slot if it is defined', () => {
      const headerBottomSlotContent = '<div>Header bottom content</div>'

      expect(wrapper.find(selectors.headerBottom).exists()).toBe(false)

      wrapper = mount(KtWidgetLayout, {
        components: {
          KtWidgetLayoutTitle
        },
        props: {
          ...requiredOptions
        },
        slots: {
          'header-bottom' : headerBottomSlotContent
        },
        global: {
          plugins: [Quasar]
        }
      })

      expect(wrapper.find(selectors.headerBottom).exists()).toBe(true)
      expect(wrapper.find(selectors.headerBottom).html()).toContain(headerBottomSlotContent)
    })

    it('Should render `default` slot', () => {
      expect(wrapper.find(selectors.content).html()).toContain(defaultSlotContent)
    })

    it('Should render `footer` slot if it is defined', () => {
      expect(wrapper.find(selectors.footer).exists()).toBe(false)

      wrapper = mount(KtWidgetLayout, {
        components: {
          KtWidgetLayoutTitle
        },
        props: {
          ...requiredOptions
        },
        slots: {
          footer : footerSlotContent
        },
        global: {
          plugins: [Quasar]
        }
      })

      expect(wrapper.find(selectors.footer).exists()).toBe(true)
      expect(wrapper.find(selectors.footer).html()).toContain(footerSlotContent)
    })
  })
})
