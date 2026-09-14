<template>
  <div class="kt-widget-layout-stub">
    <div class="kt-widget-layout-stub__left">
      <q-icon :name="props.icon" color="primary" class="kt-widget-layout-stub__icon" />
    </div>

    <div class="kt-widget-layout-stub__right">
      <kt-widget-layout-text color="app-grey-5" class="kt-widget-layout-stub__text">
        {{ props.text }}
      </kt-widget-layout-text>

      <kt-button outline class="kt-widget-layout-stub__btn" @click="$emit('click', $event)">
        {{ props.btnText }}
      </kt-button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { KtButton } from 'components/old/button'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'

interface IKtWidgetLayoutStubProps {
  icon:    string;
  text:    string;
  btnText: string;
}

const props = defineProps<IKtWidgetLayoutStubProps>()
defineEmits(['click'])
</script>

<style lang="scss">
.pgk {
  .kt-widget-layout-stub {
    display: flex;
    align-items: center;

    @media screen and (max-width: $breakpoint-xs) {
      flex-wrap: wrap;
      justify-content: center;
    }

    &__left {
      .q-icon {
        $width: 100px;
        $height: 100px;
        $border-radius: 50%;
        $border-color: var(--q-secondary);

        width: $width;
        height: $height;
        border-radius: $border-radius;
        border: 1px solid $border-color;

        svg {
          $size: 50%;

          width: $size;
          height: $size;
        }
      }
    }

    &__right {
      @media screen and (min-width: $breakpoint-xs) {
        $margin-left: 20px;

        margin-left: $margin-left;
      }

      @media screen and (max-width: $breakpoint-xs) {
        $width: 100%;
        $margin-top: 20px;

        width: $width;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-top: $margin-top;
      }
    }

    &__btn {
      $margin-top: 20px;

      margin-top: $margin-top;
    }
  }
}
</style>
