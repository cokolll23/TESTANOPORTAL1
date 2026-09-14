import { watch, Ref } from 'vue'
import { useInterestsStore } from 'stores/interests'
import { InterestMode } from './useInterests'

export function usePopularInterests (mode: Ref<InterestMode>) {
  const store = useInterestsStore()

  watch(mode, async () => {
    if (mode.value !== 'edit') {
      return false
    }

    await store.loadPopular()
  })
}
