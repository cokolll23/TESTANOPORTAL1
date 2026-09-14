<template>
  <ul class="kt-gratitudes-list">
    <li v-for="gratitude in gratitudes" :key="gratitude.CODE" class="kt-gratitudes-list__item">
      <kt-gratitudes-item :icon="gratitude.ICON"
                          :is-active="gratitude.COUNT > 0"
                          :count="gratitude.COUNT"
                          :color="gratitude.ICON_COLOR"
                          @click="openGratitude(gratitude.CODE)"
      />
    </li>
  </ul>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { usePermissionsStore } from 'stores/permissions'
import { useGratitudes } from 'composables/old/gratitudes/useGratitudes'
import { KtGratitudesItem } from '../'

const { IS_OWN_PROFILE } = storeToRefs(usePermissionsStore())
const { gratitudes, openGratitudePage } = useGratitudes()

const openGratitude = (code:string) => {
  if (!IS_OWN_PROFILE.value) {
    openGratitudePage(code)
  }
}
</script>

<style lang="scss">
.pgk {
  .kt-gratitudes {
    $offset-top: 6px;
    $offset-left: 5px;

    &-list {
      $width: 100%;
      $margin: #{$offset-top * -1} 0 0 #{$offset-left * -1};

      width: $width;
      display: flex;
      flex-wrap: wrap;
      margin: $margin;

      &__item {
        $margin: $offset-top $offset-left;

        margin: $margin;
      }
    }
  }
}
</style>
