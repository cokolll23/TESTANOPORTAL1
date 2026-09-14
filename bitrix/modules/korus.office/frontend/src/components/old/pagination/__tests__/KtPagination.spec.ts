import { describe, it, beforeEach, expect, vi } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { Quasar, QPagination, QInput } from 'quasar'
import { KtPagination } from 'components/pagination'

describe('KtPagination.vue', () => {
  const elementRefs = {
    prevBtn : 'kt-pagination-prev-btn',
    nextBtn : 'kt-pagination-next-btn'
  }
  const requiredOptions = {
    modelValue: 1,
    prevBtnHandler: vi.fn(),
    nextBtnHandler: vi.fn(),
    maxPage: 10
  }
  let wrapper: VueWrapper

  beforeEach(() => {
    wrapper = mount(KtPagination, {
      props: {
        ...requiredOptions
      },
      global: {
        plugins: [Quasar]
      }
    })
  })

  describe('RENDER', () => {
    it('Should render previous pagination button', () => {
      expect(wrapper.findComponent({
        ref: elementRefs.prevBtn
      }).exists()).toBe(true)
    })

    it('Should render next pagination button', () => {
      expect(wrapper.findComponent({
        ref: elementRefs.nextBtn
      }).exists()).toBe(true)
    })

    it('Should render q-pagination component', () => {
      expect(wrapper.findComponent(QPagination).exists()).toBe(true)
    })

    it('Should disable previous pagination button if `props.isPrevDisabled` is true', async () => {
      const prevPaginationBtn = wrapper.findComponent({
        ref: elementRefs.prevBtn
      })

      expect(prevPaginationBtn.props('disable')).toBe(false)

      await wrapper.setProps({ isPrevDisabled: true })
      expect(prevPaginationBtn.props('disable')).toBe(true)
    })

    it('Should disable next pagination button if `props.isNextDisabled` is true', async () => {
      const nextPaginationBtn = wrapper.findComponent({
        ref: elementRefs.nextBtn
      })

      expect(nextPaginationBtn.props('disable')).toBe(false)

      await wrapper.setProps({ isNextDisabled: true })
      expect(nextPaginationBtn.props('disable')).toBe(true)
    })
  })

  describe('METHODS', () => {
    it('Should call callback from `props.prevBtnHandler` on previous pagination button click', async () => {
      const prevPaginationBtn = wrapper.findComponent({
        ref: elementRefs.prevBtn
      })

      expect(requiredOptions.prevBtnHandler).not.toHaveBeenCalled()

      await prevPaginationBtn.trigger('click')
      expect(requiredOptions.prevBtnHandler).toHaveBeenCalledTimes(1)
    })

    it('Should call callback from `props.nextBtnHandler` on next pagination button click', async () => {
      const nextPaginationBtn = wrapper.findComponent({
        ref: elementRefs.nextBtn
      })

      expect(requiredOptions.nextBtnHandler).not.toHaveBeenCalled()

      await nextPaginationBtn.trigger('click')
      expect(requiredOptions.nextBtnHandler).toHaveBeenCalledTimes(1)
    })
  })

  describe('EMITS', () => {
    it('Should emit `update:modelValue` event on change active page', async () => {
      await wrapper.setProps({ modelValue: 2 })
    })
  })
})
