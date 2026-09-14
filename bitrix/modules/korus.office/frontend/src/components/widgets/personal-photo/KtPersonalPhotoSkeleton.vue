<script lang="ts" setup>
import {
  KtWidgetWrapper,
  KtUserStatusSkeleton,
  KtAvatarUploaderSkeleton,
  KtBadgesSkeleton
} from '@/components/lk'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-personal-photo-skeleton">
      <header class="kt-personal-photo-skeleton__header">
        <kt-user-status-skeleton class="kt-personal-photo-skeleton__status"/>
      </header>

      <div class="kt-personal-photo-skeleton__avatar-wrapper">
        <kt-avatar-uploader-skeleton />
        <kt-badges-skeleton class="kt-personal-photo-skeleton__badges" />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-personal-photo-skeleton {
  display: flex;
  flex-direction: column;

  &__header {
    text-align: right;
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__status {
    margin-left: auto;
  }

  &__avatar-wrapper {
    position: relative;
    margin-left: auto;
    margin-right: auto;
  }

  &__badges {
    position: absolute;
    top: 0;
    left: -9px;
    z-index: 10;
  }
}
</style>
