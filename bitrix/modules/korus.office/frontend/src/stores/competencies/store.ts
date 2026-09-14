import { defineStore } from 'pinia'
import { API } from '@/api'
import { ICompetenciesState } from './storeTypes'
import { ICompetenciesResponse, ICompetence } from './apiResponseTypes'

export const useCompetenciesStore = defineStore('competencies', {
  state: (): ICompetenciesState => ({
    items: [],
    tips: []
  }),

  actions: {
    async add (competencies: string[]) {
      const responses = await Promise.all(
        competencies.map(competence => {
          const payload = new FormData()
          payload.set('tag', competence)
          return API.employee(API.USER_ID).competencies.post({
            payload
          })
        })
      )

      responses.forEach(response => {
        if (response.status === 'success') {
          this.$patch(state => {
            state.items.push(...response.data)
          })
        }
      })
    },

    async remove (competence: string) {
      const response = await API.employee(API.USER_ID).competencies.delete({
        queryParams: {
          tag: competence
        }
      })

      if (response.status === 'success') {
        this.items = this.items.filter(_ => _.TITLE !== competence)
      }
    },

    async removeOld (competence: ICompetence) {
      const response = await API.employee(API.USER_ID).competencies.delete({
        queryParams: {
          tag: competence.TITLE
        }
      })

      if (response.status === 'success') {
        this.items = this.items.filter(_ => _ !== competence)
      }
    },

    setData (rowData: ICompetenciesResponse) {
      this.$patch(state => {
        state.items = rowData
      })
    },

    async loadTips (search: string) {
      let tips: ICompetence[] = []
      if (search.length > 2) {
        const response = await API.employee(API.USER_ID).competencies.search.get({
          queryParams: {
            search
          }
        })
        if (response.status === 'success') {
          tips = response.data
        }
      }
      this.$patch((state) => {
        state.tips = tips
      })
    },

    resetTips () {
      this.tips = []
    }
  }
})
