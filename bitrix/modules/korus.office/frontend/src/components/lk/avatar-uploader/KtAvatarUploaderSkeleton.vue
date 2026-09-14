<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
</script>

<template>
  <kt-skeleton type="circle" class="kt-avatar-uploader-skeleton" />
</template>

<style lang="scss">
.kt-avatar-uploader-skeleton {
  width: var(--kt-personal-photo-avatar-size-mobile);
  height: var(--kt-personal-photo-avatar-size-mobile);

  @media screen and (min-width: 992px) {
    width: var(--kt-personal-photo-avatar-size-desktop);
    height: var(--kt-personal-photo-avatar-size-desktop);
  }
}
</style>
