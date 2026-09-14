<template>
  <kt-widget-layout-skeleton :title="$t('gratitudes.title')"
                             actions-align="center"
                             class="kt-gratitudes-skeleton"
  >
    <kt-gratitudes-list-skeleton />
    <kt-gratitudes-post-list-skeleton />

    <template #footer>
      <q-skeleton type="QBtn" width="150px" class="q-mx-auto" />
    </template>
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
import { KtGratitudesListSkeleton, KtGratitudesPostListSkeleton } from './components'
</script>
