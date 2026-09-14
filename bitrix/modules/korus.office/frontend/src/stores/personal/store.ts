import { defineStore } from 'pinia'
import { API } from '@/api'
import { formatDate } from '@/utils'
import { IUser } from '@/models'
import { IPersonalState } from './storeTypes'
import { IPersonalResponse } from './apiResponseTypes'

export const NO_VALUE_PLACEHOLDER = 'Не указано'
export const getValueTextColor = (isDefault: boolean) => isDefault ? 'app-grey-5' : 'primary'

export const usePersonalStore = defineStore('personal', {
  state: (): IPersonalState => ({
    ID: 0,
    FULL_NAME: '',
    LAST_NAME: '',
    LOGIN: '',
    NAME: '',
    PERSONAL_PHOTO: 0,
    PHOTO: null,
    SECOND_NAME: '',
    WORK_POSITION: '',
    EMAIL: '',
    PERSONAL_GENDER: 'M',
    STATUS: 'employee',
    ONLINE_STATUS: {
      IS_ONLINE: false,
      STATUS: '',
      STATUS_TEXT: '',
      LAST_SEEN: '',
      LAST_SEEN_TEXT: '',
      NOW: ''
    },
    ACTIVE: false,
    CONFIRM_CODE: '',
    DATE_REGISTER: '',
    EXTERNAL_AUTH_ID: '',
    LAST_ACTIVITY_DATE: '',
    PERSONAL_BIRTHDAY: '',
    PERSONAL_BIRTHDAY_FORMATTED: '',
    PERSONAL_CITY: '',
    PERSONAL_COUNTRY: '',
    PERSONAL_PHONE: '',
    AUTO_TIME_ZONE: '',
    TIME_ZONE: '',
    TIME_ZONE_OFFSET: 0,
    UF_EMPLOYEE_ID: null,
    UF_COMPETENCE: [],
    UF_DEPARTMENT: [],
    UF_EMPLOYMENT_DATE: null,
    COMPANY_EXPERIENCE: null,
    UF_HIDE_PERSONAL_PHONE: null,
    UF_MESSENGERS: [],
    UF_WORKPLACE: null,
    WORK_CITY: '',
    WORK_COMPANY: '',
    WORK_COUNTRY: '',
    WORK_DEPARTMENT: '',
    WORK_PHONE: '',
    IS_EXTRANET_USER: false
  }),

  getters: {
    AVATAR (state) {
      return state.PHOTO
    },

    IS_AVATAR_DEFAULT (state) {
      return state.PERSONAL_PHOTO === 0
    },

    currentUser (state): IUser {
      const {
        ID,
        NAME,
        FULL_NAME,
        LAST_NAME,
        SECOND_NAME,
        LOGIN,
        PHOTO,
        WORK_POSITION,
        EMAIL,
        PERSONAL_GENDER
      } = state

      return {
        ID,
        NAME,
        FULL_NAME,
        LAST_NAME,
        SECOND_NAME,
        LOGIN,
        PHOTO,
        WORK_POSITION,
        EMAIL,
        PERSONAL_GENDER
      }
    },

    PERSONAL_CITY_NORMALIZED (state) {
      const isEmpty = !state.PERSONAL_CITY

      return {
        value: state.PERSONAL_CITY || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    PERSONAL_BIRTHDAY_FORMATTED_NORMALIZED (state) {
      const isEmpty = !state.PERSONAL_BIRTHDAY_FORMATTED

      return {
        value: state.PERSONAL_BIRTHDAY_FORMATTED || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    EMAIL_NORMALIZED (state) {
      const isEmpty = !state.EMAIL

      return {
        value: state.EMAIL || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    WORK_PHONE_NORMALIZED (state) {
      const isEmpty = !state.WORK_PHONE

      return {
        value: state.WORK_PHONE || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    UF_MESSENGERS_NORMALIZED (state) {
      const isEmpty = state.UF_MESSENGERS.length === 0

      return {
        value: !isEmpty ? state.UF_MESSENGERS : NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    PERSONAL_PHONE_NORMALIZED (state) {
      const isEmpty = !state.PERSONAL_PHONE

      return {
        value: state.PERSONAL_PHONE || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    UF_HIDE_PERSONAL_PHONE_NORMALIZED (state) {
      return {
        value: !!state.UF_HIDE_PERSONAL_PHONE
      }
    },

    UF_WORKPLACE_NORMALIZED (state) {
      const isEmpty = !state.UF_WORKPLACE

      return {
        value: state.UF_WORKPLACE || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    COMPANY_EXPERIENCE_NORMALIZED (state) {
      const isEmpty = !state.COMPANY_EXPERIENCE

      return {
        value: state.COMPANY_EXPERIENCE || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    UF_EMPLOYEE_ID_NORMALIZED (state) {
      const isEmpty = !state.UF_EMPLOYEE_ID

      return {
        value: state.UF_EMPLOYEE_ID || NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    UF_EMPLOYMENT_DATE_NORMALIZED (state) {
      const isEmpty = !state.UF_EMPLOYMENT_DATE

      return {
        value: state.UF_EMPLOYMENT_DATE ? formatDate(new Date(String(state.UF_EMPLOYMENT_DATE))) : NO_VALUE_PLACEHOLDER,
        color: getValueTextColor(isEmpty),
        isEmpty
      }
    },

    IS_CURRENT_USER_INTEGRATOR (state) {
      return false
    },

    IS_CLOUD (state) {
      return false
    },

    ADMIN_RIGHTS_RESTRICTED (state) {
      return false
    },

    DELEGATE_ADMIN_RIGHTS_RESTRICTED (state) {
      return false
    },

    IS_FIRE_USER_ENABLED (state) {
      return true
    }
  },

  actions: {
    async loadPhoto (dataObj: FormData) {
      const response = await API.employee(API.USER_ID).photo.post({
        payload: dataObj
      })

      if (response.status === 'success') {
        this.setAvatar(response.data)
      }
    },

    async deletePhoto () {
      const response = await API.employee(API.USER_ID).photo.delete()

      if (response.status === 'success') {
        this.resetAvatar(response.data)
      }
    },

    async setAdmin () {
      // todo: переделать, когда будет время
      const bxCallback = window.__SASSetAdmin ?? null
      if (bxCallback) {
        bxCallback()
      } else {
        console.error('Method "setAdmin" is not implemented.')
      }
    },

    async setAdminRights () {
      try {
        const response = await API.employee(API.USER_ID).setAdminRights.get()

        if (response.status === 'success') {
          this.STATUS = response.data.STATUS
        }
      } catch (error) {}
    },

    async removeAdminRights () {
      try {
        const response = await API.employee(API.USER_ID).removeAdminRights.get()

        if (response.status === 'success') {
          this.STATUS = response.data.STATUS
        }
      } catch (error) {}
    },

    async fireUser () {
      try {
        const response = await API.employee(API.USER_ID).fire.get()

        if (response.status === 'success') {
          this.STATUS = response.data.STATUS
        }
      } catch (error) {}
    },

    async hireUser () {
      try {
        const response = await API.employee(API.USER_ID).hire.get()

        if (response.status === 'success') {
          this.STATUS = response.data.STATUS
        }
      } catch (error) {}
    },

    async reinviteUser () {
      console.log('store.reinviteUser')
    },

    async deleteUser () {
      console.log('store.deleteUser')
    },

    async moveToIntranet () {
      console.log('store.moveToIntranet')
    },

    async setIntegratorRights () {
      console.log('store.setIntegratorRights')
    },

    setAvatar (src: string) {
      this.PERSONAL_PHOTO = 99999  // dirty hack - поскольку идентификатор фото с бэка не возвращается
      this.PHOTO = src
    },

    resetAvatar (src?: string) {
      this.PERSONAL_PHOTO = 0
      this.PHOTO = src || null
    },

    setData (rowData: IPersonalResponse) {
      this.$patch(state => {
        Object.assign(state, rowData)
      })
    }
  }
})
