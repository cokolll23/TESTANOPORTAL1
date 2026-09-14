import { HttpService } from './adapters/axios'

export const restClient = new HttpService().getApi()
