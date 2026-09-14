import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtUserStatus } from 'components/user-status'

describe('KtUserStatus.vue', () => {
  const selectors = {
    circle : '.kt-user-status__circle',
    text   : '.kt-user-status__text'
  }

  const circleColor = {
    green : 'bg-app-green-2',
    red   : 'bg-app-deep-orange-1'
  }

  const requiredProps = {
    isOnline: true
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtUserStatus, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render green circle when user online', () => {
      expect(wrapper.find(selectors.circle).classes()).toContain(circleColor.green)
    })

    it('Should render red circle when user offline', async () => {
      await wrapper.setProps({ isOnline: false })
      expect(wrapper.find(selectors.circle).classes()).toContain(circleColor.red)
    })

    it('Should render correct text when user online', () => {
      const text = 'В сети'
      expect(wrapper.find(selectors.text).text()).toContain(text)
    })

    it('Should render correct text when user offline', async () => {
      const text = 'Не в сети'

      await wrapper.setProps({ isOnline: false })
      expect(wrapper.find(selectors.text).text()).toContain(text)
    })
  })
})
