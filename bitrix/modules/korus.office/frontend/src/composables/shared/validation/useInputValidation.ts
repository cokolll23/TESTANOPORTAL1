import { useI18n } from 'vue-i18n'

export function useInputValidation () {
  const { t } = useI18n()

  return {
    required: {
      text (val: null | undefined | string) {
        return !!val || t('common.validation.required')
      }
    }
  }
}
