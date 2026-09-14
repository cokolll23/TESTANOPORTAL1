import { Component, markRaw } from 'vue'
import { usePersonalStore } from 'stores/personal'
import { formatDate } from '@/utils'
import {
  KtPersonalInfoEditCheckbox,
  KtPersonalInfoEditDatetime,
  KtPersonalInfoEditMessengers,
  KtPersonalInfoEditSelect,
  KtPersonalInfoEditText
} from 'components/old/personal-info-views/edit'
import {
  IPersonalField,
  IUserFieldTypeId,
  IEditField,
  IEditFieldUserField
} from 'stores/personal-fields'
import { ISelectOption } from '@/models'

const userFieldType = {
  isMessenger (field: IEditFieldUserField) {
    return field.name === 'UF_MESSENGERS' && Array.isArray(field.data.fieldInfo.SETTINGS)
  },

  isCheckbox (field: IEditFieldUserField) {
    return (
      field.data.fieldInfo.USER_TYPE_ID === 'boolean'
      && !Array.isArray(field.data.fieldInfo.SETTINGS)
      && field.data.fieldInfo.SETTINGS.DISPLAY === 'CHECKBOX'
    )
  },

  isDate (field: IEditFieldUserField) {
    return field.data.fieldInfo.USER_TYPE_ID === 'date'
  },

  isEmployee (field: IEditFieldUserField) {
    return field.data.fieldInfo.USER_TYPE_ID === 'employee' || field.data.fieldInfo.USER_TYPE_ID === 'user'
  }
}

const getSelectItemName = (targetValue: any, items: ISelectOption[]) => {
  const item = items.find(item => String(item.VALUE) === String(targetValue))

  if (typeof item === 'undefined') {
    return ''
  }

  return item.NAME
}

