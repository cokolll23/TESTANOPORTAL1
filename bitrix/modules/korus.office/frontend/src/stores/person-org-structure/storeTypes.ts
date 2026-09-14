import { IKtStructure, IKtStructureUsers } from './apiResponseTypes'

export interface IPersonOrgStructureState {
  USERS:     IKtStructureUsers;
  STRUCTURE: IKtStructure[];
}
