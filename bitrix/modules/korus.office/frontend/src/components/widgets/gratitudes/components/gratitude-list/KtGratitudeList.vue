<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { usePermissionsStore } from '@/stores/permissions'
import { useGratitudes } from '@/composables/gratitudes/useGratitudes'

import { KtGratitude } from '@/components/widgets/gratitudes/components'

const { IS_OWN_PROFILE } = storeToRefs(usePermissionsStore())
const { gratitudes, openGratitudePage } = useGratitudes()

function openGratitude (code: string) {
  if (!IS_OWN_PROFILE.value) {
    openGratitudePage(code)
  }
}
</script>

<template>
  <ul class="kt-gratitude-list">
    <li v-for="gratitude in gratitudes" :key="gratitude.CODE" class="kt-gratitude-list__item">
      <kt-gratitude :icon="gratitude.ICON"
                    :is-active="gratitude.COUNT > 0"
                    :count="gratitude.COUNT"
                    :color="gratitude.ICON_COLOR"
                    :is-clickable="!IS_OWN_PROFILE"
                    :name="gratitude.NAME"
                    show-count
                    show-name
                    @click="openGratitude(gratitude.CODE)"
      />
    </li>
  </ul>
</template>

<style lang="scss">
@use 'vars';

.kt-gratitude-list {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  gap: var(--kt-gratitude-list-offset);
}
</style>
