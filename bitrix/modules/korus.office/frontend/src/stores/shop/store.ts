import { defineStore } from 'pinia'
import { IShopState } from './storeTypes'
import { IShopResponse } from './apiResponseTypes'

export const useShopStore = defineStore('shop', {
  state: (): IShopState => ({
    URLS: Object.create(null),
    ACCOUNT: Object.create(null)
  }),

  actions: {
    setData (rowData: IShopResponse) {
      this.$patch(state => {
        Object.assign(state, rowData)
      })
    }
  }
})
