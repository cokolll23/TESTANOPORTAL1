import { computed, ref, Ref } from 'vue'

export function useSlides<SlideType> (items:Ref<SlideType[]>, itemsPerSlide: Ref<number>, active = 0) {
  const slideActive = ref(active)
  const slides = computed(() => _createSlides<SlideType>(items.value, itemsPerSlide))
  const slidesControlsVisible = computed(() => items.value.length > itemsPerSlide.value)

  return {
    slides,
    slideActive,
    slidesControlsVisible
  }
}

function _createSlides<SlideType> (items: SlideType[], perSlide: Ref<number>) {
  const slides = []
  let i = 0

  while (i < items.length) {
    const slideItems = []
    let j = i

    while (j < perSlide.value + i) {
      const item = items[j]

      if (item !== undefined) {
        slideItems.push(item)
      }

      j++
    }

    slides.push(slideItems)
    i = j
  }

  return slides
}
