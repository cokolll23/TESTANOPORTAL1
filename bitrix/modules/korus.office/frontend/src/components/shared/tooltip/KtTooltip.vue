<script lang="ts" setup>
import { h, ref, useAttrs, defineSlots } from 'vue'
import { QTooltip, QTooltipProps, QTooltipSlots } from 'quasar'

const props = withDefaults(defineProps<QTooltipProps>(), {
  target: true,
  delay: 0,
  hideDelay: 0,
  maxHeight: null,
  maxWidth: null,
  modelValue: undefined,
  anchor: 'bottom middle',
  self: 'top middle',
  offset: () => ([14, 14]),
  transitionShow: 'jump-down',
  transitionHide: 'jump-up',
  transitionDuration: 300
})
const slots = defineSlots<QTooltipSlots>()

const qTooltipRef = ref<null | typeof QTooltip>(null)

defineExpose({
  qTooltipRef
})

function render () {
  const attrs = useAttrs()

  return h(QTooltip, {
    ...props,
    ...attrs,
    ref: qTooltipRef,
    class: 'kt-tooltip'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-tooltip {
    max-width: 300px;
    padding: 8px;
    font-size: 14px;

    &.q-tooltip--style {
      color: var(--kt-ui-text-primary);
      background-color: var(--kt-ui-white);
      box-shadow: var(--kt-ui-box-shadow-base);
    }
  }
}
</style>
