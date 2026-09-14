import { defineStore } from 'pinia'
import { IWorkplaceState } from './storeTypes'
import { IWorkplaceResponse } from './apiResponseTypes'

export const useWorkplaceStore = defineStore('workplace', {
  state: (): IWorkplaceState => ({
    WORKPLACE: Object.create(null),
    BOOKING: Object.create(null)
  }),

  actions: {
    setData (rowData: IWorkplaceResponse) {
      this.$patch(state => {
        Object.assign(state, rowData)
      })
    }
  }
})
