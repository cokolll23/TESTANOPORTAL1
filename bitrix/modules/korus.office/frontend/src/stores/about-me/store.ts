import { defineStore } from 'pinia'
import { API } from '@/api'
import { IAboutMeState } from './storeTypes'

export const useAboutMeStore = defineStore('about-me', {
  state: (): IAboutMeState => ({
    content: null,
    editor: null,
    editorId: 'blogPost'
  }),

  actions: {
    async loadEditor () {
      if (this.editor) {
        return true
      }

      const response = await API.component.editor.get({
        queryParams: { name: this.editorId }
      })

      if (response.status === 'success') {
        this.setEditor(response.data)
      }
    },

    async send (payload: FormData) {
      const response = await API.employee(API.USER_ID).about.post({
        payload
      })

      if (response.status === 'success') {
        this.content = { ...response.data }
      }
    },

    setData (rowData: IAboutMeState['content']) {
      this.$patch(state => {
        state.content = rowData
      })
    },

    setEditor (rowData: IAboutMeState['editor']) {
      this.$patch(state => {
        state.editor = rowData
      })
    }
  }
})
