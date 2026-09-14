<script lang="ts" setup>
import { usePersonalFieldsStore, type IPersonalFieldUserType } from '@/stores/personal-fields'
import { useLoginTemplate } from '@/composables/shared'
import type { IUfMessenger } from '@/stores/types'

import { KtLink } from '@/components/shared'
import { KtContactVisibilitySwitcherBtn } from '@/components/widgets/personal-info/components'

interface IProps {
  content:   IUfMessenger[] | string;
  fieldCode: string;
  private:   boolean;
  hidden:    boolean;
}

const props = defineProps<IProps>()
const fieldsStore = usePersonalFieldsStore()

function getTemplate (code: string) {
  const field = fieldsStore.$state.items.find(item => item.name === 'UF_MESSENGERS') as IPersonalFieldUserType
  const allSettings = field.data.fieldInfo.SETTINGS as any[]
  const settings = allSettings.find(row => row.code === code)

  if (settings) {
    return settings.template || ''
  }

  return ''
}

function getHref (messenger: IUfMessenger) {
  const template = getTemplate(messenger.messenger)

  return useLoginTemplate(messenger.value, template)
}
</script>

<template>
  <div class="kt-contact-messengers">
    <div class="kt-contact-messengers__list">
      <div v-for="messenger in props.content" :key="messenger.messenger" class="kt-contact-messenger">
        <span class="kt-contact-messenger__label">{{ messenger.messenger }}:</span>
        <kt-link target="_blank" :href="getHref(messenger)" :text="messenger.value" theme="primary" />
      </div>
    </div>

    <kt-contact-visibility-switcher-btn v-if="props.private"
                                        :field-code="props.fieldCode"
                                        :hidden="props.hidden"
    />
  </div>
</template>

<style lang="scss">
.kt-contact {
  &-messengers {
    display: flex;
    align-items: center;
    gap: 8px;

    &__list {
      display: flex;
      flex-direction: column;
      gap: var(--kt-ui-offset-lg);
    }
  }

  &-messenger {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
}
</style>
