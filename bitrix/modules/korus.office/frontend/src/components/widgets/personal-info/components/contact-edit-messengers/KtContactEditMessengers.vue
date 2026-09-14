<script lang="ts" setup>
import { computed } from 'vue'
import type { ISelectOption } from '@/models'
import type { MESSENGER } from '@/stores/personal'
import { isEmpty } from '@/utils'

import { KtSelect, KtInput, KtBtn } from '@/components/shared'

interface IProps {
  modelValue: any;
  options:    ISelectOption[];
}

interface IEmits {
  (e: 'update:model-value', event: any): void;
}

type MESSENGER_CODE  = string | null
type MESSENGER_EVENT = string | MESSENGER[]

const props = defineProps<IProps>()
const emit  = defineEmits<IEmits>()

const hasMessengers = computed(() => {
  return !isEmpty(props.modelValue)
})

function onMessengersChange (event: MESSENGER[]) {
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

function onMessengerLinkChange (code: MESSENGER_CODE, event: string) {
  return props.modelValue.map((messenger: MESSENGER) => {
    if (messenger.NAME === code) {
      messenger[messenger.NAME] = event
    }
    return messenger
  })
}

function removeMessenger (messenger: MESSENGER) {
  const model = props.modelValue.filter((m: any) => m !== messenger)

  emit('update:model-value', model)
}

function onModelUpdate (code: MESSENGER_CODE, event: MESSENGER_EVENT) {
  const model = Array.isArray(event)
    ? onMessengersChange(event)
    : onMessengerLinkChange(code, event)

  emit('update:model-value', model)
}
</script>

<template>
  <div class="kt-contact-edit-messengers">
    <kt-select :model-value="props.modelValue"
               :options="props.options"
               option-value="VALUE"
               option-label="NAME"
               hide-bottom-space
               multiple
               @update:model-value="onModelUpdate(null, $event)"
    />

    <ul v-if="hasMessengers" class="kt-contact-edit-messengers__list">
      <li v-for="messenger in props.modelValue"
          :key="messenger.NAME"
          class="kt-contact-edit-messenger"
      >
        <span class="kt-contact-edit-messenger__label">{{ messenger.NAME }}:</span>
        <kt-input :model-value="messenger[messenger.NAME]"
                  class="kt-contact-edit-messenger__input"
                  @update:model-value="onModelUpdate(messenger.NAME, $event)"
        />
        <kt-btn theme="ghost"
                icon="close"
                round
                dense
                class="kt-contact-edit-messenger__remove-btn"
                @click="removeMessenger(messenger)"
        />
      </li>
    </ul>
  </div>
</template>

<style lang="scss">
.kt-contact-edit {
  &-messengers__list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: var(--kt-ui-offset-lg);
  }

  &-messenger {
    display: flex;
    align-items: center;
    gap: 16px;

    &__label {
      width: 160px;
    }

    &__input {
      flex-grow: 1;
    }

    &__remove-btn {
      font-size: 8px;
      color: var(--kt-ui-icon-primary);
    }
  }
}
</style>
