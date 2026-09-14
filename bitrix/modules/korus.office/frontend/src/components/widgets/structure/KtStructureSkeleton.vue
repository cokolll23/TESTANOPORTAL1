<script lang="ts" setup>
import { KtTreeSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
import { KtStructureUserListSkeleton } from '@/components/widgets/structure/components'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-personal-structure-skeleton">
      <header class="kt-personal-structure-skeleton__header">
        <kt-widget-title-skeleton />
      </header>

      <div class="kt-personal-structure-skeleton__content">
        <kt-tree-skeleton />
        <kt-structure-user-list-skeleton />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-personal-structure-skeleton {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  .kt-structure-user-list-skeleton {
    margin-top: var(--kt-ui-offset-xl);
  }
}
</style>
