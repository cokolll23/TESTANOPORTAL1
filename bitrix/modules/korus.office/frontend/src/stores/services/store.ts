import { defineStore } from 'pinia'
import { IServicesResponse } from './apiResponseTypes'
import { IServicesState } from './storeTypes'

export const useServicesStore = defineStore('services', {
  state: (): IServicesState => ({
    SERVICES: Object.create(null)
  }),

  getters: {
    mainServices (state) {
      return state.SERVICES.MAIN ?? []
    },

    favoriteServices (state) {
      return state.SERVICES.FAVORITE ?? []
    }
  },

  actions: {
    setData (rowData: IServicesResponse) {
      this.$patch(state => {
        state.SERVICES =  Object.create(rowData)
      })
    }
  }
})
