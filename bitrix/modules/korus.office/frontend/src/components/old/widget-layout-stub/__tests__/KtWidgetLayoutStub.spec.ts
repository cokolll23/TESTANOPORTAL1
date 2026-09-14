import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtWidgetLayoutStub } from 'components/widget-layout-stub'

describe('KtWidgetLayoutStub.vue', () => {
  const selectors = {
    icon : '.kt-widget-layout-stub__icon',
    text : '.kt-widget-layout-stub__text',
    btn  : '.kt-widget-layout-stub__btn'
  }

  const requiredProps = {
    icon: 'kt:notebook',
    text: 'Книги',
    btnText: 'Рассказать'
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtWidgetLayoutStub, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render icon', () => {
      expect(wrapper.find(selectors.icon).exists()).toBe(true)
    })

    it('Should render text', () => {
      expect(wrapper.find(selectors.text).exists()).toBe(true)
    })

    it('Should render action button', () => {
      expect(wrapper.find(selectors.btn).exists()).toBe(true)
    })
  })

  describe('EMITS', () => {
    it('Should emit `click` event by action button click', async () => {
      expect(wrapper.emitted()).not.toHaveProperty('click')

      await wrapper.find(selectors.btn).trigger('click')
      expect(wrapper.emitted()).toHaveProperty('click')
    })
  })
})
