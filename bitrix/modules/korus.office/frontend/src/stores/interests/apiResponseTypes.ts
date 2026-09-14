import { IUser } from '@/models'

export type IInterestUserResponse = {
  ID:     string;
  WEIGHT: number;
}

export type IInterestsResponse = {
  USERS:     null | Record<string, IUser>;
  INTERESTS: null | Record<string, {
    COUNT: number;
    USERS: IInterestUserResponse[];
  }>;
}

export type IPopularInterestsResponse = {
  USERS:     null | Record<string, IUser>;
  INTERESTS: null | Record<string, {
    COUNT: number;
    NAME:  string;
    USERS: IInterestUserResponse[];
  }>;
}
