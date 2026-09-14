import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtTabs } from 'components/tabs'
import { IKtTab } from '@/models'

interface IRequiredProps {
  modelValue: string;
  tabs:       IKtTab[];
}

describe('KtTabs.vue', () => {
  const selectors = {
    tabs  : '.kt-tabs',
    tab   : '.kt-tab',
    badge : '.kt-tab__badge'
  }

  const themes = {
    light : 'kt-tabs--light-theme',
    dark  : 'kt-tabs--dark-theme'
  }

  const requiredProps: IRequiredProps = {
    modelValue: '',
    tabs: [
      { name: 'tab 1', label: 'Tab 1', count: 2 },
      { name: 'tab 2', label: 'Tab 2' },
      { name: 'tab 3', label: 'Tab 3' }
    ]
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtTabs, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render badge if tab has prop `count`', async () => {
      expect(wrapper.findAll(selectors.badge).length).toBe(1)
    })

    describe('Layout classes', () => {})
    it('Should set "dark" theme if `prop.theme` equals `dark`', () => {
      expect(wrapper.find(selectors.tabs).classes()).toContain(themes.dark)
    })

    it('Should set "light" theme if `prop.theme` equals `light`', async () => {
      await wrapper.setProps({ theme: 'light' })
      expect(wrapper.find(selectors.tabs).classes()).toContain(themes.light)
    })
  })

  describe('EMITS', () => {
    it('Should emit `update:modelValue` event by click on tab', async () => {
      await wrapper.find(selectors.tab).trigger('click')
      expect(wrapper.emitted()).toHaveProperty('update:modelValue')
    })
  })
})
