// import { getOAuthClient } from '@/auth'
import AxiosHttpClient, {
  AxiosHttpClientInstance,
  AxiosHttpRequestConfig,
  AxiosHttpResponse
} from './httpClient'
import { RestClient, HttpResource } from '@/services/http/restClient'
import { IApiResponse } from '@/services/http/types'
import { AxiosError } from 'axios'
import { Notify } from 'quasar'

export interface TransportInterceptor<T> {
  onFulfilled?: (config: T) => T | Promise<T>,
  onRejected?: (error: any) => any
}

export class HttpService {
  protected version = 'v1'
  protected domain = process.env.VUE_APP_API_DOMAIN as string
  protected transport: AxiosHttpClientInstance
  protected client: RestClient<AxiosHttpClientInstance>
  private api?: HttpResource

  constructor () {
    this.transport = this.initTransport()
    this.client    = this.initRestClient()
  }

  protected initTransport (): AxiosHttpClientInstance {
    const transport: AxiosHttpClientInstance = AxiosHttpClient.getDefault()

    this.getOnBeforeRequestInterceptors().forEach(cb => {
      transport.interceptors.request.use(cb.onFulfilled, cb.onRejected)
    })

    this.getOnAfterResponseInterceptors().forEach(cb => {
      transport.interceptors.response.use(cb.onFulfilled, cb.onRejected)
    })

    return transport
  }

  protected getOnBeforeRequestInterceptors (): TransportInterceptor<AxiosHttpRequestConfig>[] {
    return [
      {
        onFulfilled: async (config: AxiosHttpRequestConfig) => {
          // const OAuthClient = await getOAuthClient()
          //
          // config.headers = {
          //   'X-AUTH-TOKEN': await OAuthClient.getTokenSilently() as string
          // }

          return config
        },
        onRejected: (error: any) => {
          return Promise.reject(error)
        }
      }
    ]
  }

  protected getOnAfterResponseInterceptors (): TransportInterceptor<AxiosHttpResponse>[] {
    return [
      {
        onFulfilled: (response) => {
          if (response.data.status === 'error') {
            this.showErrors(response.data.errors)
          }

          return response
        },
        onRejected: (error: AxiosError) => {
          Notify.create({
            type: 'negative',
            message: error.message,
            position: 'top'
          })

          return Promise.reject(error)
        }
      }
    ]
  }

  protected showErrors (errors: IApiResponse['errors']) {
    errors.forEach(error => {
      if (error.code === 0) { return false }

      Notify.create({
        type: 'negative',
        message: error.message,
        position: 'top'
      })
    })
  }

  protected initRestClient (): RestClient<AxiosHttpClientInstance> {
    return new RestClient(this.domain, this.transport, false)
  }

  public getApi (): HttpResource {
    if (this.api === undefined) {
      this.client.register('api')
      this.api = this.client.api(this.version) as HttpResource
    }

    return this.api
  }
}
