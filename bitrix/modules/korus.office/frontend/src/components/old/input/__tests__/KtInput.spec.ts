import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar, QInput } from 'quasar'
import { KtInput } from 'components/input'

describe('KtInput.vue', () => {
  const requiredProps = {
    modelValue: ''
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtInput, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('EMITS', () => {
    it('Should emit `update:modelValue` event by input', async () => {
      await wrapper.findComponent(QInput).setValue('Some value')
      expect(wrapper.emitted()).toHaveProperty('update:modelValue')
    })
  })
})
