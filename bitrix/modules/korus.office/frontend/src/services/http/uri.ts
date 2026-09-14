import { QueryParams } from './types'

export default class Uri {
  private readonly domain: string
  private readonly path: string
  private readonly query: string = ''

  constructor (
    domain: string,
    path: string,
    queryParams?: QueryParams
  ) {
    this.domain = Uri.checkDomain(domain)
    this.path = path

    if (queryParams) {
      this.query = Uri.buildQueryString(queryParams)
    }
  }

  public static checkDomain (domain: string): string {
    if (!/^https?:\/\//.test(domain)) {
      return `https://${domain}`
    }

    return domain
  }

  public static buildQueryString (data: QueryParams, prev = ''): string {
    const arr = []
    let k, v

    for (const f in data) {
      if (!Object.prototype.hasOwnProperty.call(data, f)) continue

      k = prev ? prev + '[' + f + ']' : f
      v = data[f]

      arr.push(
        v instanceof Object
          ? this.buildQueryString(v, k)
          : encodeURIComponent(k) + '=' + encodeURIComponent(v)
      )
    }

    return arr.join('&')
  }

  public build (): string {
    let url = `${this.domain}${this.path}`
    if (this.query) {
      url += `?${this.query}`
    }

    return url
  }
}
