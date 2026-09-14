import { IRequestComment, IRequestStatusText } from './apiResponseTypes'
import { IUser } from '@/models'
import { INav } from 'stores/types'

export type IRequestTabName = 'active' | 'closed';
export type IRequestTabLabel = 'Активные' | 'Закрыты';

export interface IRequestTab {
  name: IRequestTabName;
  label: IRequestTabLabel;
  count?: number;
  currentPage: number;
}

type IRequestStatus = {
  id:    number;
  text:  string;
  theme: string;
}

export type IRequest = {
  tabName: IRequestTabName;
  id: number;
  title: string;
  dateCreate: string;
  dateCreateFormatted: string;
  dateEnd: string;
  dateEndFormatted: string;
  maxDeadline: string;
  isOverdue: boolean;
  maxDeadlineFormatted: string;
  status: IRequestStatus;
  response: IUser[];
  comment: IRequestComment;
  indicator: string;
  countMessage?: number;
}

export interface IRequestsState {
  tabCurrent: IRequestTabName;
  tabs: IRequestTab[];
  list: IRequest[];
  nav: INav;

  navInit: boolean;
}
