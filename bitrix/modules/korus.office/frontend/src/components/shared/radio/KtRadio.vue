<script lang="ts" setup>
import { ref, h, useAttrs, defineSlots } from 'vue'
import { QRadio, QRadioProps, QRadioSlots } from 'quasar'

const props = defineProps<QRadioProps>()
const slots = defineSlots<QRadioSlots>()
const qRadioRef = ref<null | typeof QRadio>(null)

defineExpose({
  qRadioRef
})

function render () {
  const attrs = useAttrs()

  return h(QRadio, {
    ...props,
    ...attrs,
    ref: qRadioRef,
    class: 'kt-radio'
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
  .kt-radio {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    &.disabled {
      opacity: 1 !important;
    }

    .q-radio__inner {
      font-size: var(--kt-ui-radio-size, 20px);
      color: var(--kt-ui-radio-border-color, #7949f4);
      border-radius: var(--kt-ui-radio-border-radius, 50%);
      background-color: var(--kt-ui-radio-bg, transparent);
      transition: box-shadow 160ms linear;
    }

    &.disabled .q-radio__inner {
      color: var(--kt-ui-field-color-disabled, #c6c6c6);
    }

    body.desktop &:not(.disabled):focus-visible .q-radio__inner {
      box-shadow: var(--kt-ui-box-shadow-focus);
    }

    .q-radio__inner--truthy {
      color: var(--kt-ui-radio-checked-bg, #7949f4);
    }

    .q-radio__bg {
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
    }

    .no-outline {
      display: none;
    }

    body.desktop &:not(.disabled) .q-radio__inner::before {
      display: none;
    }

    &.disabled .q-radio__label {
      color: var(--kt-ui-field-color-disabled, #c6c6c6);
    }
  }
}
</style>
