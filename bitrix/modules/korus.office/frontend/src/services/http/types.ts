import { AxiosHttpResponse } from '@/services/http/adapters/axios/httpClient'

export type HttpMethod =
  | 'get'     | 'GET'
  | 'delete'  | 'DELETE'
  | 'head'    | 'HEAD'
  | 'options' | 'OPTIONS'
  | 'post'    | 'POST'
  | 'put'     | 'PUT'
  | 'patch'   | 'PATCH'
  | 'purge'   | 'PURGE'
  | 'link'    | 'LINK'
  | 'unlink'  | 'UNLINK'

interface ITransportConfig {
  baseURL: string;
  method: HttpMethod;
  url: string;
  data: any;
}

export type Transport = {
  request: (args: ITransportConfig) => Promise<any>
}

export type LikeObject<T> = {
  [k: string]: T
}

export type ApiRequest = {
  type: string;
  message: string
}

export interface IApiResponse<T = null> {
  status: 'success' | 'error',
  data: T;
  errors: {
    code: number;
    message: string
  }[]
}

export type QueryParams = {
  [K: string]: string | number | boolean | QueryParams
}

export type RegisteredResource = string | boolean | {
  [K: string]: RegisteredResource
}

export type IRequestConfig = {
  payload?:     any;
  queryParams?: QueryParams;
}

export type IRequestUrl = string | IRequestConfig;

export interface IHttpResourceCore {
  [K: string]: any

  register: (resources: RegisteredResource) => void;
  getUrl:   (url?: string, queryParams?: QueryParams) => string;
  get:      (url?: IRequestUrl, config?: IRequestConfig) => Promise<AxiosHttpResponse>;
  post:     (url?: IRequestUrl, config?: IRequestConfig) => Promise<AxiosHttpResponse>;
  put:      (url?: IRequestUrl, config?: IRequestConfig) => Promise<AxiosHttpResponse>;
  patch:    (url?: IRequestUrl, config?: IRequestConfig) => Promise<AxiosHttpResponse>;
  delete:   (url?: IRequestUrl, config?: IRequestConfig) => Promise<AxiosHttpResponse>;
}

export interface IRestClient {
  request: (
    method: HttpMethod,
    url: string,
    data?: any
  ) => Promise<AxiosHttpResponse>
}
