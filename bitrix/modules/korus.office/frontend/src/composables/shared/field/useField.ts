import { ref, Ref, watch } from 'vue'
import { QField, QFieldProps, useFormChild } from 'quasar'

export function useField (qFieldRef: Ref<undefined | QField>, props: QFieldProps) {
  const isDirtyModel = ref(false)

  if (props.lazyRules === 'ondemand') {
    watch(() => props.modelValue, () => {
      if (isDirtyModel.value) {
        qFieldRef.value?.validate(props.modelValue)
      }
    })
  }

  useFormChild({
    validate,
    resetValidation,
    requiresQForm: false
  })

  function validate () {
    isDirtyModel.value = true

    if (qFieldRef.value) {
      return qFieldRef.value.validate()
    }

    return true
  }

  function resetValidation () {
    isDirtyModel.value = false

    return qFieldRef.value?.resetValidation()
  }

  return {
    validate,
    resetValidation
  }
}
