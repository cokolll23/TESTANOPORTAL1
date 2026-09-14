import Uri from './uri'
import {
  RegisteredResource,
  IHttpResourceCore,
  IRestClient,
  HttpMethod,
  Transport,
  QueryParams, IRequestConfig, IRequestUrl
} from './types'

/**
 * Такой вот REST-клиент - для удобочитаемого использования
 * todo: найти способ сделать распознавание динамических полей объекта класса ResourceCore
 *
 * Пример использования:
 * let client = new RestClient('http://some-host.net')
 *
 * Инициализация ресурсов, из которых получаем данные:
 * client.init('books')
 * client.init(['cats', 'dogs'])
 * client.init({
 *   users: {
 *     books: false,
 *     music: 'albums'
 *   }
 * })
 *
 * Получение/отправка данных:
 * client.books.get() -> get-запрос по адресу http://some-host.net/books
 * client.books(3).get() -> get-запрос по адресу http://some-host.net/books/3
 * запросы client.get('/books/3') и client.books(3).get() равносильны
 *
 * client.cats.post({name: 'Барсик', color: 'В полоску'}) -> post-запрос по адресу http://some-host.net/cats
 *
 * client.users(13).books(5).delete() -> delete-запрос по адресу http://some-host.net/users/13/books/5
 * client.users(1).music.albums.get() -> get-запрос по адресу http://some-host.net/users/1/music/albums
 *
 * client.users(1).music.albums.getUrl({ a:1, b: 'qwerty' }) - сформирует url /users/1/music/albums?a=1&b=qwerty
 */

abstract class HttpResourceCore extends Function implements IHttpResourceCore {
  // Для возможности обращаться к ресурсам как к обычным полям объекта
  // eslint-disable-next-line no-undef
  [K: string]: any

  protected _resources = new Map<string, HttpResource>()
  protected _parent?: IHttpResourceCore
  protected _code?: string
  protected _id?: string | number

  protected abstract getClient (): IRestClient

  public register (resources: RegisteredResource) {
    if (typeof resources === 'number') {
      resources = '' + resources
    }

    if (typeof resources === 'string') {
      return this.createHttpResource(resources)
    }
    else if (Array.isArray(resources)) {
      return resources.map(this.createHttpResource)
    }
    else if (typeof resources === 'object') {
      const res = Object.create(null)

      for (const key in resources) {
        const r = this.createHttpResource(key)

        if (typeof resources[key] === 'boolean') {
          res[key] = r
          continue
        }

        (r as HttpResource).register(resources[key])
        res[key] = r
      }

      return res
    }
  }

  public getUrl (url = '', queryParams?: QueryParams): string {
    const query = Uri.buildQueryString(queryParams ?? Object.create(null))
    let resultUrl = this._parent ? this._parent.getUrl() : ''

    if (this._code) resultUrl += `/${this._code}`
    if (this._id)   resultUrl += `/${this._id}`
    if (url !== '') resultUrl += url
    if (query)      resultUrl += `?${query}`

    return resultUrl
  }

  public async get (url: IRequestUrl = '', config?: IRequestConfig) {
    const normalizedParams = HttpResource._normalizeRequestParams(url, config)

    return await this.getClient().request(
      'GET',
      this.getUrl(normalizedParams.url, normalizedParams.config?.queryParams)
    )
  }

  public async post (url: IRequestUrl = '', config?: IRequestConfig) {
    const normalizedParams = HttpResource._normalizeRequestParams(url, config)

    return await this.getClient().request(
      'POST',
      this.getUrl(normalizedParams.url, normalizedParams.config?.queryParams),
      normalizedParams.config?.payload
    )
  }

  public async put (url: IRequestUrl = '', config?: IRequestConfig) {
    const normalizedParams = HttpResource._normalizeRequestParams(url, config)

    return await this.getClient().request(
      'PUT',
      this.getUrl(normalizedParams.url, normalizedParams.config?.queryParams),
      normalizedParams.config?.payload
    )
  }

  public async patch (url: IRequestUrl = '', config?: IRequestConfig) {
    const normalizedParams = HttpResource._normalizeRequestParams(url, config)

    return await this.getClient().request(
      'PATCH',
      this.getUrl(normalizedParams.url, normalizedParams.config?.queryParams),
      normalizedParams.config?.payload
    )
  }

  public async delete (url: IRequestUrl = '', config?: IRequestConfig) {
    const normalizedParams = HttpResource._normalizeRequestParams(url, config)

    return await this.getClient().request(
      'DELETE',
      this.getUrl(normalizedParams.url, normalizedParams.config?.queryParams),
      normalizedParams.config?.payload
    )
  }

  private static _normalizeRequestParams (url: IRequestUrl, config?: IRequestConfig) {
    if (typeof url !== 'string') {
      config = url
      url = ''
    }

    return { url, config }
  }

  protected createHttpResource (name: string) {
    if (this._resources.has(name)) {
      return this.getHttpResource(name)
    }

    const parent = this instanceof HttpResource ? this : undefined

    this._resources.set(name, new HttpResource(this.getClient(), parent, name))

    return this._resources.get(name)
  }

  protected getHttpResource (name: string) {
    return this._resources.get(name)
  }
}

export class HttpResource extends HttpResourceCore {
  constructor (
    protected _client: IRestClient,
    protected _parent?: IHttpResourceCore,
    protected _code?: string,
    protected _id?: string | number
  ) {
    super()

    return new Proxy(this, {
      apply: (target: this, thisArg, args) => {
        return args[0] ? target.clone(this._parent, args[0]) : target
      },

      get (target, prop: string) {
        if (target._resources.has(prop)) {
          return target._resources.get(prop)
        }

        return target[prop as keyof typeof target]
      }
    })
  }

  protected getClient (): IRestClient {
    return this._client
  }

  public clone (parent?: IHttpResourceCore, newId?: string | number) {
    const resourceCloned = new HttpResource(this.getClient(), parent, this._code, newId)

    this._resources.forEach((resource, key) => {
      resourceCloned._resources.set(key, resource.clone(resourceCloned))
    })

    return resourceCloned
  }
}

export class RestClient <T extends Transport> extends HttpResourceCore {
  constructor (
    public readonly host: string,
    protected transport: T,
    protected trailingSlash = false
  ) {
    super()

    return new Proxy(this, {
      get (target, prop: string) {
        if (target._resources.has(prop)) {
          return target._resources.get(prop)
        }

        return target[prop as keyof typeof target]
      }
    })
  }

  protected getClient (): IRestClient {
    return this
  }

  protected prepareUrl = (url: string): string => {
    const urlParts = url.split('?')

    if (this.trailingSlash && urlParts[0].slice(-1) !== '/') {
      urlParts[0] += '/'
    }

    if (!this.trailingSlash && urlParts[0].slice(-1) === '/') {
      urlParts[0] = urlParts[0].slice(0, -1)
    }

    return urlParts.join('?')
  }

  public async request (
    method: HttpMethod,
    url: string,
    data = null
  ) {
    url = this.prepareUrl(url)
    const response = await this.transport.request({
      baseURL: this.host,
      method,
      url,
      data
    })

    return response.data
  }
}
