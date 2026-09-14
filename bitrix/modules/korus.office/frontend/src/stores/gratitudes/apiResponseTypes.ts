import { IUser } from '@/models'
import { INav } from 'stores/types'

export type IGratitudeCode  =
  | 'thumbsup'
  | 'gift'
  | 'cup'
  | 'money'
  | 'crown'
  | 'drink'
  | 'cake'
  | 'thefirst'
  | 'flag'
  | 'star'
  | 'heart'
  | 'beer'
  | 'flowers'
  | 'smile';

export interface IBadge {
  ID:   string;
  CODE: IGratitudeCode ;
  NAME: string;
  SORT: number;
}

export interface IBadgeCounter {
  NAME:  string;
  COUNT: number;
  ID:    number[];
}

export interface IPost {
  ID:               string;
  TITLE:            string;
  AUTHOR_ID:        number;
  BADGE_ID:         number;
  CONTENT_VIEW_CNT: number;
  DATE_FORMATTED:   string;
  DATE_PUBLISH:     string;
  DATE_PUBLISH_TS:  number;
  MICRO:            'Y' | 'N';
  RATING_DATA:      [];
  UF_GRATITUDE:     string;
  URL:              string;
}

export interface IGratitudeUrls {
  ADD:  string;
  LIST: string;
}

export type IGratitudesResponse = {
  BADGES:          IBadge[],
  BADGES_COUNTERS: null | Record<string, IBadgeCounter>;
  NAV:             INav;
  POSTS:           null | Record<string, IPost>;
  URLS:            IGratitudeUrls;
  USERS:           Record<string, IUser>;
}
