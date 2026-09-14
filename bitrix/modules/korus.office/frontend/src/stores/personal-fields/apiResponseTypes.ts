import { ILinkTarget, ISelectOption } from '@/models'
import { IPersonalResponse } from 'stores/personal'

export type IPersonalFieldType =
  | 'text'
  | 'link'
  | 'datetime'
  | 'list'
  | 'phone'
  | 'userField'
  | 'timezone'
  | 'multilist';

export interface IPersonalFieldCommon {
  title:            string;
  name:             keyof IPersonalResponse;
  editable:         boolean;
  management:       boolean;
  managementStatus: boolean;
  sort: {
    type: string,
    value: number
  }
}

export type IPersonalFieldText = IPersonalFieldCommon & {
  type: 'text';
  showAlways?: boolean;
  visibilityPolicy?: string;
  data?: {
    lineCount?: number;
  }
}

export type IPersonalFieldPhone = IPersonalFieldCommon & {
  type: 'phone';
}

export type IPersonalFieldLink = IPersonalFieldCommon & {
  type: 'link';
  data: {
    target?: ILinkTarget;
    link_template?: string;
  }
}

export type IPersonalFieldList = IPersonalFieldCommon & {
  type: 'list';
  data: {
    items: ISelectOption[];
    class: string;
  }
}

export type IPersonalFieldMultiList = IPersonalFieldCommon & {
  type: 'multilist';
  data: {
    items: ISelectOption[];
    class: string;
  }
}

export type IPersonalFieldDatetime = IPersonalFieldCommon & {
  type: 'datetime';
  data: {
    enableTime: boolean;
    dateViewFormat: string;
  }
}

export type IPersonalFieldTimezone = IPersonalFieldCommon & {
  type: 'timezone';
  visibilityPolicy: string;
  data: {
    timezone_items: ISelectOption[];
    auto_timezone_items: ISelectOption[];
  }
}

export type IUserFieldTypeId =
  | 'string'
  | 'string_formatted'
  | 'url'
  | 'date'
  | 'hlblock'
  | 'user'
  | 'employee'
  | 'boolean'
  | 'integer'
  | 'messengers'
  | 'competence';

export type IUserFieldSettings =
  | {
  ROWS?:           number;
  SIZE?:           number;
  POPUP?:          'Y' | 'N';
  LABEL?:          (null | string)[];
  REGEXP?:         string;
  DISPLAY?:        'LIST' | 'CHECKBOX';
  PATTERN?:        string;
  MAX_VALUE?:      number;
  MIN_VALUE?:      number;
  HLBLOCK_ID?:     number;
  HLFIELD_ID?:     number;
  MAX_LENGTH?:     number;
  MIN_LENGTH?:     number;
  LIST_HEIGHT:     number;
  DEFAULT_VALUE?:  unknown;
  LABEL_CHECKBOX?: null | string;
}
  | { code: string; value: string; }[]
  | []

export type IPersonalFieldUserType = IPersonalFieldCommon & {
  type: 'userField';
  data: {
    fieldInfo: {
      FIELD:           string;
      ENTITY_ID:       string;
      MULTIPLE:        'Y' | 'N';
      MANDATORY:       'Y' | 'N';
      ENTITY_VALUE_ID: string;
      SETTINGS:        IUserFieldSettings;
      USER_TYPE_ID:    IUserFieldTypeId;
    }
  }
}

export type IPersonalField = (
  | IPersonalFieldText
  | IPersonalFieldPhone
  | IPersonalFieldLink
  | IPersonalFieldList
  | IPersonalFieldMultiList
  | IPersonalFieldDatetime
  | IPersonalFieldTimezone
  | IPersonalFieldUserType
  )

export type IPersonalFieldsResponse = IPersonalField[];