export function useConfigs () {
  const personalStore = usePersonalStore()

  const emailRules = ['email']
  const loginRules = [
    (val: string) =>
      (val && val.length > 3) || 'Поле `Login` должно быть не меньше 3-х символов!'
  ]

  const getComponentValue = (field: IPersonalField) => {
    let VALUE = personalStore.$state[field.name] as any
    let VALUE_INITIAL
    let STRING_TAG = 'text'

    let userFieldTypeId: IUserFieldTypeId

    switch (field.type) {
      case 'datetime':
        VALUE_INITIAL = VALUE && !Number.isNaN(new Date(VALUE))
          ? formatDate(new Date(VALUE))
          : null

        return {
          value: VALUE && !Number.isNaN(new Date(VALUE))
            ? formatDate(new Date(VALUE))
            : null,
          valueInitial: VALUE_INITIAL,
          [Symbol.toStringTag]: 'datetime'
        }
      case 'list':
        VALUE_INITIAL = {
          VALUE,
          NAME: getSelectItemName(VALUE, field.data.items)
        }

        return {
          value: {
            VALUE,
            NAME: getSelectItemName(VALUE, field.data.items)
          },
          valueInitial: VALUE_INITIAL,
          [Symbol.toStringTag]: 'list'
        }
      case 'multilist':
        VALUE_INITIAL = VALUE.map((val: number) => ({
          VALUE: val,
          NAME: getSelectItemName(val, field.data.items)
        }))

        return {
          value: VALUE.map((val: number) => ({
            VALUE: val,
            NAME: getSelectItemName(val, field.data.items)
          })),
          valueInitial: VALUE_INITIAL,
          [Symbol.toStringTag]: 'multilist'
        }
      case 'timezone':
        VALUE_INITIAL = {
          timezoneValue: {
            VALUE,
            NAME: getSelectItemName(VALUE, field.data.timezone_items)
          },
          timezoneValueAuto: {
            VALUE: personalStore.$state.AUTO_TIME_ZONE,
            NAME: getSelectItemName(personalStore.$state.AUTO_TIME_ZONE, field.data.auto_timezone_items)
          }
        }

        return {
          value: {
            timezoneValue: {
              VALUE,
              NAME: getSelectItemName(VALUE, field.data.timezone_items)
            },
            timezoneValueAuto: {
              VALUE: personalStore.$state.AUTO_TIME_ZONE,
              NAME: getSelectItemName(personalStore.$state.AUTO_TIME_ZONE, field.data.auto_timezone_items)
            }
          },
          valueInitial: VALUE_INITIAL,
          [Symbol.toStringTag]: 'timezone'
        }
      case 'userField':
        userFieldTypeId = field.data.fieldInfo.USER_TYPE_ID
        STRING_TAG = 'text'

        if (userFieldTypeId === 'integer') {
          VALUE = typeof VALUE === 'number' ? VALUE : null
          VALUE_INITIAL = typeof VALUE === 'number' ? VALUE : null
        }
        else if (userFieldTypeId === 'boolean') {
          VALUE = Boolean(VALUE)
          VALUE_INITIAL = Boolean(VALUE)
        }
        else if (userFieldTypeId === 'date') {
          VALUE = VALUE && !Number.isNaN(new Date(VALUE))
            ? formatDate(new Date(VALUE))
            : null
          VALUE_INITIAL = VALUE && !Number.isNaN(new Date(VALUE))
            ? formatDate(new Date(VALUE))
            : null
          STRING_TAG = 'datetime'
        }
        else if (userFieldTypeId === 'employee') {
          if (field.data.fieldInfo.MULTIPLE === 'Y') {
            VALUE = VALUE ? VALUE.map((item: any) => (item.ID)) : []
            VALUE_INITIAL = VALUE ? VALUE.map((item: any) => (item.ID)) : []
          } else {
            VALUE = VALUE ? VALUE.ID : null
            VALUE_INITIAL = VALUE ? VALUE.ID : null
          }
        }
        else if (userFieldTypeId === 'messengers') {
          VALUE = VALUE ? VALUE.map((item: any) => ({
            VALUE: item.value,
            NAME: item.messenger,
            [item.messenger]: item.value
          })) : []
          VALUE_INITIAL = VALUE ? VALUE.map((item: any) => ({
            VALUE: item.value,
            NAME: item.messenger,
            [item.messenger]: item.value
          })) : []
          STRING_TAG = 'messengers'
        }
        else {
          VALUE = typeof VALUE === 'string' ? VALUE : ''
          VALUE_INITIAL = typeof VALUE === 'string' ? VALUE : ''
        }

        return {
          value: VALUE,
          valueInitial: VALUE_INITIAL,
          [Symbol.toStringTag]: STRING_TAG
        }
      default:
        VALUE_INITIAL = VALUE ?? ''

        return {
          value: VALUE ?? '',
          valueInitial: VALUE_INITIAL,
          [Symbol.toStringTag]: STRING_TAG
        }
    }
  }

  const getComponentPreselectedValue = (field: IPersonalField) => {
    let preselectedValue = personalStore.$state[field.name] as any

    let userFieldTypeId: IUserFieldTypeId

    switch (field.type) {
      case 'userField':
        userFieldTypeId = field.data.fieldInfo.USER_TYPE_ID

        if (userFieldTypeId === 'employee') {
          if (field.data.fieldInfo.MULTIPLE === 'Y') {
            preselectedValue = preselectedValue ? preselectedValue.map((item: any) => (['user', item.ID])) : [['user', '']]
          } else {
            preselectedValue = preselectedValue ? [['user', preselectedValue.ID]] : [['user', '']]
          }
        } else {
          preselectedValue = typeof preselectedValue === 'string' ? preselectedValue : ''
        }

        return {
          preselectedValue: preselectedValue
        }
      default:
        return {
          preselectedValue: preselectedValue ?? ''
        }
    }
  }

  const getComponentName = (field: IEditField): Component => {
    switch (field.type) {
      case 'list':
      case 'multilist':
        return markRaw(KtPersonalInfoEditSelect)
      case 'datetime':
        return markRaw(KtPersonalInfoEditDatetime)
      case 'userField':
        if (userFieldType.isMessenger(field)) {
          return markRaw(KtPersonalInfoEditMessengers)
        }
        else if (userFieldType.isCheckbox(field)) {
          return markRaw(KtPersonalInfoEditCheckbox)
        }
        else if (userFieldType.isDate(field)) {
          return markRaw(KtPersonalInfoEditDatetime)
        }

        return markRaw(KtPersonalInfoEditText)
      default:
        return markRaw(KtPersonalInfoEditText)
    }
  }
  const getComponentClassName = (field: IEditField): string => {
    if (field.type !== 'userField') {
      return `is-${field.type}-field`
    }

    switch (field.data.fieldInfo.USER_TYPE_ID) {
      case 'boolean':
        return 'is-checkbox-field'
      case 'date':
        return 'is-datetime-field'
      default:
        return 'is-text-field'
    }
  }
  const getComponentProps = (field: IEditField) => {
    const props: Record<string, unknown> = Object.create(null)

    props.label = field.title

    switch (field.type) {
      case 'text':
      case 'link':
      case 'datetime':
      case 'phone':
        if (field.name === 'LOGIN') {
          props.rules = loginRules
          props.lazyRules = true
        }
        else if (field.name === 'EMAIL') {
          props.rules = emailRules
          props.lazyRules = true
          props.errorMessage = 'Поле `Email` указано в неверном формате!'
        }

        break
      case 'list':
      case 'multilist':
        props.options = field.data.items
        props.multiple = field.type === 'multilist'

        break
      case 'userField':
        if (field.name === 'UF_MESSENGERS' && Array.isArray(field.data.fieldInfo.SETTINGS)) {
          props.options = field.data.fieldInfo.SETTINGS.map(option => ({
            NAME: option.code,
            VALUE: option.value
          }))
          props.multiple = true
        }

        if (field.data.fieldInfo.USER_TYPE_ID === 'integer') {
          props.type = 'number'
        }

        if (field.data.fieldInfo.USER_TYPE_ID === 'boolean') {
          props.yAligment = 'center'
        }

        break
    }

    return props
  }

  const isEmployeeField = userFieldType.isEmployee

  return {
    getComponentValue,
    getComponentPreselectedValue,
    getComponentName,
    getComponentClassName,
    getComponentProps,
    isEmployeeField
  }
}
