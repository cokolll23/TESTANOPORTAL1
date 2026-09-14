<script lang="ts" setup>
import { ref } from 'vue'
import { KtInput, KtPopupProxy, KtDate, KtBtn, KtIcon } from '@/components/shared'

interface IProps {
  modelValue: string;
}

interface IEmits {
  (e: 'update:model-value', event: string): void;
}

const props = defineProps<IProps>()
const emit = defineEmits<IEmits>()

const isPopupVisible = ref(false)

function updateModel (event: any) {
  emit('update:model-value', event)
}

function showPopup () {
  isPopupVisible.value = true
}
</script>

<template>
  <kt-input :model-value="props.modelValue"
            @update:model-value="updateModel"
            class="kt-datepicker"
  >
    <template #append>
      <kt-icon name="calendar" class="kt-datepicker__icon" @click="showPopup" />

      <kt-popup-proxy v-model="isPopupVisible"
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                      class="kt-datepicker__popup"
      >
        <kt-date :model-value="props.modelValue"
                 mask="DD.MM.YYYY"
                 @update:model-value="updateModel"
        >
          <div class="row items-center justify-end">
            <kt-btn theme="primary" :label="$t('datepicker.closePopup')" v-close-popup />
          </div>
        </kt-date>
      </kt-popup-proxy>
    </template>
  </kt-input>
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-datepicker {
    &__icon {
      color: var(--kt-ui-select-arrow-color);
      cursor: pointer;
    }

    &__popup {
      --kt-popover-padding: 0;
    }
  }
}
</style>
