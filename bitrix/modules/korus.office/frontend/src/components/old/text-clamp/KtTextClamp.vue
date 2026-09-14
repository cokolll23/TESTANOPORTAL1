<template>
  <div class="kt-text-clamp" :key="clampKey">
    <div ref="clampRef" class="kt-text-clamp__text">{{ clampedText }}</div>

    <q-tooltip v-if="hasTooltip" class="kt-text-clamp__tooltip">
      {{ props.text }}
    </q-tooltip>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick, Ref } from 'vue'
import { debounce } from 'quasar'
import { clamp } from '@/services/clamp/clamp'

interface IKtTextClampProps {
  text:               string;
  maxLines?:          number | 'auto';
  showFullInTooltip?: boolean;
}

const props = withDefaults(defineProps<IKtTextClampProps>(), {
  maxLines: 1,
  showFullInTooltip: false
})

const clampRef: Ref<HTMLElement | null> = ref(null)
const clampKey = ref('clamp')
const clampedText = ref(props.text)
const isClamped = ref(false)
const hasTooltip = computed(() => props.showFullInTooltip && isClamped.value)

const clampText = () => {
  clampedText.value = props.text
  const { clamped } = clamp(clampRef.value, {
    clamp: props.maxLines,
    useNativeClamp: false
  })

  isClamped.value = clamped !== undefined
}
const onPageResize = debounce(async () => {
  clampedText.value = props.text
  /**
   * Чтобы при ресайзе обновить `clamp` надо изменить ссылку на DOM-элемент
   * `clampRef.value`. Для этого меняем ключ у родительского элемента, чтобы
   * перерендерить компонент заново
   */
  clampKey.value = clampKey.value === 'clamp' ? '_clamp' : 'clamp'

  await nextTick()

  clampText()
}, 100)

onMounted(() => {
  clampText()
  window.addEventListener('resize', onPageResize)
})
onBeforeUnmount(() => {
  window.removeEventListener('resize', onPageResize)
})

watch(() => [props.text, props.maxLines], clampText)
</script>
