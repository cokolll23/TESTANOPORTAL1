<template>
  <kt-widget-layout-skeleton title=""
                             theme="light"
                             :separator-color="separatorColor"
                             :background="backgroundColor"
                             class="kt-service-skeleton"
  >
    <template #default>
      <q-skeleton type="rect" width="240px" height="80px" />
    </template>

    <template #footer>
      <q-skeleton type="QBtn" width="150px" />
      <q-skeleton type="QBtn" width="100px" />
    </template>
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { colors } from 'quasar'
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'

const { getPaletteColor, changeAlpha } = colors
const separatorColor  = changeAlpha(getPaletteColor('app-grey-15'), 0.2)
const backgroundColor = 'var(--kt-service-widget-bg, rgba(18, 18, 57, 0.7))'
</script>

<style lang="scss">
.pgk {
  .kt-service-skeleton {
    &__content {
      $width: 100%;

      width: $width;
    }
  }
}
</style>
