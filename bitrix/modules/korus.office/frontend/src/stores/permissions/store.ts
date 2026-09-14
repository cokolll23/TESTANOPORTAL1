import { defineStore } from 'pinia'
import { IPermissionsState } from './storeTypes'
import { IPermissionsResponse } from './apiResponseTypes'

export const usePermissionsStore = defineStore('permissions', {
  state: (): IPermissionsState => ({
    CAN_EDIT:             false,
    IS_OWN_PROFILE:       false,
    IS_SESSION_ADMIN:     false,
    SHOW_SONET_ADMIN:     false,
    EDIT_DEPUTY:          false,
    DISABLE_EMOJI_STATUS: false,
    SORT_DISABLE: false
  }),

  actions: {
    setData (rowData: IPermissionsResponse) {
      this.$patch(state => {
        Object.assign(state, rowData)
      })
    }
  }
})
