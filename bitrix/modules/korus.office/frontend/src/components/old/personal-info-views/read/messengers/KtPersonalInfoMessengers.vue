<template>
  <kt-widget-layout-row :label="props.label">
    <kt-widget-layout-text v-if="props.isEmpty" :color="props.color" >
      <slot>
        {{ props.content }}
      </slot>

      <q-icon v-if="props.private"
              :name="eyeStatus ? 'kt:eye-slashed' : 'kt:eye'"
              size="16px"
              color="primary"
              class="q-ml-sm private-toggle-icon"
              style="cursor: pointer"
              @click="toggleVisibility"
      />
    </kt-widget-layout-text>

    <ul v-else class="kt-messenger-list">
      <li v-for="messenger in props.content"
          :key="messenger.messenger"
          class="kt-messenger-item"
      >
        <kt-link target="_blank" :href="getHref(messenger)" :text="messenger.value" theme="primary" />
        <span class="kt-messenger-item__icon">{{ messenger.messenger }}</span>
        <q-icon v-if="props.private"
                :name="eyeStatus ? 'kt:eye-slashed' : 'kt:eye'"
                size="16px"
                color="primary"
                class="q-ml-sm"
                style="cursor: pointer"
                @click="toggleVisibility"
        />
      </li>
    </ul>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { KtLink } from 'components/old/link'

import { usePersonalFieldsStore, IPersonalFieldUserType } from 'stores/personal-fields'
import { IUfMessenger } from 'stores/types'
const store = usePersonalFieldsStore()

interface IKtPersonalInfoMessengersProps {
  label:   string;
  content: IUfMessenger[] | string;
  color:   string;
  isEmpty: boolean;
  fieldCode: string;
  private: boolean;
  hidden: boolean;
}

const props = defineProps<IKtPersonalInfoMessengersProps>()
const fieldsStore = usePersonalFieldsStore()
const getTemplate = (code: string): string => {
  const field = fieldsStore.$state.items.find(item => item.name === 'UF_MESSENGERS') as IPersonalFieldUserType
  const allSettings = field.data.fieldInfo.SETTINGS as any[]
  const settings = allSettings.find(row => row.code === code)
  if (settings) {
    return settings.template || ''
  }

  return ''
}
const getHref = (messenger: IUfMessenger): string => {
  const template = getTemplate(messenger.messenger)
  if (template) {
    return template.replace('#login#', messenger.value)
  }

  return messenger.value
}

const eyeStatus:any = ref(props.hidden)

const toggleVisibility = () => {
  const formData = new FormData()
  formData.set('fieldCode', String(props.fieldCode))
  formData.set('value', String(eyeStatus.value ? 0 : 1))

  store.changeVisibility(formData)
  eyeStatus.value = !eyeStatus.value
}
</script>

<style lang="scss">
.pgk {
  .kt-messenger-item {
    display: flex;
    align-items: center;
  }

  .kt-messenger-item + .kt-messenger-item {
    $margin-top: 10px;

    margin-top: $margin-top;
  }

  .kt-messenger-item__label,
  .kt-messenger-item__icon {
    $color: var(--q-app-grey-8);

    color: $color;
  }

  .kt-messenger-item__label {
    $width: 120px;

    width: $width;
  }

  .kt-messenger-item__icon {
    flex-grow: 1;
    text-align: right;
    margin-left: 15px;
  }

  .kt-messenger-item__icon::before {
    $width: 1px;
    $height: 15px;
    $top: 2px;
    $left: -7px;
    $background-color: var(--q-app-grey-8);

    content: '';
    display: inline-block;
    position: relative;
    top: $top;
    left: $left;
    width: $width;
    height: $height;
    background-color: $background-color;
  }
}
</style>
