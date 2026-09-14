import { boot } from 'quasar/wrappers'
import * as validators from '@vuelidate/validators'
import { i18n } from '@/utils/i18n'
import { ISelectorItemValue } from '@/components/shared/selector'

export const $vuelidate = {
  required (params: {
    message?: string;
  }) {
    const validator = validators.required
    const message = params.message ?? i18n.t('common.validation.required')

    return (val: unknown) => {
      return validator.$validator(val, null, null) || message
    }
  },
  numeric (params: {
    message?: string;
  }) {
    const validator = validators.numeric
    const message = params.message ?? i18n.t('common.validation.numeric')

    return (val: unknown) => {
      return validator.$validator(val, null, null) || message
    }
  },
  integer (params: {
    message?: string;
  }) {
    const validator = validators.integer
    const message = params.message ?? i18n.t('common.validation.integer')

    return (val: unknown) => {
      return validator.$validator(val, null, null) || message
    }
  },
  decimal (params: {
    message?: string;
  }) {
    const validator = validators.decimal
    const message = params.message ?? i18n.t('common.validation.decimal')

    return (val: unknown) => {
      return validator.$validator(val, null, null) || message
    }
  },
  minValue (params: {
    value:    number;
    message?: string;
  }) {
    const validator = validators.minValue
    const value = params.value
    const message = params.message ?? i18n.t('common.validation.minValue').replace('#MIN_VALUE#', String(value))

    return (val: unknown) => {
      return validator(value).$validator(val, null, null) || message
    }
  },
  maxValue (params: {
    value:    number;
    message?: string;
  }) {
    const validator = validators.maxValue
    const value = params.value
    const message = params.message ?? i18n.t('common.validation.maxValue').replace('#MAX_VALUE#', String(value))

    return (val: unknown) => {
      return validator(value).$validator(val, null, null) || message
    }
  },
  minLength (params: {
    length:   number;
    message?: string;
  }) {
    const validator = validators.minLength
    const length = params.length
    const message = params.message ?? i18n.t('common.validation.minLength').replace('#LENGTH#', String(length))

    return (val: unknown) => {
      return validator(length).$validator(val, null, null) || message
    }
  },
  maxLength (params: {
    length:   number;
    message?: string;
  }) {
    const validator = validators.maxLength
    const length = params.length
    const message = params.message ?? i18n.t('common.validation.maxLength').replace('#LENGTH#', String(length))

    return (val: unknown) => {
      return validator(length).$validator(val, null, null) || message
    }
  },
  selector (params: {
    message?: string;
  }) {
    return (val: null | undefined | ISelectorItemValue | ISelectorItemValue[]) => {
      const isEmpty = (
        val == null
        || (!Array.isArray(val) && typeof val.id === 'undefined')
        || (Array.isArray(val) && val.length === 0)
      )
      const message = params.message ?? i18n.t('common.validation.required')

      return !isEmpty || message
    }
  }
}

export default boot(({ app }) => {
  app.config.globalProperties.$vuelidate = $vuelidate
})
