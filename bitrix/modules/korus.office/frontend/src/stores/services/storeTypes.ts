import { IService } from './apiResponseTypes'

export interface IServicesState {
  SERVICES: {
    MAIN:     IService[];
    FAVORITE: IService[];
  };
}
