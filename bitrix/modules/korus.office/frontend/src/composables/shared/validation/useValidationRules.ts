import { computed } from 'vue'
import { QInputProps } from 'quasar'
import { $vuelidate } from '@/boot/vuelidate-rules'

export function useValidationRules (rules?: QInputProps['rules'], validation?: (keyof typeof $vuelidate)[]) {
  return {
    rules: computed(() => {
      rules = rules ?? []
      validation = validation ?? []

      return [
        ...rules,
        ...validation
          .map(ruleName => {
            const [name, args] = ruleName.split('|')
            const ruleFn = $vuelidate[name as typeof ruleName]
            const params = Object.create(null)

            if (args) {
              args.split('&').reduce((acc, pair) => {
                const [key, value] = pair.split('=')
                acc[key] = value

                return acc
              }, params)
            }

            if (ruleFn) {
              return ruleFn(params)
            }

            return null
          })
          .filter(Boolean)
      ]
    })
  }
}
