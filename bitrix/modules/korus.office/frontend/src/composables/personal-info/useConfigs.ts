import { markRaw } from 'vue'
import { cloneDeep } from 'lodash-es'
import { usePersonalStore } from '@/stores/personal'
import type { ISelectOption } from '@/models'

import { KtSelect, KtDatepicker, KtCheckbox, KtInput, KtSelector } from '@/components/shared'
import {
  KtContactEditMessengers,
  KtContactEditTimezone,
  KtContactEditWorkplace
} from '@/components/widgets/personal-info/components'
import {
  IPersonalField,
  IEditField,
  IEditFieldUserField
} from 'stores/personal-fields'

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
  },

  isWorkplace (field: IEditFieldUserField) {
    return field.name === 'UF_WORKPLACE'
  }
}

function getSelectItemName (targetValue: any, items: ISelectOption[]) {
  const item = items.find(item => String(item.VALUE) === String(targetValue))

  if (typeof item === 'undefined') {
    return ''
  }

  return item.NAME
}

export function useConfigs () {
  const personalStore = usePersonalStore()

  const loginRules = [
    (val: string) =>
      (val && val.length > 3) || 'Поле `Login` должно быть не меньше 3-х символов!'
  ]

  const getComponentValue = (field: IPersonalField) => {
    const VALUE = personalStore.$state[field.name] as any

    switch (field.type) {
      case 'datetime': {
        const format = 'd.m.Y'
        const timestamp = new Date(VALUE).getTime() / 1000
        const isEmpty = !VALUE || Number.isNaN(new Date(VALUE))
        const value = !isEmpty ? BX.date.format(format, timestamp) : null
        return {
          value,
          valueInitial: cloneDeep(value),
          [Symbol.toStringTag]: 'datetime'
        }
      }
      case 'list': {
        const value = {
          VALUE,
          NAME: getSelectItemName(VALUE, field.data.items)
        }

        return {
          value,
          valueInitial: cloneDeep(value),
          [Symbol.toStringTag]: 'list'
        }
      }
      case 'multilist': {
        const value = VALUE.map((val: number) => ({
          VALUE: val,
          NAME: getSelectItemName(val, field.data.items)
        }))

        return {
          value,
          valueInitial: cloneDeep(value),
          [Symbol.toStringTag]: 'multilist'
        }
      }
      case 'timezone': {
        const value = {
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
          value,
          valueInitial: cloneDeep(value),
          [Symbol.toStringTag]: 'timezone'
        }
      }
      case 'userField': {
        let value
        let stringTag = 'text'

        switch (field.data.fieldInfo.USER_TYPE_ID) {
          case 'integer':
            value = typeof VALUE === 'number' ? VALUE : null
            break
          case 'boolean':
            value = Boolean(VALUE)
            break
          case 'date': {
            const format = 'd.m.Y'
            const timestamp = new Date(VALUE).getTime() / 1000
            const isEmpty = !VALUE || Number.isNaN(new Date(VALUE))

            value = isEmpty ? BX.date.format(format, timestamp) : null
            stringTag = 'datetime'
            break
          }
          case 'employee': {
            if (field.data.fieldInfo.MULTIPLE === 'Y') {
              value = VALUE ? VALUE.map((item: any) => (item.ID)) : []
            }
            else {
              value = VALUE ? VALUE.ID : null
            }
            break
          }
          case 'messengers': {
            value = VALUE ? VALUE.map((item: any) => ({
              VALUE: item.value,
              NAME: item.messenger,
              [item.messenger]: item.value
            })) : []
            stringTag = 'messengers'
            break
          }
          default:
            value = typeof VALUE === 'string' ? VALUE : ''
        }

        return {
          value,
          valueInitial: cloneDeep(value),
          [Symbol.toStringTag]: stringTag
        }
      }
      default: {
        const value = VALUE ?? ''

        return {
          value,
          valueInitial: cloneDeep(value),
          [Symbol.toStringTag]: 'text'
        }
      }
    }
  }

  function getComponentPreselectedValue (field: IPersonalField) {
    let preselectedValue = personalStore.$state[field.name] as any

    switch (field.type) {
      case 'userField':
        if (field.data.fieldInfo.USER_TYPE_ID === 'employee') {
          if (field.data.fieldInfo.MULTIPLE === 'Y') {
            preselectedValue = preselectedValue ? preselectedValue.map((item: any) => (['user', item.ID])) : [['user', '']]
          }
          else {
            preselectedValue = preselectedValue ? [['user', preselectedValue.ID]] : [['user', '']]
          }
        }
        else {
          preselectedValue = typeof preselectedValue === 'string' ? preselectedValue : ''
        }

        return {
          preselectedValue
        }
      default:
        return {
          preselectedValue: preselectedValue ?? ''
        }
    }
  }

  function getComponentName (field: IEditField) {
    switch (field.type) {
      case 'list':
      case 'multilist':
        return markRaw(KtSelect)
      case 'datetime':
        return markRaw(KtDatepicker)
      case 'timezone':
        return markRaw(KtContactEditTimezone)
      case 'userField':
        if (userFieldType.isMessenger(field)) {
          return markRaw(KtContactEditMessengers)
        }
        else if (userFieldType.isCheckbox(field)) {
          return markRaw(KtCheckbox)
        }
        else if (userFieldType.isDate(field)) {
          return markRaw(KtDatepicker)
        }
        else if (userFieldType.isEmployee(field)) {
          return markRaw(KtSelector)
        }
        else if (userFieldType.isWorkplace(field)) {
          return markRaw(KtContactEditWorkplace)
        }

        return markRaw(KtInput)
      default:
        return markRaw(KtInput)
    }
  }

  function getComponentProps (field: IEditField) {
    const props: Record<string, unknown> = Object.create(null)
    const staticFieldsCodes = ['NAME', 'LAST_NAME', 'SECOND_NAME']

    switch (field.type) {
      case 'text':
      case 'link':
      case 'datetime':
      case 'phone':
        props.modelValue = field.value
        props['onUpdate:modelValue'] = (event: string) => {
          field.value = event
        }

        if (staticFieldsCodes.includes(field.name)) {
          props.disable = true
        }

        if (field.name === 'LOGIN') {
          props.rules = loginRules
          props.lazyRules = true
        }
        else if (field.name === 'EMAIL') {
          props.rules = ['email']
          props.lazyRules = true
          props.errorMessage = 'Поле `Email` указано в неверном формате!'
        }

        break
      case 'list':
      case 'multilist':
        props.modelValue = field.value
        props['onUpdate:modelValue'] = (event: ISelectOption) => {
          field.value = event
        }
        props.optionValue = 'VALUE'
        props.optionLabel = 'NAME'
        props.options = field.data.items
        props.multiple = field.type === 'multilist'
        props.hideBottomSpace = true

        break
      case 'timezone':
        props.timezone = field.value.timezoneValue
        props.timezoneAuto = field.value.timezoneValueAuto
        props.timezoneItems = field.data.timezone_items
        props.timezoneItemsAuto = field.data.auto_timezone_items
        props['onUpdate:timezone'] = (event: ISelectOption) => {
          field.value.timezoneValue = event
        }
        props['onUpdate:timezoneAuto'] = (event: ISelectOption) => {
          field.value.timezoneValueAuto = event
        }
        break
      case 'userField':
        props.modelValue = field.value
        props['onUpdate:modelValue'] = (event: any) => {
          field.value = event
        }

        if (field.name === 'UF_MESSENGERS' && Array.isArray(field.data.fieldInfo.SETTINGS)) {
          props.options = field.data.fieldInfo.SETTINGS.map(option => ({
            NAME: option.code,
            VALUE: option.value
          }))
          props.multiple = true
        }

        if (userFieldType.isEmployee(field)) {
          props.id = field.name
          props.entities = [{ id: 'user' }]
          props.preselectedItems = field.preselectedValue
          props.multiple = field.data.fieldInfo.MULTIPLE === 'Y'
          props.targetElement = null
          props.showPopupByFieldClick = true
          props.showDropdownIcon = true
          props.showActionMoreBtn = props.multiple
          props.hideBottomSpace = true
        }

        if (field.data.fieldInfo.USER_TYPE_ID === 'integer') {
          props.type = 'number'
        }

        break
    }

    return props
  }

  return {
    getComponentValue,
    getComponentPreselectedValue,
    getComponentName,
    getComponentProps
  }
}
