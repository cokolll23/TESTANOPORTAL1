import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtTextClamp } from 'components/text-clamp'

describe('KtTextClamp.vue', () => {
  const selectors = {
    text    : '.kt-text-clamp__text',
    tooltip : '.kt-text-clamp__tooltip'
  }

  const requiredProps = {
    text: 'Длинный и интересный текст про то, как корабли бороздят просторы вселенной'
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtTextClamp, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render clamp text', () => {
      expect(wrapper.find(selectors.text).text()).toContain(requiredProps.text)
    })
  })
})
