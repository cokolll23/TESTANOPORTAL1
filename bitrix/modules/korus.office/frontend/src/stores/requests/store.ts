import { nextTick } from 'vue'
import { defineStore } from 'pinia'
import { IApiResponse, INav } from 'stores/types'
import { API } from '@/api'
import { formatDate } from '@/utils'
import { useLegacy } from '@/composables/legacy'
import { IRequestsResponse, IRequestRow } from './apiResponseTypes'
import { IRequest, IRequestsState, IRequestTabName } from './storeTypes'

export const useRequestsStore = defineStore('requests', {
  state: (): IRequestsState => ({
    tabCurrent: 'active',
    tabs: [
      { name: 'active', label: 'Активные', currentPage: 1 },
      { name: 'closed', label: 'Закрыты', currentPage: 1 }
    ],
    list: [],
    nav: {
      currentPage: 1,
      pageSize: 0,
      recordCount: 0
    },
    navInit: false
  }),

  actions: {
    async load (query: Record<string, string | number>) {
      const response: IApiResponse<IRequestsResponse> = await API.employee(API.USER_ID).requests.get({
        queryParams: {
          status: this.tabCurrent,
          nav: `page-${this.nav.currentPage}-size-${this.nav.pageSize}`,
          ...query
        }
      })

      if (response.status === 'success') {
        this.setData(response.data)
      }
    },

    async markCommentViewed (id: number) {
      const item = this.list.find(el => el.id === id)
      if (item?.comment.new) {
        const response: IApiResponse = await API.requests(id).comments.view.post()

        if (response.status === 'success') {
          item.comment.new = false
        }
      }
    },

    changeTab (newTab: IRequestTabName) {
      this.$patch(state => {
        state.tabCurrent = newTab
      })
    },

    setListOld (rowData: IRequestsResponse) {
      this.$patch(state => {
        state.list = []

        let index = -1
        const listLength = rowData.list.length

        while (++index < listLength) {
          const request: IRequest = Object.create(null)
          const requestRow: IRequestRow = rowData.list[index]

          request.tabName = 'active'
          request.id = requestRow.id
          request.title = rowData.types.find(type => type.id === requestRow.type)?.title ?? ''
          request.dateCreate = formatDate(new Date(requestRow.dateCreate))
          request.dateEnd = formatDate(new Date(requestRow.dateEnd))
          request.maxDeadline = formatDate(new Date(requestRow.maxDeadline))
          request.status = {
            text: rowData.statuses.find(status => status.id === requestRow.status)?.title ?? '',
            color: requestRow.color
          }

          request.response = []
          for (const userId of requestRow.response) {
            request.response.push(rowData.users[userId])
          }

          request.comment = requestRow.comment

          state.list.push(request)
        }
      })
    },

    setList (rowData: IRequestsResponse) {
      const { statuses, list, types, users } = rowData
      const dateFormat = 'd.m.Y'
      const listLength = list.length

      this.list = []

      try {
        let index = -1
        while (++index < listLength) {
          const request: IRequest = Object.create(null)
          const {
            id,
            type,
            dateCreate,
            dateEnd,
            maxDeadline,
            isOverdue,
            status,
            color,
            response,
            comment,
            indicator,
            countMessage
          } = list[index]

          request.tabName = 'active'
          request.id = id
          request.title = types.find(item => item.id === type)?.title ?? ''
          request.dateCreate = dateCreate
          request.dateCreateFormatted = BX.date.format(dateFormat, new Date(dateCreate).getTime() / 1000)

          request.dateEnd = dateEnd
          request.dateEndFormatted = BX.date.format(dateFormat, new Date(dateEnd).getTime() / 1000)

          request.maxDeadline = maxDeadline
          request.isOverdue = isOverdue
          request.maxDeadlineFormatted = BX.date.format(dateFormat, new Date(maxDeadline).getTime() / 1000)

          request.status = {
            id: status,
            text: statuses.find(item => item.id === status)?.title ?? '',
            theme: color
          }
          request.indicator = indicator

          request.response = []
          for (const userId of response) {
            request.response.push(users[userId])
          }

          request.comment = comment

          if (countMessage != null) {
            request.countMessage = countMessage
          }

          this.list.push(request)
        }
      }
      catch (error) {
        console.error('Ошибка парсинга данных списка заявок:', error)
      }
    },

    setNav (newNav: INav) {
      try {
        if (this.nav.pageSize === 0) {
          this.nav.pageSize = newNav.pageSize
        }

        this.nav.currentPage = newNav.currentPage
        this.nav.recordCount = newNav.recordCount
      }
      catch (error) {
        console.error('Ошибка парсинга данных навигации списка заявок:', error)
      }
    },

    setData (rowData: IRequestsResponse) {
      if (useLegacy().isLegacyMode.value) {
        this.setListOld(rowData)
      }
      else {
        this.setList(rowData)
      }

      this.setNav(rowData.nav)

      nextTick().then(() => {
        this.navInit = true
      })
    },

    resetNavInit (newTab: IRequestTabName) {
      this.$patch(state => {
        state.navInit = false

        const currentTab = state.tabs.find(function (element) {
          return element.name === state.tabCurrent
        })
        if (currentTab !== undefined) {
          currentTab.currentPage = state.nav.currentPage
        }

        const tab = state.tabs.find(function (element) {
          return element.name === newTab
        })
        if (tab !== undefined) {
          state.nav.currentPage = tab.currentPage
        }
      })
    }
  }
})
