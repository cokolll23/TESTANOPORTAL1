import { computed, ref, Ref } from 'vue'
import { useCompetenciesStore, ICompetence } from 'stores/competencies'

export type CompetenceMode = 'read' | 'edit' | 'stub'

export function useCompetencies (preparedTags: Ref<string[]>, newTagModel: Ref<string>) {
  const store = useCompetenciesStore()

  const modeNext = computed(() => store.items.length ? 'read' : 'stub')
  const mode: Ref<CompetenceMode> = ref(modeNext.value)
  const changeMode = (nextMode: CompetenceMode) => {
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

  const remove = async (competence: ICompetence) => {
    await store.removeOld(competence)

    if (store.items.length === 0) {
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

  return {
    mode,
    changeMode,

    add,
    remove,
    cancel,
    search
  }
}
