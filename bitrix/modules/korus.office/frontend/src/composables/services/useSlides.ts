import { computed, reactive, ref, Ref } from 'vue'
import { Screen } from 'quasar'
import { useServicesStore, IService } from 'stores/services'

export function useSlides (activeTab: Ref) {
  const servicesStore = useServicesStore()

  const widgetsPerSlide = computed(() => {
    if (Screen.gt.lg) return 3
    else if (Screen.gt.md || Screen.sm) return 2

    return 1
  })

  const slideActiveMain = ref(0)
  const slideActiveFavorites = ref(0)

  const slides = reactive({
    main: computed(() => _createSlides(servicesStore.SERVICES.MAIN, widgetsPerSlide)),
    favorites: computed(() => _createSlides(servicesStore.SERVICES.FAVORITE, widgetsPerSlide))
  })

  const slidesControlsVisible = computed(() => {
    if (activeTab.value === 'main') {
      return servicesStore.SERVICES.MAIN.length > widgetsPerSlide.value
    }

    return servicesStore.SERVICES.FAVORITE.length > widgetsPerSlide.value
  })

  return {
    slides,
    slideActiveMain,
    slideActiveFavorites,
    slidesControlsVisible,
    widgetsPerSlide
  }
}

function _createSlides (widgets: IService[], perSlide: Ref) {
  const slides = []
  let i = 0

  while (i < widgets.length) {
    const slideWidgets = []
    let j = i

    while (j < perSlide.value + i) {
      const widget = widgets[j]

      if (widget !== undefined) {
        slideWidgets.push(widget)
      }

      j++
    }

    slides.push(slideWidgets)
    i = j
  }

  return slides
}
