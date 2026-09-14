<script lang="ts" setup>
import { ref, h, useAttrs, defineSlots } from 'vue'
import { QCheckbox, QCheckboxProps, QCheckboxSlots } from 'quasar'
import { getIconSource } from '@/components/shared/icon/getSource'

const props = defineProps<QCheckboxProps>()
const slots = defineSlots<QCheckboxSlots>()
const qCheckboxRef = ref<null | typeof QCheckbox>(null)

defineExpose({
  qCheckboxRef
})

function render () {
  const indeterminateIcon: QCheckboxProps['indeterminateIcon'] = getIconSource('remove')
  const attrs = useAttrs()

  return h(QCheckbox, {
    ...props,
    ...attrs,
    checkedIcon: 'none',
    indeterminateIcon,
    ref: qCheckboxRef,
    class: 'kt-checkbox'
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
  .kt-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    &.disabled {
      opacity: 1 !important;
    }

    .q-checkbox__inner {
      font-size: var(--kt-ui-checkbox-size, 20px);
      color: var(--kt-ui-checkbox-checked-bg, #7949f4);
      border-radius: var(--kt-ui-checkbox-border-radius, 4px);
      transition: box-shadow 160ms linear;
    }

    &.disabled .q-checkbox__inner {
      color: var(--kt-ui-field-color-disabled, #c6c6c6);
    }

    body.desktop &:not(.disabled):focus-visible .q-checkbox__inner {
      box-shadow: var(--kt-ui-box-shadow-focus);
    }

    .q-checkbox__inner--truthy {
      color: var(--kt-ui-checkbox-checked-bg, #7949f4);
    }

    .q-checkbox__inner--indet,
    .q-checkbox__inner--truthy .q-checkbox__icon {
      background-color: var(--kt-ui-white);
    }

    .q-checkbox__icon-container {
      border: 1.5px solid var(--kt-ui-checkbox-border-color, #7949f4);
      border-radius: var(--kt-ui-checkbox-border-radius, 4px);
      background-color: var(--kt-ui-checkbox-checked-bg, #7949f4);
    }

    .q-checkbox__icon-container .q-checkbox__icon {
      mask-image: var(--kt-ui-checkbox-icon-checked);
      mask-repeat: no-repeat;
      mask-position: 50% 50%;
    }

    &.disabled .q-checkbox__icon-container {
      border-color: var(--kt-ui-field-color-disabled, #c6c6c6);
      background-color: var(--kt-ui-field-color-disabled, #c6c6c6);
    }

    .q-checkbox__bg {
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      border: 1.5px solid currentColor;
      border-radius: var(--kt-ui-checkbox-border-radius, 4px);
    }

    .no-outline {
      display: none;
    }

    body.desktop &:not(.disabled) .q-checkbox__inner::before {
      display: none;
    }

    &.disabled .q-checkbox__label {
      color: var(--kt-ui-field-color-disabled, #c6c6c6);
    }
  }
}
</style>
