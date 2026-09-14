import { computed } from 'vue'
import { useWindowSize } from '@vueuse/core'
import { useGratitudesStore, gratitudeDataInitialOld } from 'stores/gratitudes'

export function useGratitudes () {
  const gratitudeStore = useGratitudesStore()
  const gratitudes = computed(() => {
    return gratitudeStore.BADGES
      .map(BADGE => {
        const data = gratitudeDataInitialOld.find(item => item.CODE === BADGE.CODE)
        const COUNT = (gratitudeStore.BADGES_COUNTERS ?? {})[BADGE.ID]?.COUNT ?? 0

        if (typeof data === 'undefined') {
          return Object.assign({}, BADGE)
        }

        return Object.assign({}, BADGE, data, {
          COUNT,
          ICON_COLOR: COUNT > 0 ? data.ICON_COLOR : 'app-grey-9'
        })
      })
      .sort((a, b) => {
        return Number(a.SORT) - Number(b.SORT)
      })
  })

  const openGratitudePage = (code?: string) => {
    const BX = window.BX
    const { width: windowSize } = useWindowSize()
    const path = typeof code !== 'undefined'
      ? gratitudeStore.URLS.LIST + `&gratCode=${code}`
      : gratitudeStore.URLS.ADD
    const entityId = new URLSearchParams(path).get('gratUserId')
    const options = typeof code !== 'undefined'
      ? {
          width: 1000
        }
      : {
          cacheable: false,
          data: {
            entityType: 'gratPost',
            entityId
          },
          width: 1000
        }

    if (windowSize.value > 768) {
      BX.SidePanel.Instance.open(path, options)
    }
    else {
      window.location.href = path
    }
  }

  return {
    gratitudes,
    openGratitudePage
  }
}
