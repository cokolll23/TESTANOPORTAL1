import axios, { AxiosInstance, AxiosRequestConfig, AxiosResponse } from 'axios'
import { IApiResponse } from '@/stores/types'

type AxiosHttpClientInstance = AxiosInstance
type AxiosHttpRequestConfig = AxiosRequestConfig
type AxiosHttpResponse = AxiosResponse<IApiResponse>

export type {
  AxiosHttpClientInstance,
  AxiosHttpRequestConfig,
  AxiosHttpResponse
}

export default class AxiosHttpClient {
  public static getDefault (): AxiosHttpClientInstance {
    return axios.create({
      headers: this.getDefaultHeaders(),
      withCredentials: true
    })
  }

  private static getDefaultHeaders (): { [key:string]: string } {
    const BX = window.BX
    const headers = { 'Content-Type': 'application/json' } as { [key: string]: string }

    if (typeof BX !== 'undefined') {
      headers['X-Bitrix-Csrf-Token'] = BX.bitrix_sessid()

      if (BX.message('SITE_ID')) {
        headers['X-Bitrix-Site-Id'] = BX.message('SITE_ID')
      }
    }

    return headers
  }
}
