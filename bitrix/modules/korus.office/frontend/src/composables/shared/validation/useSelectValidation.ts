import { useI18n } from 'vue-i18n'

export function useSelectValidation () {
  const { t } = useI18n()

  return {
    required (val: null | undefined | number | number[]) {
      const isEmpty = val == null || (typeof val === 'number' ? val === 0 : val.length === 0)

      return !isEmpty || t('common.validation.required')
    }
  }
}
