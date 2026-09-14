<script lang="ts" setup>
import { ref, computed } from 'vue'
import { omit } from 'lodash-es'
import { QDialog, QDialogProps } from 'quasar'

import { KtTitle, KtBtn } from '@/components/shared'

interface IProps extends QDialogProps {
  titleText?: string;
}

interface IEmits {
  (e: 'update:model-value', state: boolean): void;
}

const props = withDefaults(defineProps<IProps>(), {
  position: 'standard',
  modelValue: null,
  transitionShow: 'fade',
  transitionHide: 'fade',
  transitionDuration: 300
})
const propsDialog = computed(() => {
  return omit(props, ['titleText'])
})

const emit = defineEmits<IEmits>()

const qDialogRef = ref<null | typeof QDialog>(null)

defineExpose({
  qDialogRef
})

function close () {
  emit('update:model-value', false)
}
</script>

<template>
  <q-dialog v-bind="propsDialog" ref="qDialogRef" class="kt-dialog">
    <div class="kt-dialog__outer">
      <div class="kt-dialog__inner">
        <kt-btn theme="secondary"
                icon="close"
                round
                dense
                class="kt-dialog__close-btn"
                @click="close"
        />

        <header v-if="$slots.header || props.titleText" class="kt-dialog__header">
          <slot name="header">
            <kt-title :level="2" :text="props.titleText" class="kt-dialog__title" />
          </slot>
        </header>

        <div class="kt-dialog__content" ref="dialogContentRef">
          <slot></slot>
        </div>
      </div>
    </div>
  </q-dialog>
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-dialog {
    .q-dialog__inner--minimized {
      padding: var(--kt-ui-offset-lg);
    }

    .q-dialog__inner > div {
      pointer-events: none;
      overflow: revert;
      will-change: revert;
      border-radius: revert;
    }

    .q-dialog__inner--minimized > div {
      max-height: revert;

      @media screen and (min-width: 600px) {
        max-width: revert;
      }
    }

    &__outer {
      padding-right: 40px;
      position: relative;
    }

    &__inner {
      max-height: calc(100vh - 32px);
      padding: var(--kt-ui-offset-lg) var(--kt-ui-offset-xl);
      border-radius: var(--kt-ui-border-radius-lg);
      color: var(--kt-ui-modal-text-color);
      background-color: var(--kt-ui-modal-bg-color);
      pointer-events: all;
      overflow: auto;
      will-change: scroll-position;
    }

    .q-dialog__inner--minimized .kt-dialog__inner {
      @media screen and (min-width: 600px) {
        max-width: 560px;
      }
    }

    &__title {
      font-family: var(--kt-ui-modal-title-font);
      color: var(--kt-ui-modal-title-text-color);
    }

    &__close-btn {
      position: absolute;
      top: 0;
      right: 0;
    }

    &__close-btn .q-icon {
      font-size: 20px;
    }

    &__header {
      margin-bottom: 4px;
    }
  }
}

body:not(.pgk).platform-ios .q-dialog__inner--minimized > div,
body:not(.pgk).platform-android:not(.native-mobile) .q-dialog__inner--minimized > div {
  max-height: calc(100vh - 92px);
}
</style>
