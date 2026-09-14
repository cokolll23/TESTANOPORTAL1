import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { KtLink } from '@/components/link'
import { KtUserCard } from '@/components/user-card'

describe('KtUserCard.vue', () => {
  const elementSelector = {
    avatarWrapper : '.kt-user-card__avatar-wrapper',
    avatar        : '.kt-user-card__avatar-wrapper .q-img__image',
    name          : '.kt-user-card__name',
    workPosition  : '.kt-user-card__work-position'
  }

  const themes = {
    primary   : 'kt-link--primary-theme',
    secondary : 'kt-link--secondary-theme'
  }

  const requiredOptions = {
    user: {
      ID: 3,
      FULL_NAME: 'Андреева Александра Сергеевна',
      LAST_NAME: 'Андреева',
      WORK_POSITION: 'Директор Департамента сопровождения информационных технологий',
      NAME: 'Александра',
      LOGIN: 'andreeva',
      SECOND_NAME: 'Александра',
      PHOTO: window.location.origin + '/src/assets/default-user-avatar.svg'
    }
  }
  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtUserCard, {
      components: {
        KtLink
      },
      props: {
        ...requiredOptions
      }
    })
  })

  describe('RENDER', () => {
    it('Should render avatar', async () => {
      expect(wrapper.find(elementSelector.avatarWrapper).exists()).toBe(true)
    })

    it('Shouldn"t render avatar if `props.hideAvatar` equals true', async () => {
      await wrapper.setProps({
        hideAvatar: true
      })

      expect(wrapper.find(elementSelector.avatarWrapper).exists()).toBe(false)
    })

    it('Should render user name', () => {
      expect(wrapper.find(elementSelector.name).exists()).toBe(true)
    })

    it('Shouldn"t render user name if `props.hideContent` equals true', async () => {
      await wrapper.setProps({ hideContent: true })
      expect(wrapper.find(elementSelector.name).exists()).toBe(false)
    })

    it('Should render user workPosition if it has been provided', async () => {
      expect(wrapper.find(elementSelector.workPosition).exists()).toBe(true)
      expect(wrapper.find(elementSelector.workPosition).text()).toContain(requiredOptions.user.WORK_POSITION)

      await wrapper.setProps({
        user: { ...requiredOptions.user, WORK_POSITION: '' }
      })

      expect(wrapper.find(elementSelector.workPosition).exists()).toBe(false)
    })

    it('Shouldn"t render user workPosition if `props.hideContent` equals true', async () => {
      await wrapper.setProps({
        hideContent: true
      })

      expect(wrapper.find(elementSelector.workPosition).exists()).toBe(false)
    })

    it('Should set correct href', () => {
      expect(wrapper.attributes('href')).toBe(`/company/personal/user/${requiredOptions.user.ID}/`)
    })

    describe('Layout classes', () => {
      it('Should have a size class', async () => {
        expect(wrapper.classes('kt-user-card--md')).toBe(true)

        await wrapper.setProps({ size: 'xl' })
        expect(wrapper.classes('kt-user-card--xl')).toBe(true)

        await wrapper.setProps({ size: 'xs' })
        expect(wrapper.classes('kt-user-card--xs')).toBe(true)
      })

      it('Should set "primary" theme if `props.theme` equals "primary"', () => {
        expect(wrapper.classes()).toContain(themes.primary)
      })

      it('Should set "secondary" theme if `props.theme` equals "secondary"', async () => {
        await wrapper.setProps({ theme: 'secondary' })
        expect(wrapper.classes()).toContain(themes.secondary)
      })
    })
  })
})
