import { Dialog, date } from 'quasar'
import { IAssets, IComponentContent } from 'stores/types'

export function formatDate (dateString: Date, format = 'DD.MM.YYYY') {
  return date.formatDate(dateString, format)
}

export function isEmptyPreselectedItems (arr: any) {
  return arr.some((item:any) => item[1] !== null)
}
function formatTimezone (format: 'short' | 'long', timezone = '') {
  const options: any = { timeZoneName: format }

  if (timezone) {
    options.timeZone = timezone
  }

  const strDate = new Intl.DateTimeFormat([], options).format(new Date())

  return strDate.split(' ').slice(1).join(' ')
}

export function getTimezoneFormatted (value: { autoTimeZone: string, timeZone: string }) {
  let timeZone = ''
  if (value.autoTimeZone !== 'Y' && value.timeZone) {
    timeZone = value.timeZone
  }

  const short = formatTimezone('short', timeZone)
  const long = formatTimezone('long', timeZone)

  return short + ', ' + long
}

export function truncate (str: string, maxlength: number) {
  return (str.length > maxlength)
    ? str.slice(0, maxlength - 1) + '…'
    : str
}

export function ObjectKeys<T extends object> (target: T): (keyof T)[] {
  return Object.keys(target) as (keyof T)[]
}

export function isEmpty (value: unknown) {
  if (value == null) return true
  if (typeof value === 'string') return value === ''
  if (typeof value === 'number') return value === 0
  if (Array.isArray(value)) return value.length === 0
  if (value instanceof Map || value instanceof Set) return value.size === 0
  if (Object.prototype.toString.call(value)) return ObjectKeys(value as object).length === 0

  return false
}

export function getType (val: unknown) {
  return Object.prototype.toString.call(val).slice(8, -1)
}

export function confirm (options: {
  title?: string;
  message?: string;
  html?: boolean;
}) {
  return new Promise((resolve, reject) => {
    Dialog.create({
      title: options.title,
      message: options.message,
      cancel: true,
      persistent: true,
      html: options?.html
    })
      .onOk(() => {
        resolve(true)
      })
      .onCancel(() => {
        reject()
      })
  })
}

export async function processAssets (assets: IAssets) {
  const BX = window.BX

  const filesLoaded = await new Promise((resolve, reject) => {
    const css = assets.css || []
    const js = assets.js || []

    BX.load(css, () => {
      BX.loadScript(js, () => resolve(true))
    })
  })

  return new Promise((resolve, reject) => {
    if (filesLoaded) {
      const strings = (assets.string || []).join('\n')

      BX.html(document.head, strings, { useAdjacentHTML: true }).then(() => resolve(true))
    } else {
      reject(false)
    }
  })
}

export async function processComponent (data: IComponentContent, elem: HTMLElement, replace = true) {
  return new Promise((resolve, reject) => {
    processAssets(data.assets)
      .then(() => {
        if (replace) {
          elem.innerHTML = ''
        }

        const BX = window.BX

        BX.html(elem, data.html, { useAdjacentHTML: true })
          .then(() => resolve(true))
          .catch(() => reject(false))
      })
      .catch(() => {
        reject(false)
      })
  })
}
