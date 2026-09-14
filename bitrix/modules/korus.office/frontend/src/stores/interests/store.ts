import { defineStore } from 'pinia'
import { ObjectKeys } from '@/utils'
import { API } from '@/api'
import { IUser } from '@/models'
import { IInterestsState, IInterest } from './storeTypes'
import { IInterestsResponse, IPopularInterestsResponse, IInterestUserResponse } from './apiResponseTypes'

export const useInterestsStore = defineStore('interests', {
  state: (): IInterestsState => ({
    INTERESTS: [],
    POPULAR: []
  }),

  actions: {
    async add (interests: string[]) {
      const responses = await Promise.all(
        interests.map(interest => {
          return API.employee(API.USER_ID).interests.post({
            queryParams: {
              tag: interest
            }
          })
        })
      )

      responses.forEach(response => {
        if (response.status === 'success') {
          this.$patch(state => {
            state.INTERESTS.push(...this.prepareInterests(response.data))
          })
        }
      })
    },

    async remove (interest: string) {
      const response = await API.employee(API.USER_ID).interests.delete({
        queryParams: {
          tag: interest
        }
      })

      if (response.status === 'success') {
        this.INTERESTS = this.INTERESTS.filter(_ => _.TEXT !== interest)
      }
    },

    async removeOld (interest: IInterest) {
      const response = await API.employee(API.USER_ID).interests.delete({
        queryParams: {
          tag: interest.TEXT
        }
      })

      if (response.status === 'success') {
        this.INTERESTS = this.INTERESTS.filter(_ => _ !== interest)
      }
    },

    async loadPopular () {
      const response = await API.employee(API.USER_ID).interests.search.get()

      if (response.status === 'success') {
        this.setPopular(response.data)
      }
    },

    setData (rowData: IInterestsResponse) {
      this.$patch(state => {
        state.INTERESTS = this.prepareInterests(rowData)
      })
    },

    prepareInterests (rowData: IInterestsResponse) {
      const { INTERESTS, USERS } = rowData

      if (INTERESTS === null || USERS === null) {
        return []
      }

      return ObjectKeys(INTERESTS).map(title => {
        const { COUNT, USERS: INTERESTS_USERS } = INTERESTS[title]

        return {
          TEXT: title,
          COUNT,
          USERS: this.getInterestUsers(INTERESTS_USERS, USERS)
        }
      })
    },

    setPopular (rowData: IPopularInterestsResponse) {
      const { INTERESTS, USERS } = rowData

      if (INTERESTS === null || USERS === null) {
        return false
      }

      this.$patch(state => {
        state.POPULAR = ObjectKeys(INTERESTS).map(index => {
          const item = INTERESTS[index]

          return {
            TEXT: item.NAME,
            COUNT: item.COUNT,
            USERS: this.getInterestUsers(item.USERS, USERS)
          }
        })
      })
    },

    getInterestUsers (interestUsers: IInterestUserResponse[], allUsers: Record<string, IUser>) {
      return ObjectKeys(allUsers)
        .filter(ID => interestUsers.map(_ => _.ID).includes(ID))
        .map(ID => allUsers[ID])
    },

    async loadTips (search: string) {
      let tips: IPopularInterestsResponse = {
        USERS: null,
        INTERESTS: null
      }
      if (search.length > 2) {
        const response = await API.employee(API.USER_ID).interests.search.get({
          queryParams: {
            search
          }
        })
        if (response.status === 'success') {
          tips = response.data
        }
      }
      this.setPopular(tips)
    }
  }
})
