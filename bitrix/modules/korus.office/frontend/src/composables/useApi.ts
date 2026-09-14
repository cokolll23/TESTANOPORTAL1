import { restClient } from '@/services/http'
import { useRoute } from 'vue-router'

let employeeApi: typeof restClient
let componentApi: typeof restClient
let isRegistered = false

export function useApi () {
  if (isRegistered) {
    return { employeeApi, componentApi }
  }

  const route = useRoute()
  employeeApi = restClient
    .register({
      employees: {
        [`${route.params.id}`]: {
          personal: true,
          about: true,
          photo: true,
          structure: true,
          competencies: true,
          interests: true,
          gratitudes: true,
          services: true,
          vacations: {
            current: true
          },
          requests: true,
          shop: true
        }
      }
    })
  componentApi = restClient
    .register({
      components: {
        editor: true
      }
    })

  isRegistered = true

  return { employeeApi, componentApi }
}
