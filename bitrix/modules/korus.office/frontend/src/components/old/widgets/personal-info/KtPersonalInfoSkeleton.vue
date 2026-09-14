<template>
  <kt-widget-layout-skeleton :title="fullName"
                             :separator-color="separatorColor"
                             theme="dark"
                             class="kt-personal-info-skeleton"
  >
    <template #header-bottom>
      <q-skeleton type="text" width="40%" height="15px" class="q-mt-md" />
    </template>

    <kt-personal-info-read-mode-skeleton />
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { colors } from 'quasar'
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
import { KtPersonalInfoReadModeSkeleton } from './'

const { getPaletteColor } = colors
const fullName = 'User Name'
const separatorColor = getPaletteColor('app-grey-15')
</script>

<style lang="scss">
.pgk {
  .kt-personal-info-skeleton {
    .kt-widget-layout-skeleton__content {
      $padding-top: 35px;

      padding-top: $padding-top;
    }
  }
}
</style>
