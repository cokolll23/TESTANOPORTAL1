<template>
  <kt-widget-layout-skeleton :title="$t('interests.title')"
                             padding-bottom="30px"
                             class="kt-interests-skeleton"
  >
    <kt-widget-layout-stub-skeleton />
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
import { KtWidgetLayoutStubSkeleton } from 'components/old/widget-layout-stub'
</script>
