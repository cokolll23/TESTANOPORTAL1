import { IAboutResponse } from './apiResponseTypes'

export interface IAboutMeState {
  content:  IAboutResponse;
  editor:   IAboutResponse;
  editorId: string
}
