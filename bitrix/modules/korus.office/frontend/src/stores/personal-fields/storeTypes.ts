import { ISelectOption } from '@/models'
import { IUfMessenger } from 'stores/types'
import {
  IPersonalFieldDatetime,
  IPersonalFieldLink,
  IPersonalFieldList,
  IPersonalFieldMultiList,
  IPersonalFieldPhone,
  IPersonalFieldsResponse,
  IPersonalFieldText,
  IPersonalFieldTimezone,
  IPersonalFieldUserType
} from './apiResponseTypes'

export interface IPersonalFieldsState {
  items: IPersonalFieldsResponse;
}

export type IEditFieldText = (IPersonalFieldText & {
  value: string;
  valueInitial: string;
  [Symbol.toStringTag]: string;
})

export type IEditFieldPhone = (IPersonalFieldPhone & {
  value: string;
  valueInitial: string;
  [Symbol.toStringTag]: string;
})

export type IEditFieldLink = (IPersonalFieldLink & {
  value: string;
  valueInitial: string;
  [Symbol.toStringTag]: string;
})

export type IEditFieldDatetime = (IPersonalFieldDatetime & {
  value: string | null;
  valueInitial: string | null;
  [Symbol.toStringTag]: string;
})

export type IEditFieldList = (IPersonalFieldList & {
  value: ISelectOption;
  valueInitial: ISelectOption;
  [Symbol.toStringTag]: string;
})

export type IEditFieldMultiList = (IPersonalFieldMultiList & {
  value: ISelectOption[];
  valueInitial: ISelectOption[];
  [Symbol.toStringTag]: string;
})

export type IEditFieldTimezone = (IPersonalFieldTimezone & {
  value: {
    timezoneValue:     ISelectOption;
    timezoneValueAuto: ISelectOption;
  };
  valueInitial: {
    timezoneValue:     ISelectOption;
    timezoneValueAuto: ISelectOption;
  };
  [Symbol.toStringTag]: string;
})
export type IEditFieldUserField = (IPersonalFieldUserType & {
  value: string | number | boolean | IUfMessenger[];
  valueInitial: string | number | boolean | IUfMessenger[];
  preselectedValue:  any;
  [Symbol.toStringTag]: string;
})

export type IEditField = (
  | IEditFieldText
  | IEditFieldPhone
  | IEditFieldLink
  | IEditFieldDatetime
  | IEditFieldList
  | IEditFieldMultiList
  | IEditFieldTimezone
  | IEditFieldUserField
  )
export type IEditFields = IEditField[]
