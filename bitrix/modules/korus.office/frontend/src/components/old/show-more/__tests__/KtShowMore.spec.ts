import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtShowMore } from '@/components/show-more'

describe.skip('KtShowMore.vue', () => {
  const elementSelector = {
    wrapper      : '.kt-show-more',
    toggleButton : '.kt-show-more__btn'
  }
  const stateClasses = {
    expanded: 'is-expanded'
  }
  const props = {
    expandText: 'Показать больше',
    expandedText: 'Свернуть'
  }
  const visibleSlotContent = '<div>Видимый контент</div>'
  const defaultSlotContent = '<div>Скрытый контент</div>'

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtShowMore, {
      props,
      slots: {
        visible : visibleSlotContent,
        default : defaultSlotContent
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render button text in `expand` state', () => {
      expect(wrapper.find(elementSelector.toggleButton).text()).toContain(props.expandText)
    })

    it('Should render button text in `expanded` state', async () => {
      await wrapper.find(elementSelector.toggleButton).trigger('click')
      expect(wrapper.find(elementSelector.toggleButton).text()).toContain(props.expandedText)
    })

    it('Should be `expanded` if prop `defaultOpened` is true', async () => {
      wrapper = mount(KtShowMore, {
        props: {
          ...props,
          defaultOpened: true
        },
        slots: {
          visible : visibleSlotContent,
          default : defaultSlotContent
        },
        global: {
          plugins: [Quasar]
        }
      })

      expect(wrapper.classes(stateClasses.expanded)).toBe(true)
    })

    describe('Layout classes', () => {
      it('Should have class `is-expanded` in `expanded` state', async () => {
        expect(wrapper.classes(stateClasses.expanded)).toBe(false)

        await wrapper.find(elementSelector.toggleButton).trigger('click')
        expect(wrapper.classes(stateClasses.expanded)).toBe(true)
      })
    })
  })

  describe('SLOTS', () => {
    it('Should render `default` slot', () => {
      expect(wrapper.find(elementSelector.wrapper).html()).toContain(defaultSlotContent)
    })

    it('Should render `visible` slot', () => {
      expect(wrapper.find(elementSelector.wrapper).html()).toContain(visibleSlotContent)
    })
  })
})
