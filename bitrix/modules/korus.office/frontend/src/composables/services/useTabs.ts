import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

export function useTabs () {
  const { t } = useI18n()

  const tabs = [
    {
      name: 'main',
      label: t('services.tabs.main')
    },
    {
      name: 'favorites',
      label: t('services.tabs.favorites')
    }
  ]
  const activeTab = ref('main')

  return {
    tabs,
    activeTab
  }
}
