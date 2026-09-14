import { computed, ref, watch } from 'vue'
import { StoreDefinition } from 'pinia'
import { debounce } from 'lodash'

export function usePagination<T extends ReturnType<StoreDefinition>> (store: T) {
  const isFirstPage = computed(() => store.nav.currentPage === 1)
  const isLastPage = computed(
    () => store.nav.currentPage * store.nav.pageSize >= store.nav.recordCount
  )

  const totalPages = computed(() =>
    Math.ceil(store.nav.recordCount / store.nav.pageSize)
  )
  const hasPagination = computed(() => totalPages.value > 1)
  const perPageOptions = ref([5, 10, 25])

  const isNavigationLocked = ref(false)

  const prevPage = () => {
    store.$patch(state => {
      state.nav.currentPage--
    })
  }
  const nextPage = () => {
    store.$patch(state => {
      state.nav.currentPage++
    })
  }

  const debouncedLoad = debounce(async () => {
    if (isNavigationLocked.value || !store.navInit) {
      return false
    }

    isNavigationLocked.value = true
    try {
      await store.load()
    } finally {
      isNavigationLocked.value = false
    }
  }, 500)

  watch(
    () => store.nav.currentPage,
    () => {
      debouncedLoad()
    }
  )

  watch([() => store.nav.pageSize], async () => {
    for (const tab of store.tabs) {
      tab.currentPage = 1
    }

    if (store.nav.currentPage === 1) {
      await store.load()
    } else {
      store.nav.currentPage = 1
    }
  })

  return {
    isFirstPage,
    isLastPage,

    totalPages,
    hasPagination,
    perPageOptions,

    prevPage,
    nextPage
  }
}
