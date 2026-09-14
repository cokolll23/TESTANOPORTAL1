import { IUser } from '@/models'
import { INav } from 'stores/types'
import {
  IBadge,
  IBadgeCounter,
  IGratitudeCode,
  IGratitudeUrls,
  IPost
} from './apiResponseTypes'

export type IGratitudePosts = null | Record<string, IPost>;
export type IGratitudeUsers = Record<string, IUser>;

export type IGratitudeInitial = {
  CODE:       IGratitudeCode;
  ICON:       string;
  ICON_COLOR: string;
};

export interface IGratitudesState {
  BADGES:          IBadge[],
  BADGES_COUNTERS: null | Record<string, IBadgeCounter>;
  NAV:             INav;
  POSTS:           IGratitudePosts;
  URLS:            IGratitudeUrls;
  USERS:           IGratitudeUsers;
}
