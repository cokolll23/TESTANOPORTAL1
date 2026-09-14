<script lang="ts" setup>
import { h, ref, computed, useAttrs, defineSlots } from 'vue'
import { QInput, QInputProps, QInputSlots } from 'quasar'
import { omit } from 'lodash-es'
import { $vuelidate } from '@/boot/vuelidate-rules'
import { useField, useValidationRules } from '@/composables/shared'

interface IProps extends QInputProps {
  textMode?:   boolean;
  validation?: (keyof typeof $vuelidate)[];
}

const props = withDefaults(defineProps<IProps>(), {
  error: null,
  lazyRules: false,
  type: 'text',
  dark: null
})
const propsInput = computed(() => {
  return omit(props, ['textMode', 'validation'])
})

const slots = defineSlots<QInputSlots>()
const qInputRef = ref<QInput>()

const { validate, resetValidation } = useField(qInputRef, props)
const { rules } = useValidationRules(props.rules, props.validation)

defineExpose({
  qInputRef,
  validate,
  resetValidation
})

function render () {
  const attrs = useAttrs()

  return h(QInput, {
    ...propsInput.value,
    ...attrs,
    rules: propsInput.value?.readonly !== true ? rules.value : [],
    ref: qInputRef,
    class: {
      'kt-input': true,
      'q-field--text-mode': props.textMode,
      'kt-textarea': props.type === 'textarea'
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
  .kt-input {
    &.q-textarea.q-field--dense.q-field--labeled .q-field__control-container {
      padding-top: 5px;
    }
  }
}
</style>
