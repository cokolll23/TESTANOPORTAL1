import { getType, ObjectKeys } from '@/utils'
import { ISelectOption } from '@/models'
import { IEditFields, IEditFieldTimezone } from 'stores/personal-fields'

type IPrimitiveFieldValue    = string | number | boolean | null;
type IEditFieldTimezoneValue = IEditFieldTimezone['value'];

const getChangedPrimitiveField = (current: IPrimitiveFieldValue, initial: IPrimitiveFieldValue) => {
  return (current !== initial) ? current : null
}
const getChangedMessengers = (current: any, initial: any) => {
  if (JSON.stringify(current) !== JSON.stringify(initial)) {
    return current.map((val: any) => ({
      value: val[val.NAME],
      messenger: val.NAME
    }))
  }

  return null
}
const getChangedList = (current: ISelectOption, initial: ISelectOption) => {
  if (current.VALUE !== initial.VALUE) {
    return current.VALUE
  }

  return null
}
const getChangedMultiList = (current: ISelectOption[], initial: ISelectOption[]) => {
  const valueList = current.map(option => option.VALUE)
  const valueListInitial = initial.map(option => option.VALUE)

  if (JSON.stringify(valueList) !== JSON.stringify(valueListInitial)) {
    return valueList
  }

  return null
}
const getChangedTimezone = (current: IEditFieldTimezoneValue, initial: IEditFieldTimezoneValue) => {
  if (current.timezoneValue?.VALUE !== initial.timezoneValue?.VALUE || current.timezoneValueAuto.VALUE !== initial.timezoneValueAuto.VALUE) {
    return {
      AUTO_TIME_ZONE: String(current.timezoneValueAuto.VALUE),
      TIME_ZONE: String(current.timezoneValue?.VALUE)
    }
  }

  return null
}

export function useChanges () {
  return {
    getChangedFields (data: IEditFields) {
      let i = -1
      const result = []

      while (++i < data.length) {
        let changes: any = null
        const { name, value, valueInitial } = data[i]

        switch (getType(data[i])) {
          case 'timezone':
            changes = getChangedTimezone(
              value as IEditFieldTimezoneValue,
              valueInitial as IEditFieldTimezoneValue
            )
            break
          case 'messengers':
            changes = getChangedMessengers(value, valueInitial)
            break
          case 'list':
            changes = getChangedList(
              value as ISelectOption,
              valueInitial as ISelectOption
            )
            break
          case 'multilist':
            changes = getChangedMultiList(
              value as ISelectOption[],
              valueInitial as ISelectOption[]
            )
            break
          default:
            changes = getChangedPrimitiveField(
              value as IPrimitiveFieldValue,
              valueInitial as IPrimitiveFieldValue
            )
        }

        if (changes !== null) {
          result.push({
            name,
            changes,
            [Symbol.toStringTag]: data[i][Symbol.toStringTag]
          })
        }
      }

      return result
    }
  }
}
