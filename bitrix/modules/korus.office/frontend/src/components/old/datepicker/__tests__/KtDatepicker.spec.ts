import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar, QInput } from 'quasar'
import { KtDatepicker } from 'components/datepicker'

describe('KtDatepicker.vue', () => {
  const requiredProps = {
    modelValue: ''
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtDatepicker, {
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
      await wrapper.findComponent(QInput).setValue('07.09.2022')
      expect(wrapper.emitted()).toHaveProperty('update:modelValue')
    })
  })
})
