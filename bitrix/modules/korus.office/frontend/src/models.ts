export type ITheme = 'light' | 'dark'

export type ILinkTheme = 'primary' | 'secondary' | 'dark'

export type ILinkTarget = '_blank' | '_parent' | '_self' | '_top'

export type IPersonalGender = 'M' | 'F'

export type IInputType =
  | 'text'
  | 'password'
  | 'textarea'
  | 'email'
  | 'search'
  | 'tel'
  | 'file'
  | 'number'
  | 'url'
  | 'time'
  | 'date';

export interface IUser {
  ID:               number;
  FULL_NAME:        string;
  LAST_NAME:        string;
  LOGIN:            string;
  NAME:             string;
  PHOTO:            string | null;
  SECOND_NAME:      string;
  WORK_POSITION?:   string;
  EMAIL?:           string;
  PERSONAL_GENDER?: IPersonalGender;
  ONLINE_STATUS?: {
    IS_ONLINE:      boolean,
    STATUS:         string,
    STATUS_TEXT:    string,
    LAST_SEEN:      string,
    LAST_SEEN_TEXT: string,
    NOW:            string
  }
}

export type IUserStatus =
  | 'admin'
  | 'integrator'
  | 'extranet'
  | 'fired'
  | 'invited'
  | 'email'
  | 'shop'
  | 'visitor'
  | 'employee';

export type ISelectOption = {
  NAME:  string;
  VALUE: string | number;
}

export type IKtTab = {
  name:   string;
  label:  string;
  count?: number;
};
