import { defineStore } from 'pinia'
import { formatDate } from '@/utils'
import { IVacation, IVacationsResponse } from './apiResponseTypes'
import { ICurrentVacationsState } from './storeTypes'

export const useVacationsStore = defineStore('vacations', {
  state: (): ICurrentVacationsState => ({
    USERS: null,
    VACATION: null
  }),

  getters: {
    VACATION_FORMATTED (state) {
      return state.VACATION?.FROM && state.VACATION?.TO
        ? `${state.VACATION.FROM} - ${state.VACATION.TO} (${state.VACATION.DAYS})`
        : null
    }
  },

  actions: {
    setData (rowData: IVacationsResponse) {
      this.$patch(state => {
        Object.assign(state, rowData)
      })
    }
  }
})
