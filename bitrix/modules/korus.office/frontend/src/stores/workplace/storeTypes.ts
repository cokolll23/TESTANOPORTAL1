import { IBooking, IWorkplace } from './apiResponseTypes'

export interface IWorkplaceState {
  WORKPLACE: IWorkplace,
  BOOKING:   IBooking[]
}
