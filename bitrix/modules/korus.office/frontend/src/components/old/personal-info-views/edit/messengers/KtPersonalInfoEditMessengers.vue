<template>
  <kt-widget-layout-row :label="props.label">
    <kt-select :model-value="props.modelValue"
               :options="props.options"
               multiple
               @update:model-value="onModelUpdate(null, $event)"
    />

    <ul class="kt-messenger-list q-mt-md">
      <li v-for="messenger in props.modelValue"
          :key="messenger.NAME"
          class="kt-messenger-item"
      >
        <span class="kt-messenger-item__label">{{ messenger.NAME }}:</span>
        <kt-input :model-value="messenger[messenger.NAME]"
                  @update:model-value="onModelUpdate(messenger.NAME, $event)"
        />
        <kt-button round
                   icon="kt:close"
                   class="kt-messenger-item__remove-btn q-ml-md"
                   @click="removeMessenger(messenger)"
        />
      </li>
    </ul>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtSelect } from 'components/old/select'
import { KtInput } from 'components/old/input'
import { KtButton } from 'components/old/button'
import { ISelectOption } from '@/models'
import type { MESSENGER } from 'stores/personal'

interface IKtPersonalInfoEditSelectProps {
  label:      string;
  modelValue: any;
  options:    ISelectOption[];
}

type MESSENGER_CODE  = string | null
type MESSENGER_EVENT = string | MESSENGER[]

const props = defineProps<IKtPersonalInfoEditSelectProps>()
const emit  = defineEmits(['update:modelValue'])

const onMessengersChange = (event: MESSENGER[]) => {
  props.modelValue.length > event.length
    ? props.modelValue.forEach((messenger: MESSENGER) => {
      if (!event.includes(messenger)) {
        messenger[messenger.NAME] = ''
      }
    })
    : event.forEach((messenger: MESSENGER) => {
      if (!messenger[messenger.NAME]) {
        messenger[messenger.NAME] = ''
      }
      return messenger
    })

  return event
}
const onMessengerLinkChange = (code: MESSENGER_CODE, event: string) => {
  return props.modelValue.map((messenger: MESSENGER) => {
    if (messenger.NAME === code) {
      messenger[messenger.NAME] = event
    }
    return messenger
  })
}
const removeMessenger = (messenger: MESSENGER) => {
  const model = props.modelValue.filter((m: any) => m !== messenger)

  emit('update:modelValue', model)
}

const onModelUpdate = (code: MESSENGER_CODE, event: MESSENGER_EVENT) => {
  const model = Array.isArray(event)
    ? onMessengersChange(event)
    : onMessengerLinkChange(code, event)

  emit('update:modelValue', model)
}
</script>

<style lang="scss">
.pgk {
  .kt-messenger-item {
    &__remove-btn {
      $font-size: 8px;

      font-size: $font-size;
    }
  }
}
</style>
