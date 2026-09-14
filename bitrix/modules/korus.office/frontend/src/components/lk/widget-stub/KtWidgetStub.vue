<script lang="ts" setup>
import { KtImg } from '@/components/shared'

interface IProps {
  text:  string;
  icon?: string;
}

const props = defineProps<IProps>()
</script>

<template>
  <div class="kt-widget-stub">
    <slot name="icon">
      <div v-if="props.icon" class="kt-widget-stub__image-wr">
        <kt-img :src="props.icon" alt="" ratio="1" no-spinner class="kt-widget-stub__image" />
      </div>
    </slot>

    <p class="kt-widget-stub__text">{{ props.text }}</p>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-widget-stub {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: var(--kt-lk-widget-stub-gap);

  @media screen and (min-width: 360px) {
    flex-wrap: nowrap;
    justify-content: initial;
  }

  &__image-wr {
    width: var(--kt-lk-widget-stub-image-size);
    height: var(--kt-lk-widget-stub-image-size);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    border-radius: 50%;
    background-color: var(--kt-lk-widget-stub-image-bg);
  }

  &__image {
    width: 50%;
    height: 50%;
  }

  &__text {
    font-size: 16px;
    text-align: center;

    @media screen and (min-width: 360px) {
      text-align: left;
    }
  }
}
</style>
