import { useI18n } from 'vue-i18n'
import { ISelectorItemValue } from '@/components/shared'

export function useSelectorValidation () {
  const { t } = useI18n()

  return {
    required (val: null | undefined | ISelectorItemValue | ISelectorItemValue[]) {
      const isEmpty = (
        val == null
        || (!Array.isArray(val) && typeof val.id === 'undefined')
        || (Array.isArray(val) && val.length === 0)
      )

      return !isEmpty || t('common.validation.required')
    }
  }
}
