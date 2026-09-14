import { IUser } from '@/models'
import { IVacation } from './apiResponseTypes'

export interface ICurrentVacationsState {
  USERS:    null | Record<string, IUser>;
  VACATION: null | IVacation;
}
