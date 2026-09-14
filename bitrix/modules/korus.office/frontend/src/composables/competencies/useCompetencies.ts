import { computed, ref, Ref } from 'vue'
import { useCompetenciesStore } from 'stores/competencies'

export type CompetenceMode = 'read' | 'edit' | 'stub'

export function useCompetencies (preparedTags: Ref<string[]>) {
  const store = useCompetenciesStore()

  const modeNext = computed(() => store.items.length ? 'read' : 'stub')
  const mode = ref<CompetenceMode>(modeNext.value)

  function changeMode (nextMode: CompetenceMode) {
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

  async function remove (competence: string) {
    await store.remove(competence)
  }

  function cancel () {
    preparedTags.value = []
    changeMode(modeNext.value)
  }

  function search (competence: string) {
    if (competence.length > 0) {
      store.loadTips(competence)
    }
    else {
      store.resetTips()
    }
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
