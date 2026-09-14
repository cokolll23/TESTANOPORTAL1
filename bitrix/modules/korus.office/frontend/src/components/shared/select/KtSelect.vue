<script lang="ts" setup>
import { h, ref, computed, useAttrs, defineSlots } from 'vue'
import { QSelect, QItem, QItemSection, QSelectProps, QSelectSlots } from 'quasar'
import { omit } from 'lodash-es'
import { $vuelidate } from '@/boot/vuelidate-rules'
import { useField, useValidationRules } from '@/composables/shared'

interface IProps extends QSelectProps {
  textMode?:   boolean;
  validation?: (keyof typeof $vuelidate)[];
}

const props = withDefaults(defineProps<IProps>(), {
  error: null,
  lazyRules: false,
  transitionShow: 'fade',
  transitionHide: 'fade',
  transitionDuration: 300,
  behavior: 'default',
  inputDebounce: 500,
  tabindex: 0,
  options: () => ([]),
  optionValue: 'value',
  optionLabel: 'label',
  optionDisable: 'disable',
  optionsDark: null,
  dark: null,
  virtualScrollSliceSize: 10,
  virtualScrollSliceRatioBefore: 1,
  virtualScrollSliceRatioAfter: 1,
  virtualScrollItemSize: 24,
  virtualScrollStickySizeStart: 0,
  virtualScrollStickySizeEnd: 0
})
const propsSelect = computed(() => {
  return omit(props, ['textMode', 'validation'])
})

const slots = defineSlots<QSelectSlots>()
const qSelectRef = ref<QSelect>()

const { validate, resetValidation } = useField(qSelectRef, props)
const { rules } = useValidationRules(props.rules, props.validation)

defineExpose({
  qSelectRef,
  validate,
  resetValidation
})

function render () {
  const attrs = useAttrs()

  if (typeof slots.option !== 'function') {
    slots.option = function (scope) {
      const label = typeof props.optionLabel === 'function'
        ? props.optionLabel(scope.opt)
        : props.optionLabel

      return [
        h(QItem, {
          ...scope.itemProps,
          class: [
            'kt-select-option',
            {
              'is-active': scope.selected
            }
          ]
        }, h(QItemSection, null, scope.opt != null && typeof scope.opt === 'object' ? scope.opt[label] : scope.opt))
      ]
    }
  }

  const rootElement = document.querySelector(':root')!
  const dropdownTop = getComputedStyle(rootElement).getPropertyValue('--kt-ui-dropdown-top')

  return h(QSelect, {
    ...propsSelect.value,
    ...attrs,
    rules: propsSelect.value?.readonly !== true ? rules.value : [],
    ref: qSelectRef,
    dropdownIcon: 'none',
    useChips: props.multiple,
    popupContentClass: 'kt-select-dropdown',
    menuOffset: [0, dropdownTop ? parseInt(dropdownTop) : 0],
    class: {
      'kt-select': true,
      'q-field--text-mode': props.textMode
    }
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
  .kt-select {
    &.q-select--multiple .q-field__marginal {
      align-self: center;
    }

    &.q-select--multiple .q-field__native {
      padding-top: 4px;
      padding-bottom: 4px;
      margin: 0 0 calc(-1 * var(--kt-ui-dropdown-choice-offset)) calc(-1 * var(--kt-ui-dropdown-choice-offset));
    }

    &.q-select--multiple .q-field__input {
      margin: 0 0 var(--kt-ui-dropdown-choice-offset) var(--kt-ui-dropdown-choice-offset);
      padding: 0;
    }

    .q-chip {
      max-width: 24ch;
      display: inline-flex;
      height: var(--kt-ui-dropdown-choice-height);
      line-height: var(--kt-ui-dropdown-choice-height);
      margin: 0 var(--kt-ui-dropdown-choice-offset) var(--kt-ui-dropdown-choice-offset) 0;
      padding: var(--kt-ui-dropdown-choice-padding);
      border: var(--kt-ui-dropdown-choice-border);
      border-radius: var(--kt-ui-dropdown-choice-border-radius);
      color: var(--kt-ui-dropdown-choice-color);
      background-color: var(--kt-ui-dropdown-choice-bg);
      transition: background-color 160ms linear;
    }

    .q-chip:hover {
      background-color: var(--kt-ui-dropdown-choice-bg-hover);
    }

    .q-chip:active {
      background-color: var(--kt-ui-dropdown-choice-bg-active);
    }

    .q-chip__icon--remove {
      width: var(--kt-ui-dropdown-choice-remove-size);
      border: none;
      padding: 0;
      position: absolute;
      right: 8px;
      left: unset;
      mask: var(--kt-ui-dropdown-choice-remove-icon) no-repeat 50%;
      color: var(--kt-ui-dropdown-choice-remove-color);
      background-color: var(--kt-ui-dropdown-choice-remove-color);
    }

    .q-select__dropdown-icon {
      transform: initial !important;
      mask: var(--kt-ui-select-arrow) no-repeat 50%;
      background-color: var(--kt-ui-select-arrow-color);
    }

    &-dropdown {
      padding: var(--kt-ui-dropdown-padding);
      border: var(--kt-ui-dropdown-border);
      border-radius: var(--kt-ui-dropdown-border-radius);
      background-color: var(--kt-ui-dropdown-bg);
      box-shadow: var(--kt-ui-dropdown-box-shadow);
    }

    &-dropdown[aria-multiselectable='true'] &-option.is-active::after {
      content: '';
      width: var(--kt-ui-dropdown-option-check-size);
      height: var(--kt-ui-dropdown-option-check-size);
      margin-left: auto;
      mask: var(--kt-ui-dropdown-option-check-icon);
      background-color: var(--kt-ui-dropdown-option-check-color);
    }

    &-option {
      display: flex;
      align-items: center;
      gap: 8px;
      min-height: unset;
      padding: var(--kt-ui-dropdown-option-padding);
      font-size: var(--kt-ui-dropdown-option-font-size);
      line-height: var(--kt-ui-dropdown-option-line-height);
      border-bottom: none;
      border-radius: var(--kt-ui-dropdown-option-border-radius);
      color: var(--kt-ui-dropdown-option-color);
      background-color: var(--kt-ui-dropdown-option-bg);

      &:hover,
      &.is-active {
        color: var(--kt-ui-dropdown-option-color-highlighted);
        background-color: var(--kt-ui-dropdown-option-bg-highlighted);
      }
    }

    &-option + &-option {
      margin-top: var(--kt-ui-dropdown-option-offset);
    }
  }
}
</style>
