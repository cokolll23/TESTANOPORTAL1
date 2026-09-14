import { defineStore } from 'pinia'
import { extend } from 'quasar'
import { ObjectKeys, formatDate } from '@/utils'
import { useLegacy } from '@/composables/legacy'
import { API } from '@/api'
import { IUser } from '@/models'
import { IApiResponse, INav } from '@/stores/types'
import { IGratitudesResponse } from './apiResponseTypes'
import { IGratitudesState, IGratitudePosts, IGratitudeInitial } from './storeTypes'

import {
  beerIcon,
  cakeIcon,
  crownIcon,
  cupIcon,
  drinkIcon,
  flagIcon,
  flowerIcon,
  giftIcon,
  heartIcon,
  likeIcon,
  moneyIcon,
  smileIcon,
  starIcon,
  thefirstIcon
} from '@/assets/widgets/gratitudes/icons'

export const gratitudeDataInitialOld: IGratitudeInitial[] = [
  { CODE: 'thumbsup', ICON: 'kt:like',     ICON_COLOR: '#6e54d1' },
  { CODE: 'gift',     ICON: 'kt:gift',     ICON_COLOR: '#5cd1df' },
  { CODE: 'cup',      ICON: 'kt:cup',      ICON_COLOR: '#ffa800' },
  { CODE: 'money',    ICON: 'kt:money',    ICON_COLOR: '#05b5ab' },
  { CODE: 'crown',    ICON: 'kt:crown',    ICON_COLOR: '#a7abb0' },
  { CODE: 'drink',    ICON: 'kt:drink',    ICON_COLOR: '#2fc7f7' },
  { CODE: 'cake',     ICON: 'kt:cake',     ICON_COLOR: '#b47153' },
  { CODE: 'thefirst', ICON: 'kt:thefirst', ICON_COLOR: '#fff1b2' },
  { CODE: 'flag',     ICON: 'kt:flag',     ICON_COLOR: '#fb6dba' },
  { CODE: 'star',     ICON: 'kt:star',     ICON_COLOR: '#29ad49' },
  { CODE: 'heart',    ICON: 'kt:heart',    ICON_COLOR: '#fe5857' },
  { CODE: 'beer',     ICON: 'kt:beer',     ICON_COLOR: '#ae914b' },
  { CODE: 'flowers',  ICON: 'kt:flower',   ICON_COLOR: '#ff7b9d' },
  { CODE: 'smile',    ICON: 'kt:smile',    ICON_COLOR: '#bada0b' }
]

export const gratitudeDataInitial: IGratitudeInitial[] = [
  { CODE: 'thumbsup', ICON: likeIcon,     ICON_COLOR: 'var(--kt-ui-purple-50-hover)' },
  { CODE: 'gift',     ICON: giftIcon,     ICON_COLOR: 'var(--kt-ui-cyan-50)' },
  { CODE: 'cup',      ICON: cupIcon,      ICON_COLOR: 'var(--kt-ui-yellow-50-hover)' },
  { CODE: 'money',    ICON: moneyIcon,    ICON_COLOR: 'var(--kt-ui-teal-50)' },
  { CODE: 'crown',    ICON: crownIcon,    ICON_COLOR: 'var(--kt-ui-purple-50)' },
  { CODE: 'drink',    ICON: drinkIcon,    ICON_COLOR: 'var(--kt-ui-blue-50)' },
  { CODE: 'cake',     ICON: cakeIcon,     ICON_COLOR: 'var(--kt-ui-orange-50)' },
  { CODE: 'thefirst', ICON: thefirstIcon, ICON_COLOR: 'var(--kt-ui-yellow-50)' },
  { CODE: 'flag',     ICON: flagIcon,     ICON_COLOR: 'var(--kt-ui-magenta-50)' },
  { CODE: 'star',     ICON: starIcon,     ICON_COLOR: 'var(--kt-ui-green-50-hover)' },
  { CODE: 'heart',    ICON: heartIcon,    ICON_COLOR: 'var(--kt-ui-red-50-hover)' },
  { CODE: 'beer',     ICON: beerIcon,     ICON_COLOR: 'var(--kt-ui-orange-50-hover)' },
  { CODE: 'flowers',  ICON: flowerIcon,   ICON_COLOR: 'var(--kt-ui-magenta-50-hover)' },
  { CODE: 'smile',    ICON: smileIcon,    ICON_COLOR: 'var(--kt-ui-green-50)' }
]

export const useGratitudesStore = defineStore('gratitudes', {
  state: (): IGratitudesState => ({
    BADGES: [],
    BADGES_COUNTERS: Object.create(null),
    NAV: Object.create(null),
    POSTS: Object.create(null),
    URLS: Object.create(null),
    USERS: Object.create(null)
  }),

  getters: {
    hasPosts (state) {
      return ObjectKeys(state.POSTS ?? Object.create(null)).length > 0
    }
  },

  actions: {
    async loadMorePosts () {
      const nextPage = this.NAV.currentPage + 1
      await this.loadPosts(nextPage)
    },

    async loadPreviousPosts () {
      const prevPage = this.NAV.currentPage - 1
      await this.loadPosts(prevPage)
    },

    async loadPosts (nextPage: number) {
      const response: IApiResponse<IGratitudesResponse> = await API.employee(API.USER_ID).gratitudes.get({
        queryParams: {
          nav: `page-${nextPage}-size-${this.NAV.pageSize}`
        }
      })

      if (response.status === 'success') {
        const { NAV, USERS, POSTS } = response.data

        this.updateNav(NAV)
        this.addUsers(USERS)

        if (useLegacy().isLegacyMode.value) {
          this.addPostsOld(POSTS)
        }
        else {
          this.addPosts(POSTS)
        }
      }
    },

    setData (rowData: IGratitudesResponse) {
      if (rowData.POSTS) {
        this.preparePosts(rowData.POSTS)
      }

      this.$patch(state => {
        extend(true, state, rowData)
      })
    },

    updateNav (newNav: INav) {
      this.$patch(state => {
        state.NAV = newNav
      })
    },

    addUsers (users: Record<string, IUser>) {
      this.$patch(state => {
        Object.assign(state.USERS, users)
      })
    },

    addPosts (posts: IGratitudePosts) {
      if (posts === null) {
        return false
      }

      if (this.POSTS == null) {
        this.POSTS = Object.create(null)
      }

      this.preparePosts(posts)

      if (this.POSTS) {
        Object.assign(this.POSTS, posts)
      }
    },

    addPostsOld (posts: IGratitudePosts) {
      if (posts === null) {
        return false
      }

      if (this.POSTS == null) {
        this.POSTS = Object.create(null)
      }

      this.preparePosts(posts)

      this.$patch(state => {
        state.POSTS = posts
      })
    },

    preparePosts (posts: NonNullable<IGratitudePosts>) {
      ObjectKeys(posts).forEach(ID => {
        const POST = posts[ID]

        POST.DATE_PUBLISH = BX.date.format('l, d F, Y', new Date(POST.DATE_PUBLISH).getTime() / 1000)
      })
    }
  }
})
