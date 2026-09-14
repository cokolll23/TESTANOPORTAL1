<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Screen, QMenuProps } from 'quasar'
import { storeToRefs } from 'pinia'
import { useBadgesStore } from '@/stores/badges'

import { KtBtn, KtMenu } from '@/components/shared'
import { KtBadgesItem, KtBadgesDetail, KtBadgesDetailPopup } from './components'

const { badges, badgesVisible } = storeToRefs(useBadgesStore())

const popoverProps = computed(() => {
  const options: QMenuProps = {
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
const popoverIndex = ref<null | number>(null)
const isPopoverVisible = ref(false)

const isDetailPopupVisible = ref(false)

function showDetailsPopup () {
  isDetailPopupVisible.value = true
}

function showPopover (index: number) {
  popoverIndex.value = index
  isPopoverVisible.value = true
}

function hidePopover () {
  popoverIndex.value = null
  isPopoverVisible.value = false
}
</script>

<template>
  <div class="kt-badges">
    <div v-for="(badge, index) in badgesVisible"
         :key="badge.name"
         class="kt-badges__item-wrapper"
    >
      <kt-badges-item :image="badge.image"
                      :color="badge.color"
                      :style="{ 'z-index': badges.length - index }"
                      @click="showPopover(index)"
                      @mouseleave="hidePopover"
      />

      <kt-menu v-if="popoverIndex === index"
               v-model="isPopoverVisible"
               :anchor="popoverProps.anchor"
               :self="popoverProps.self"
               :offset="popoverProps.offset"
               no-parent-event
               max-width="480px"
               class="kt-popover"
      >
        <kt-badges-detail :badge="badge" />
      </kt-menu>
    </div>

    <kt-btn v-if="badges.length > 4"
            round
            label="..."
            class="kt-badges__btn kt-badges-item"
            @click="showDetailsPopup"
    >
      <kt-badges-detail-popup v-model="isDetailPopupVisible" />
    </kt-btn>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-badges {
  display: flex;
  flex-direction: column;
  align-items: center;

  &__item-wrapper {
    position: relative;

    & + & {
      margin-top: -8px;
      margin-left: 0;
    }
  }

  &__btn {
    margin-top: -8px;
    z-index: 0;
    color: var(--kt-badges-more-btn-color);
    background-color: var(--kt-badges-more-btn-bg);
  }
}
</style>
