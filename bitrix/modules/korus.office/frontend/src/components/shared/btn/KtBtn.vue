<script lang="ts" setup>
import { h, computed, useAttrs, defineSlots, type VNode } from 'vue'
import { QBtn, QBtnProps, QBtnSlots } from 'quasar'
import { omit } from 'lodash-es'
import { useBtn, KtBtnProps } from '@/composables/shared'
import { KtIcon } from '@/components/shared'

interface IProps extends KtBtnProps, QBtnProps {}

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

const slots = defineSlots<QBtnSlots>()
const { layoutClasses } = useBtn(props)

function render () {
  const attrs = useAttrs()

  return h(QBtn, {
    ...propsBtn.value,
    ...attrs,
    icon: undefined,
    iconRight: undefined,
    class: ['kt-btn', layoutClasses.value]
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
