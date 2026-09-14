import { IUser } from '@/models'

export interface IVacation {
  FROM:   string;
  TO:     string;
  DAYS:     string;
  DEPUTY: number[];
}

export type IVacationsResponse = {
  USERS:    null | Record<string, IUser>;
  VACATION: null | IVacation;
}
