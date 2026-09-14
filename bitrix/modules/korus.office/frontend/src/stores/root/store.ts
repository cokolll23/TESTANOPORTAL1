import { defineStore } from 'pinia'
import { isEmpty, ObjectKeys } from '@/utils'

import { usePersonalStore } from 'stores/personal'
import { usePersonalFieldsStore } from 'stores/personal-fields'
import { useBadgesStore, IBadgesApiResponse } from 'stores/badges'
import { usePermissionsStore } from 'stores/permissions'
import { useVacationsStore, IVacationsResponse } from 'stores/current-vacation'
import { usePersonOrgStructureStore } from 'stores/person-org-structure'
import { useGratitudesStore } from 'stores/gratitudes'
import { useServicesStore } from 'stores/services'
import { useRequestsStore } from 'stores/requests'
import { useShopStore } from 'stores/shop'
import { useCompetenciesStore, ICompetenciesResponse } from 'stores/competencies'
import { useAboutMeStore, IAboutResponse } from 'stores/about-me'
import { useInterestsStore, IInterestsResponse } from 'stores/interests'
import { useWorkplaceStore } from 'stores/workplace'
import { API } from '@/api'
import { IApiResponse } from 'stores/types'
import { InitializeResponse } from './apiResponseTypes'

export const useRootStore = defineStore('root', {
  state: () => ({
    isAppLoading: true,
    modules: {
      PERSONAL: {
        store: usePersonalStore,
        isVisible: false
      },

      BADGES: {
        store: useBadgesStore,
        isVisible: false
      },

      FIELDS: {
        store: usePersonalFieldsStore,
        isVisible: false
      },

      PERMISSIONS: {
        store: usePermissionsStore,
        isVisible: false
      },

      VACATIONS: {
        store: useVacationsStore,
        isVisible: false
      },

      STRUCTURE: {
        store: usePersonOrgStructureStore,
        isVisible: false
      },

      SHOP: {
        store: useShopStore,
        isVisible: false
      },

      COMPETENCIES: {
        store: useCompetenciesStore,
        isVisible: false
      },

      GRATITUDES: {
        store: useGratitudesStore,
        isVisible: false
      },

      SERVICES: {
        store: useServicesStore,
        isVisible: false
      },

      REQUESTS: {
        store: useRequestsStore,
        isVisible: false
      },

      ABOUT: {
        store: useAboutMeStore,
        isVisible: false
      },

      INTERESTS: {
        store: useInterestsStore,
        isVisible: false
      },

      WORKPLACE: {
        store: useWorkplaceStore,
        isVisible: false
      }
    }
  }),

  actions: {
    async fetchAppData () {
      // Запрос за данными
      const response: IApiResponse<InitializeResponse> = await API.employee(API.USER_ID).get()

      // Запись полученных данных
      if (response.data) {
        this.setAppData(response.data)
        this.isAppLoading = false
      }
    },

    setAppData (data: InitializeResponse) {
      const PERMISSION = this.isAppLoading ? data.PERMISSIONS : this.modules.PERMISSIONS.store()

      ObjectKeys(data).forEach(key => {
        const module = this.modules[key]

        if (!module) return

        module.store().setData(data[key] as any)

        if (['SHOP', 'SERVICES', 'REQUESTS'].includes(key)) {
          this.modules[key].isVisible = PERMISSION.IS_OWN_PROFILE
        } else if (['BADGES', 'COMPETENCIES', 'INTERESTS', 'ABOUT'].includes(key)) {
          let empty
          if (key === 'BADGES') {
            const badges = data[key] as IBadgesApiResponse
            empty = isEmpty(badges)
          } else if (key === 'ABOUT') {
            const about = data[key] as IAboutResponse
            empty = isEmpty(about?.html)
          } else if (key === 'INTERESTS') {
            const interests = data[key] as IInterestsResponse
            empty = isEmpty(interests.INTERESTS)
          } else {
            const competencies = data[key] as ICompetenciesResponse
            empty = isEmpty(competencies)
          }

          this.modules[key].isVisible = !empty || PERMISSION.IS_OWN_PROFILE || PERMISSION.CAN_EDIT
        } else if (['VACATIONS'].includes(key)) {
          const vacations = data[key] as IVacationsResponse
          const vacation = vacations.VACATION
          const empty = vacation == null || isEmpty(vacation.FROM) || isEmpty(vacation.TO)

          this.modules[key].isVisible = !empty || PERMISSION.IS_OWN_PROFILE || PERMISSION.IS_SESSION_ADMIN
        } else {
          this.modules[key].isVisible = true
        }
      })

      window.dispatchEvent(new CustomEvent('Lk:PermissionsLoaded', {
        bubbles: true,
        detail: { permissions: PERMISSION }
      }))
    }
  }
})
