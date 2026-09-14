import { defineStore } from 'pinia'
import { formatDate } from '@/utils'
import type { IBadgesState } from './storeTypes'
import type { IBadgesApiResponse, IBadge } from './apiResponseTypes'

export const useBadgesStore = defineStore('badges', {
  state: (): IBadgesState => ({
    badges: []
  }),

  getters: {
    hasBadges (state) {
      return state.badges.length > 0
    },

    badgesVisible (state) {
      return state.badges.slice(0, 4)
    }
  },

  actions: {
    setData (rawData: IBadgesApiResponse) {
      this.$patch(state => {
        state.badges = rawData.map(badge => this.createBadge(badge))
      })
    },

    createBadge (payload: IBadge) {
      return {
        ...payload,
        date: formatDate(new Date(payload.date))
      }
    }
  }
})
