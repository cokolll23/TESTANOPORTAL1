import { restClient } from '@/services/http'

export const API = {
  USER_ID: '0',
  created: false,
  employee: restClient,
  component: restClient,
  requests: restClient,

  create (USER_ID: string) {
    if (this.created) {
      return false
    }

    this.USER_ID = USER_ID

    this.createEmployeeApi()
    this.createComponentApi()
    this.createRequestApi()

    this.created = true
  },

  createEmployeeApi () {
    this.employee = restClient
      .register({
        employees: {
          personal: true,
          about: true,
          photo: true,
          structure: true,
          competencies: {
            search: true
          },
          fieldSettings: true,
          interests: {
            search: true
          },
          gratitudes: true,
          services: true,
          vacations: {
            current: true
          },
          requests: true,
          shop: true,
          fire: true,
          hire: true,
          setAdminRights: true,
          removeAdminRights: true
        }
      })
      .employees
  },

  createComponentApi () {
    this.component = restClient
      .register({
        components: {
          editor: true
        }
      })
      .components
  },

  createRequestApi () {
    this.requests = restClient
      .register({
        requests: {
          comments: {
            view: true
          }
        }
      })
      .requests
  }
}
