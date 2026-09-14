<template>
  <div class="kt-avatar-uploader-skeleton">
    <q-skeleton type="circle" />
  </div>
</template>

<script lang="ts" setup>
</script>

<style lang="scss">
.pgk {
  .kt-avatar-uploader-skeleton {
    $ns: &;
    $width: var(--avatar-size);
    $height: var(--avatar-size);

    display: flex;
    justify-content: center;
    align-items: center;
    width: $width;
    height: $height;
    position: relative;

    .q-skeleton {
      $size: var(--avatar-size);

      width: $size;
      height: $size;
      flex-shrink: 0;
    }
  }
}
</style>
