import { computed, ref, type Ref } from 'vue'
import { useInterestsStore } from '@/stores/interests'
import { usePopularInterests } from '@/composables/interests/usePopularInterests'

export type InterestMode = 'read' | 'edit' | 'stub'

export function useInterests (preparedTags: Ref<string[]>) {
  const store = useInterestsStore()

  const modeNext = computed(() => store.INTERESTS.length ? 'read' : 'stub')
  const mode: Ref<InterestMode> = ref(modeNext.value)

  function changeMode (nextMode: InterestMode) {
    mode.value = nextMode
  }

  async function add (newTagModel?: string) {
    const payload = [...preparedTags.value]

    if (typeof newTagModel !== 'undefined' && newTagModel !== '' && !payload.includes(newTagModel)) {
      payload.push(newTagModel)
    }

    await store.add(payload)
    preparedTags.value = []
    changeMode(modeNext.value)
  }

  async function remove (interest: string) {
    await store.remove(interest)
  }

  function cancel () {
    preparedTags.value = []
    changeMode(modeNext.value)
  }

  function search (search: string) {
    if (search.length > 0) {
      store.loadTips(search)
    }
    else {
      store.loadPopular()
    }
  }

  usePopularInterests(mode)

  return {
    mode,
    changeMode,

    add,
    remove,
    cancel,
    search
  }
}
