import { ref, watch, Ref } from 'vue'
import { useRequestsStore, IRequestTabName } from 'stores/requests'

export function useTabs () {
  const requestsStore = useRequestsStore()

  const tabs = [
    { name: 'active', label: 'Активные' },
    { name: 'closed', label: 'Закрыты' }
  ]
  const activeTab: Ref<IRequestTabName> = ref('active')

  watch(activeTab, async (newTab) => {
    requestsStore.resetNavInit(newTab)
    await requestsStore.load({
      status: newTab
    })
    requestsStore.changeTab(newTab)
  })

  return {
    tabs,
    activeTab
  }
}
