import { defineStore } from 'pinia'
import { API } from '@/api'
import { usePersonalStore } from 'stores/personal'
import { usePermissionsStore } from 'stores/permissions'
import type { IPersonalFieldsResponse } from './apiResponseTypes'
import type { IPersonalFieldsState } from './storeTypes'

export const usePersonalFieldsStore = defineStore('personal-fields', {
  state: (): IPersonalFieldsState => ({
    items: []
  }),

  getters: {
    FIELDS (state) {
      return state.items
    },
    FIELDS_EDITABLE (state) {
      return state.items.filter(item => item.editable && item.name !== 'PERSONAL_PHOTO')
    }
  },

  actions: {
    async submit (payload: FormData) {
      const response = await API.employee(API.USER_ID).personal.post({
        payload
      })
      const personalStore = usePersonalStore()
      const permissionsStore = usePermissionsStore()

      if (response.status === 'success') {
        personalStore.setData(response.data.PERSONAL)
        permissionsStore.setData(response.data.PERMISSIONS)
        this.setData(response.data.FIELDS)
      }
    },

    async changeVisibility (payload: FormData) {
      const response = await API.employee(API.USER_ID).fieldSettings.post({
        payload
      })
      const personalStore = usePersonalStore()

      if (response.status === 'success') {
        personalStore.setData(response.data)
      }
    },

    setData (rowData: IPersonalFieldsResponse) {
      this.$patch(state => {
        state.items = rowData
      })
    }
  }
})
