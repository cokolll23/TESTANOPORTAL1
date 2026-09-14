import { IUser } from '@/models'

export type IInterest = {
  TEXT:  string;
  COUNT: number;
  USERS: IUser[];
}

export interface IInterestsState {
  INTERESTS: IInterest[];
  POPULAR:   IInterest[];
}
