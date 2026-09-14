<script setup lang="ts">
import { h, ref, useAttrs, defineSlots } from 'vue'
import { QToggle, QToggleProps, QToggleSlots } from 'quasar'

const props = defineProps<QToggleProps>()
const slots = defineSlots<QToggleSlots>()

const qToggleRef = ref<QToggle>()

defineExpose({
  qToggleRef
})

function render () {
  const attrs = useAttrs()

  return h(QToggle, {
    ...props,
    ...attrs,
    ref: qToggleRef,
    class: 'kt-toggle'
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
  .kt-toggle {
    &.disabled {
      opacity: 1 !important;
    }

    .q-toggle__inner {
      width: 32px;
      height: 16px;
      min-width: initial;
      padding: 0;
      color: #8d8d8d;
    }

    &.disabled .q-toggle__inner {
      color: #c6c6c6;
    }

    &:focus-visible .q-toggle__inner {
      border-radius: 12px;
      box-shadow: 0 0 0 1px #ffffff,
      0 0 0 3px #7949f4;
    }

    .q-toggle__inner--truthy {
      color: var(--kt-icon-accented, #7949F4);
    }

    .q-toggle__inner--truthy .q-toggle__track {
      opacity: 1;
    }

    .q-toggle__inner--truthy .q-toggle__thumb {
      left: 19px;
    }

    .q-toggle__inner--truthy .q-toggle__thumb::after {
      background: #ffffff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='6' height='6' fill='none'%3E%3Cpath fill='%23161616' stroke='%237949F4' d='M2.438 4.5.75 2.812l.265-.265L2.438 3.97l2.547-2.548.265.265L2.437 4.5Z'/%3E%3C/svg%3E") no-repeat 50% 50%;
    }

    &.disabled .q-toggle__inner--truthy .q-toggle__thumb::after {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='6' height='6' fill='none'%3E%3Cpath fill='%238D8D8D' stroke='%238D8D8D' d='M2.438 4.5.75 2.812l.265-.265L2.438 3.97l2.547-2.548.265.265L2.437 4.5Z'/%3E%3C/svg%3E");
    }

    .q-toggle__track {
      height: 16px;
      border-radius: 12px;
    }

    .q-toggle__thumb {
      width: 10px;
      height: 10px;
      top: 50%;
      left: 3px;
      transform: translateY(-50%);
    }

    .q-toggle__thumb::after {
      box-shadow: none;
    }

    &.disabled .q-toggle__thumb::after {
      background-color: transparent;
    }

    body.desktop &:not(.disabled):focus .q-toggle__thumb::before,
    body.desktop &:not(.disabled):hover .q-toggle__thumb::before {
      transform: unset;
    }

    .q-toggle__label {
      margin-left: 8px;
      font-family: 'IBM Plex Sans', serif;
      font-size: 14px;
      font-style: normal;
      font-weight: 400;
      line-height: 18px;
      color: var(--kt-text-primary, #161616);
    }

    &.disabled .q-toggle__label {
      color: #c6c6c6;
    }
  }
}
</style>
