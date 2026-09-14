import { describe, test, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import i18n from '@/utils/i18n'
import { KtInput } from '../index'

describe('KtInput.vue', () => {
  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtInput, {
      global: {
        plugins: [Quasar, i18n]
      },
      props: {
        modelValue: null
      }
    })
  })

  describe('Render cases', () => {
    describe('Basic validation strategies', () => {
      test('Should add strategy `required.text`', async () => {
        await wrapper.setProps({ validation: ['required.text'] })
        expect(wrapper.findComponent(KtInput).vm.rules.length).toEqual(1)
      })

      test('Strategy `required.text` should have "Function" type', async () => {
        await wrapper.setProps({ validation: ['required.text'] })
        expect(typeof wrapper.findComponent(KtInput).vm.rules[0]).toEqual('function')
      })
    })
  })
})
