<script lang="ts" setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useResizeObserver } from '@vueuse/core'
import { KtTooltip } from '@/components/shared'

interface IProps {
  lines:    number;
  content:  string;
  htmlMode: boolean;
}

const props = withDefaults(defineProps<IProps>(), {
  lines: 2,
  htmlMode: false
})

const textClampRef = ref<null | HTMLElement>(null)

const linesCurrent = ref(0)
const isTooltipVisible = ref(false)
const isTextTruncated = computed(() => {
  return linesCurrent.value > props.lines
})

onMounted(start)

function start () {
  update()
  useResizeObserver(textClampRef, update)
}

async function update () {
  const textClamp = textClampRef.value

  if (textClamp) {
    textClamp.style.webkitLineClamp = ''

    await nextTick()

    linesCurrent.value = getCurrentLinesCount()
    textClamp.style.webkitLineClamp = String(props.lines)
  }
}

function getCurrentLinesCount () {
  if (textClampRef.value == null) {
    return 0
  }

  const height = textClampRef.value.offsetHeight
  const lineHeight = parseInt(getComputedStyle(textClampRef.value).lineHeight)

  return Math.floor(height / lineHeight)
}

function showTooltip () {
  isTooltipVisible.value = true
}

function hideTooltip () {
  isTooltipVisible.value = false
}
</script>

<template>
  <div class="kt-text-clamp-wr">
    <div class="kt-text-clamp" ref="textClampRef" @pointerenter="showTooltip" @pointerleave="hideTooltip">
      <div v-if="props.htmlMode" v-html="props.content" />
      <template v-else>{{ props.content }}</template>
    </div>

    <kt-tooltip v-if="isTextTruncated"
                v-model="isTooltipVisible"
                :target="textClampRef"
                :offset="[0, 4]"
                anchor="bottom middle"
                self="top middle"
                no-parent-event
    >
      <div ref="textClampTooltipContentRef" class="all-pointer-events">
        <div v-if="props.htmlMode" v-html="props.content" />
        <template v-else>{{ props.content }}</template>
      </div>
    </kt-tooltip>
  </div>
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-text-clamp {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
}
</style>
