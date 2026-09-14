import { computed, ref, Ref } from 'vue'
import { useInterestsStore, IInterest } from 'stores/interests'
import { usePopularInterests } from '@/composables/old/interests/usePopularInterests'

export type InterestMode = 'read' | 'edit' | 'stub'

export function useInterests (preparedTags: Ref<string[]>, newTagModel: Ref<string>) {
  const store = useInterestsStore()

  const modeNext = computed(() => store.INTERESTS.length ? 'read' : 'stub')
  const mode: Ref<InterestMode> = ref(modeNext.value)
  const changeMode = (nextMode: InterestMode) => {
    mode.value = nextMode
  }

  const add = async () => {
    const payload = [...preparedTags.value]

    if (newTagModel.value !== '') {
      payload.push(newTagModel.value)
    }

    await store.add(payload)
    preparedTags.value = []
    newTagModel.value = ''
    changeMode(modeNext.value)
  }
  const remove = async (interest: IInterest) => {
    await store.removeOld(interest)

    if (store.INTERESTS.length === 0) {
      changeMode(modeNext.value)
    }
  }
  const cancel = () => {
    preparedTags.value = []
    changeMode(modeNext.value)
  }

  const search = (search: string) => {
    store.loadTips(search)
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
