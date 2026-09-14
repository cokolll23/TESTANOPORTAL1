import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtIcon } from 'components/icon'

describe('KtIcon.vue', () => {
  const classesSize = {
    sm : 'kt-icon-wrapper--sm',
    md : 'kt-icon-wrapper--md'
  }
  const classPrimaryBg = 'bg-primary'

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtIcon, {
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    describe('Layout classes', () => {
      it('Should set md-size css-class', () => {
        expect(wrapper.classes()).toContain(classesSize.md)
      })

      it('Should set sm-size css-class', async () => {
        await wrapper.setProps({ size: 'sm' })
        expect(wrapper.classes()).toContain(classesSize.sm)
      })

      it('Should set background css-class', async () => {
        await wrapper.setProps({ backgroundColor: 'primary' })
        expect(wrapper.classes()).toContain(classPrimaryBg)
      })
    })
  })
})
