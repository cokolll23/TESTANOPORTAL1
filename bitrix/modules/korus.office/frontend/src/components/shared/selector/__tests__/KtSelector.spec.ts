import { describe, test, beforeAll, afterAll, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar, QField } from 'quasar'
import i18n from '@/utils/i18n'
import { KtSelector } from '@/components/shared'
import { BX } from '@/tests/bx'

describe('KtSelector.vue', () => {
  let wrapper: VueWrapper
  const selectors = {
    actionMoreBtn: '[data-test-selector="action-more-btn"]',
    dropdownIcon: '[data-test-selector="dropdown-icon"]'
  }

  beforeAll(() => {
    global.BX = BX
  })

  afterAll(() => {
    global.BX = Object.create(null)
  })

  beforeEach(() => {
    wrapper = mount(KtSelector, {
      global: {
        plugins: [Quasar, i18n]
      },
      props: {
        modelValue: null,
        entities: [
          { id: 'user' }
        ],
        id: 'test'
      }
    })
  })

  describe('Render cases', () => {
    describe('Action more button', () => {
      test('Shouldn\'t render by default', () => {
        expect(wrapper.find(selectors.actionMoreBtn).exists()).toBe(false)
      })

      test('Should render it if prop `showActionMoreBtn` is true', async () => {
        await wrapper.setProps({ showActionMoreBtn: true })
        expect(wrapper.find(selectors.actionMoreBtn).exists()).toBe(true)
      })
    })

    describe('Dropdown icon', () => {
      test('Shouldn\'t render by default', () => {
        expect(wrapper.find(selectors.dropdownIcon).exists()).toBe(false)
      })

      test('Should render it if prop `showDropdownIcon` is true', async () => {
        await wrapper.setProps({ showDropdownIcon: true })
        expect(wrapper.find(selectors.dropdownIcon).exists()).toBe(true)
      })
    })

    describe('Basic validation strategies', () => {
      test('Should add strategy `required`', async () => {
        await wrapper.setProps({ validation: ['required'] })
        expect(wrapper.findComponent(KtSelector).vm.rules.length).toEqual(1)
      })

      test('Strategy `required` should have "Function" type', async () => {
        await wrapper.setProps({ validation: ['required'] })
        expect(typeof wrapper.findComponent(KtSelector).vm.rules[0]).toEqual('function')
      })
    })
  })

  describe('Style cases', () => {
    describe('Card size className', () => {
      test('Should render selector in `size-md` if prop `size` is `md`', async () => {
        await wrapper.setProps({ size: 'md' })

        expect(wrapper.findComponent(QField).classes('kt-selector--md')).toBe(true)
        expect(wrapper.findComponent(QField).classes('kt-selector--sm')).toBe(false)
      })

      test('Should render selector in `size-sm` if prop `size` is `sm`', async () => {
        await wrapper.setProps({ size: 'sm' })

        expect(wrapper.findComponent(QField).classes('kt-selector--sm')).toBe(true)
        expect(wrapper.findComponent(QField).classes('kt-selector--md')).toBe(false)
      })
    })
  })
})
