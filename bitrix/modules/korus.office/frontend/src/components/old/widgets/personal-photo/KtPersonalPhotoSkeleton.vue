<template>
  <div class="kt-personal-photo-skeleton">
    <kt-user-status-skeleton class="kt-personal-photo-skeleton__status"/>
    <kt-avatar-uploader-skeleton class="q-mx-auto" />
  </div>
</template>

<script lang="ts" setup>
import { KtUserStatusSkeleton } from 'components/old/user-status'
import { KtAvatarUploaderSkeleton } from 'components/old/avatar-uploader'
</script>

<style lang="scss">
.pgk {
  .kt-personal-photo-skeleton {
    $padding: 20px;
    $border-radius: 10px;
    $background-color: var(--q-app-white);

    display: flex;
    flex-direction: column;
    padding: $padding;
    border-radius: $border-radius;
    background-color: $background-color;

    &__status {
      $margin-bottom: 6px;

      justify-content: flex-end;
      margin-bottom: $margin-bottom;
    }

    &__avatar {
      $margin: 0 auto;

      margin: $margin;
    }
  }
}
</style>
