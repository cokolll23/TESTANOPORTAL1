import { describe, it, beforeEach, expect } from 'vitest'
import { mount, VueWrapper } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { createI18n  } from 'vue-i18n'
import { Quasar } from 'quasar'
import { usePersonOrgStructureStore, IPersonOrgStructureState } from 'stores/person-org-structure'
import { KtStructure } from 'components/structure'
import messages from '@/i18n'

describe('KtStructure.vue', () => {
  const selectors = {
    structure : '.kt-structure',
    head      : '.kt-structure .kt-user-card'
  }

  const state: IPersonOrgStructureState = {
    STRUCTURE: [
      {
        HEAD: '3',
        TREE: [
          { ID: '1', NAME: 'ООО «К-Team»', DEPTH_LEVEL: '1' },
          { ID: '2', NAME: 'Дирекция по информационным технологиям г. Москвы', DEPTH_LEVEL: '2' },
          { ID: '3', NAME: 'Отдел системного администрирования и технической поддержки', DEPTH_LEVEL: '3' },
          { ID: '4', NAME: 'Отдел системного администрирования г. Москвы', DEPTH_LEVEL: '4' }
        ]
      }
    ],
    USERS: {
      3: {
        3: {
          ID: 3,
          FULL_NAME: 'Андреева Александра Сергеевна',
          LAST_NAME: 'Андреева',
          NAME: 'Александра',
          LOGIN: 'andreeva',
          SECOND_NAME: 'Александра',
          PHOTO: window.location.origin + '/src/assets/default-user-avatar.svg'
        }
      }
    }
  }

  const i18n = createI18n({
    locale: 'ru',
    globalInjection: true,
    messages
  })

  let wrapper: VueWrapper
  let store: ReturnType<typeof usePersonOrgStructureStore>

  beforeEach(async () => {
    wrapper = mount(KtStructure, {
      global: {
        plugins: [
          Quasar,
          createTestingPinia({
            stubActions: false
          }),
          i18n
        ]
      }
    })

    store = usePersonOrgStructureStore()
    store.setData(state)
  })

  describe('RENDER', () => {
    it('Should render user director', () => {
      expect(wrapper.findAll(selectors.head).length).toBe(state.STRUCTURE.length)
    })
  })
})
