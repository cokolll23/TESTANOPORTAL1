<template>
  <q-input :model-value="props.modelValue"
           outlined
           @update:model-value="$emit('update:modelValue', $event)"
           class="kt-datepicker"
  >
    <template #append>
      <q-icon name="event" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-date :model-value="props.modelValue"
                  mask="DD.MM.YYYY"
                  @update:model-value="$emit('update:modelValue', $event)"
          >
            <div class="row items-center justify-end">
              <kt-button theme="primary" v-close-popup>
                {{ $t('datepicker.closePopup') }}
              </kt-button>
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>

<script lang="ts" setup>
import { KtButton } from 'components/old/button'

interface IKtDatepickerProps {
  modelValue: string;
}

const props = defineProps<IKtDatepickerProps>()
defineEmits(['update:modelValue'])
</script>

<style lang="scss">
.pgk {
  .kt-datepicker {
    $font-size: 14px;
    $line-height: 18px;

    font-size: $font-size;
    line-height: $line-height;
  }
}
</style>
