import { IUser } from '@/models'

export interface IKtTreeNode {
  ID:          string;
  NAME:        string;
  DEPTH_LEVEL: string;
  URL:         string;
  label?:      string;
  children?:   IKtTreeNode[];
}

export interface IKtStructure {
  HEAD:      string | null;
  TREE:      IKtTreeNode[];
  TEAM:      null | number[];
  headInfo?: IUser;
}

export interface IKtStructureUsers {
  [K: string]: {
    [K: string]: IUser
  }
}

export type IStructureResponse = {
  USERS:     IKtStructureUsers;
  STRUCTURE: IKtStructure[];
}
