<template>
  <div v-if="hasBadges" class="kt-badges">
    <div v-for="(badge, index) in badgesVisible"
         :key="badge.name"
         class="kt-badges__item-wrapper"
    >
      <kt-badges-item :image="badge.image"
                      :color="badge.color"
                      :style="{ 'z-index': badges.length - index }"
                      @click="toggleTooltip(index)"
                      @mouseleave="hideTooltip"
      />

      <q-tooltip v-if="tooltipIndex==index"
                 v-model="tooltipOpen"
                 :anchor="tooltip.anchor"
                 :self="tooltip.self"
                 :offset="tooltip.offset"
                 max-width="600px"
                 class="kt-tooltip kt-badges__tooltip"
      >
        <kt-badges-detail :badge="badge" />
      </q-tooltip>
    </div>

    <kt-button v-if="badges.length > 4" round label="..." class="kt-badges__btn kt-badges-item">
      <kt-badges-detail-popup />
    </kt-button>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Screen } from 'quasar'
import { storeToRefs } from 'pinia'
import { useBadgesStore } from 'stores/badges'

import { KtButton } from 'components/old/button'
import { KtBadgesItem, KtBadgesDetail, KtBadgesDetailPopup } from './components'

const { badges } = storeToRefs(useBadgesStore())
const badgesVisible = computed(() => badges.value.slice(0, 4))
const hasBadges = computed(() => badgesVisible.value.length > 0)

const tooltip = computed(() => {
  const options: Record<string, any> = {
    self: 'top left'
  }

  if (Screen.gt.sm) {
    options.anchor = 'top right'
    options.offset = [15, 0]
  }
  else {
    options.anchor = 'bottom left'
    options.offset = [0, 15]
  }

  return options
})

const tooltipIndex = ref(null)
const tooltipOpen = ref(false)

const toggleTooltip = (index: number) => {
  tooltipIndex.value = index
  tooltipOpen.value = true
}
const hideTooltip = () => {
  tooltipIndex.value = null
  tooltipOpen.value = false
}
</script>

<style lang="scss">
.pgk {
  .kt-badges {
    $top: 0;
    $left: 8px;
    $transform: translateX(-50%);
    $z-index: 10;

    display: flex;
    flex-direction: column;
    align-items: center;
    position: absolute;
    top: $top;
    left: $left;
    transform: $transform;
    z-index: $z-index;
  }

  .kt-badges__item-wrapper {
    position: relative;
  }

  .kt-badges__item-wrapper + .kt-badges__item-wrapper {
    $margin-top: -15px;
    $margin-left: 0;

    margin-top: $margin-top;
    margin-left: $margin-left;
  }

  .kt-badges__btn {
    $margin-top: -15px;
    $background-color: var(--q-app-grey-9);
    $z-index: 0;

    margin-top: $margin-top;
    background-color: $background-color;
    z-index: $z-index;
  }
}
</style>
