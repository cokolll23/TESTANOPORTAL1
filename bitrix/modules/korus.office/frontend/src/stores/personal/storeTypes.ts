import { ISelectOption, IUser, IUserStatus } from '@/models'
import { IUfMessenger } from 'stores/types'

export type MESSENGER = ISelectOption & {
  [K: string]: string
}

export interface IPersonalState extends IUser {
  STATUS:                      IUserStatus;
  PERSONAL_PHOTO:              number;
  ACTIVE:                      boolean;
  CONFIRM_CODE:                string;
  DATE_REGISTER:               string;
  EXTERNAL_AUTH_ID:            string;
  LAST_ACTIVITY_DATE:          string;
  PERSONAL_BIRTHDAY:           string;
  PERSONAL_BIRTHDAY_FORMATTED: string;
  PERSONAL_CITY:               string;
  PERSONAL_COUNTRY:            string;
  PERSONAL_PHONE:              string;
  AUTO_TIME_ZONE:              string;
  TIME_ZONE:                   string;
  TIME_ZONE_OFFSET:            number;
  UF_EMPLOYEE_ID:              null | string;
  UF_COMPETENCE:               string[];
  UF_DEPARTMENT:               number[];
  UF_EMPLOYMENT_DATE:          null | string;
  COMPANY_EXPERIENCE:          null | string;
  UF_HIDE_PERSONAL_PHONE:      null | number;
  UF_MESSENGERS:               IUfMessenger[];
  UF_WORKPLACE:                null | string;
  WORK_CITY:                   string;
  WORK_COMPANY:                string;
  WORK_COUNTRY:                string;
  WORK_DEPARTMENT:             string;
  WORK_PHONE:                  string;
  IS_EXTRANET_USER:            boolean;
}
