import { IUser } from '@/models'
import { INav } from 'stores/types'

export interface IRequestComment {
  text: string;
  new: boolean;
}

export interface IRequestRow {
  id: number;
  status: number;
  type: number;
  dateCreate: string;
  dateEnd: string;
  maxDeadline: string;
  isOverdue: boolean;
  response: number[];
  delivery: string;
  canAbort: boolean;
  color: string;
  comment: IRequestComment;
  indicator: string;
  countMessage?: number;
}

export type IRequestStatusText =
  | 'Новая'
  | 'В работе'
  | 'Выполнена'
  | 'Отклонена'
  | 'Отозвана'

export interface IRequestStatus {
  id: number;
  title: string;
}

export interface IRequestType {
  id: number;
  title: string;
}

export type IRequestsResponse = {
  list: IRequestRow[];
  nav: INav;
  statuses: IRequestStatus[];
  types: IRequestType[];
  users: Record<string, IUser>;
}
