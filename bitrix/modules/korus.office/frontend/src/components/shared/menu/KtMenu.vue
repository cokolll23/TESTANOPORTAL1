<script lang="ts" setup>
import { h, ref, useAttrs, defineSlots } from 'vue'
import { QMenu, QMenuProps, QMenuSlots } from 'quasar'

const props = withDefaults(defineProps<QMenuProps>(), {
  modelValue: null,
  target: true,
  dark: null,
  maxHeight: null,
  maxWidth: null,
  transitionShow: 'fade',
  transitionHide: 'fade',
  transitionDuration: 300
})
const slots = defineSlots<QMenuSlots>()
const qMenuRef = ref<null | typeof QMenu>(null)

defineExpose({
  qMenuRef
})

function render () {
  const attrs = useAttrs()

  return h(QMenu, {
    ...props,
    ...attrs,
    ref: qMenuRef,
    class: 'kt-menu'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
@use 'vars';

/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-menu {
    padding: var(--kt-popover-padding);
    border-radius: var(--kt-popover-border-radius);
    box-shadow: var(--kt-ui-box-shadow-base);
    background-color: var(--kt-popover-bg);
  }
}
</style>
