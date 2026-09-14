import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar, QSelect } from 'quasar'
import { KtSelect } from 'components/select'

describe('KtSelect.vue', () => {
  const requiredProps = {
    modelValue: ''
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtSelect, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('EMITS', () => {
    it('Should emit `update:modelValue` event by select', async () => {
      await wrapper.findComponent(QSelect).setValue({
        CODE: '1',
        LABEL: 'Some value'
      })
      expect(wrapper.emitted()).toHaveProperty('update:modelValue')
    })
  })
})
