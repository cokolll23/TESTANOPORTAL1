import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar } from 'quasar'
import { KtTag } from 'components/tag'

describe('KtTag.vue', () => {
  const selectors = {
    tag       : '.kt-tag',
    text      : '.kt-tag__text',
    users     : '.kt-tag__users',
    popup     : '.kt-tag__popup',
    removeBtn : '.kt-tag .q-chip__icon--remove'
  }

  const requiredProps = {
    text: 'Книги',
    removable: true,
    users: [
      {
        ID: 3,
        FULL_NAME: 'Андреева Александра Сергеевна',
        LAST_NAME: 'Андреева',
        NAME: 'Александра',
        LOGIN: 'andreeva',
        SECOND_NAME: 'Александра',
        PHOTO: window.location.origin + '/src/assets/default-user-avatar.svg'
      }
    ]
  }

  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtTag, {
      props: {
        ...requiredProps
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render text', () => {
      expect(wrapper.find(selectors.text).text()).toContain(requiredProps.text)
    })

    it('Should render user list if `props.users` is not empty array', () => {
      expect(wrapper.find(selectors.users).exists()).toBe(true)
    })

    it('Shouldn"t render user list if `props.users` is empty array', async () => {
      await wrapper.setProps({ users: [] })
      expect(wrapper.find(selectors.users).exists()).toBe(false)
    })
  })

  describe('EMITS', () => {
    it('Should emit `click` event by click on tab', async () => {
      await wrapper.find(selectors.tag).trigger('click')
      expect(wrapper.emitted()).toHaveProperty('click')
    })

    it('Should emit `removeTag` event by click on remove btn', async () => {
      await wrapper.find(selectors.removeBtn).trigger('click')
      expect(wrapper.emitted()).toHaveProperty('removeTag')
    })
  })
})
