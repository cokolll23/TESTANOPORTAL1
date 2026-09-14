<template>
  <kt-widget-layout-row
    :label="props.label"
    y-aligment="center"
    :class-list="props.classList">
    <q-icon v-if="props.private"
            :name="eyeStatus ? 'kt:eye-slashed' : 'kt:eye'"
            size="16px"
            color="primary"
            class="private-toggle-icon q-ml-sm"
            style="cursor: pointer"
            @click="toggleVisibility"
    />
    <template v-if="props.user">
      <template v-if="props.multiple">
        <div class="flex column">
          <kt-user-card v-for="user in props.user" :user="user" theme="secondary" :justify="props.justify" :key="user.ID"/>
        </div>
      </template>
      <kt-user-card v-if="!props.multiple" :user="props.user" theme="secondary" :justify="props.justify" />
    </template>
    <kt-widget-layout-text v-else color="app-grey-5" class="kt-widget-layout-row__label text-center">
      {{ $t('personalPhoto.replacementStaffNone') }}
    </kt-widget-layout-text>
    <slot name="footer"></slot>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtUserCard } from 'components/old/user-card'
import { IUser } from '@/models'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { ref } from 'vue'
import { usePersonalFieldsStore } from 'stores/personal-fields'
const store = usePersonalFieldsStore()

interface IKtPersonalInfoTextProps {
  label: string;
  user:  IUser;
  multiple: boolean;
  justify?: 'start' | 'center' | 'end';
  classList?: string;
  fieldCode?: string;
  private?: boolean;
  hidden?: boolean;
}

const props = defineProps<IKtPersonalInfoTextProps>()

const eyeStatus = ref(props.hidden)

const toggleVisibility = () => {
  const formData = new FormData()
  formData.set('fieldCode', String(props.fieldCode))
  formData.set('value', String(eyeStatus.value ? 0 : 1))

  store.changeVisibility(formData)
  eyeStatus.value = !eyeStatus.value
}
</script>
