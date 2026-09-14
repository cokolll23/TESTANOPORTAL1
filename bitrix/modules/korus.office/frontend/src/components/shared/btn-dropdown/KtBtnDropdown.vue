<script lang="ts" setup>
import { h, computed, useAttrs, defineSlots, type VNode } from 'vue'
import { QBtnDropdown, QBtnDropdownProps, QBtnDropdownSlots } from 'quasar'
import { omit } from 'lodash-es'
import { useBtn, KtBtnProps } from '@/composables/shared'
import { KtIcon } from '@/components/shared'

interface IProps extends KtBtnProps, QBtnDropdownProps {}

const props = withDefaults(defineProps<IProps>(), {
  theme: 'primary',
  unelevated: true,
  rounded: true,
  noCaps: true,
  iconFit: 'auto',
  iconRightFit: 'auto'
})
const propsBtn = computed(() => {
  return omit(props, ['theme', 'iconFit', 'iconRightFit'])
})

const slots = defineSlots<QBtnDropdownSlots>()
const { layoutClasses } = useBtn(props)

function render () {
  const attrs = useAttrs()

  return h(QBtnDropdown, {
    ...attrs,
    ...propsBtn.value,
    icon: undefined,
    iconRight: undefined,
    dropdownIcon: 'none',
    class: ['kt-btn', 'kt-btn-dropdown', layoutClasses.value]
  }, {
    ...slots,
    default: () => {
      const content: VNode[] = []

      if (props.icon) {
        content.push(h(KtIcon, { name: props.icon, fit: props.iconFit }))
      }

      if (typeof slots.default === 'function') {
        content.push(...slots.default())
      }

      if (props.iconRight) {
        content.push(h(KtIcon, { name: props.iconRight, fit: props.iconRightFit }))
      }

      return content
    }
  })
}
</script>

<template>
  <render />
</template>
