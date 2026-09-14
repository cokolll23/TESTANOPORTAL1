import { describe, test, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { KtUserCard } from '@/components/shared'
import { createUser } from '@/tests/test-data-provider'

describe('KtUserCard.vue', () => {
  let wrapper: VueWrapper
  const selectors = {
    card: '[data-test-user-card="card"]',
    avatarWrapper: '[data-test-user-card="avatar-wrapper"]',
    content: '[data-test-user-card="content"]',
    name: '[data-test-user-card="name"]'
  }

  beforeEach(() => {
    wrapper = mount(KtUserCard, {
      props: {
        user: createUser(1)
      }
    })
  })

  describe('Render cases', () => {
    describe('User avatar', () => {
      test('Shouldn\'t render it by default', () => {
        expect(wrapper.find(selectors.avatarWrapper).exists()).toBe(false)
      })

      test('Should render it if prop `showAvatar` is true', async () => {
        await wrapper.setProps({ showAvatar: true })
        expect(wrapper.find(selectors.avatarWrapper).exists()).toBe(true)
      })
    })

    describe('User name', () => {
      test('Shouldn\'t render it by default', async () => {
        expect(wrapper.find(selectors.name).exists()).toBe(false)
      })

      test('Should render it if prop `showName` is true', async () => {
        await wrapper.setProps({ showName: true })
        expect(wrapper.find(selectors.content).exists()).toBe(true)
      })
    })
  })

  describe('Style cases', () => {
    describe('User name font weight', () => {
      test('Should print it normal by default', async () => {
        await wrapper.setProps({ showName: true })
        expect(wrapper.find(selectors.name).classes('text-weight-bold')).toBe(false)
      })

      test('Should print it bold if prop `boldName` is true', async () => {
        await wrapper.setProps({ showName: true, boldName: true })
        expect(wrapper.find(selectors.name).classes('text-weight-bold')).toBe(true)
      })
    })

    describe('Card size className', () => {
      test('Should render card in `size-md` by default', () => {
        expect(wrapper.find(selectors.card).classes('size-md')).toBe(true)
        expect(wrapper.find(selectors.card).classes('size-sm')).toBe(false)
        expect(wrapper.find(selectors.card).classes('size-lg')).toBe(false)
      })

      test('Should render card in `size-sm` if prop `size` is `sm`', async () => {
        await wrapper.setProps({ size: 'sm' })

        expect(wrapper.find(selectors.card).classes('size-sm')).toBe(true)
        expect(wrapper.find(selectors.card).classes('size-md')).toBe(false)
        expect(wrapper.find(selectors.card).classes('size-lg')).toBe(false)
      })

      test('Should render card in `size-lg` if prop `size` is `lg`', async () => {
        await wrapper.setProps({ size: 'lg' })

        expect(wrapper.find(selectors.card).classes('size-lg')).toBe(true)
        expect(wrapper.find(selectors.card).classes('size-sm')).toBe(false)
        expect(wrapper.find(selectors.card).classes('size-md')).toBe(false)
      })
    })
  })
})
