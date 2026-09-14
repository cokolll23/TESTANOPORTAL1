<template>
  <kt-personal-info-edit-timezone v-if="FIELD.type === 'timezone'"
                                  :label="FIELD.title"
                                  :timezone-items="FIELD.data.timezone_items"
                                  :timezone-items-auto="FIELD.data.auto_timezone_items"
                                  v-model:timezone="FIELD.value.timezoneValue"
                                  v-model:timezoneAuto="FIELD.value.timezoneValueAuto"
  />

  <kt-selector v-else-if="FIELD.type === 'userField' && isEmployeeField(FIELD)"
               :label="FIELD.title"
               v-model="FIELD.value"
               :entities="[{ id: 'user' }]"
               :preselected-items="FIELD.preselectedValue"
               :multiple="FIELD.data.fieldInfo.MULTIPLE === 'Y'"
               :target-element="null"
               :dense="true"
               lazy-rules
  />
  <kt-personal-info-edit-workplace v-else-if="FIELD.name === 'UF_WORKPLACE'"
                                   :class="getComponentClassName(FIELD)"
                                   v-bind="getComponentProps(FIELD)"
                                   v-model="FIELD.value"
  />

  <component v-else
             :is="getComponentName(FIELD)"
             :class="getComponentClassName(FIELD)"
             v-bind="getComponentProps(FIELD)"
             v-model="FIELD.value"
  />
</template>

<script lang="ts" setup>
import { KtPersonalInfoEditTimezone, KtPersonalInfoEditWorkplace } from "components/old/personal-info-views/edit"
import { KtSelector } from "components/old/personal-info-views/edit/kt-selector"
import { useChanges, useConfigs } from "composables/old/personal-info"
import { IEditField } from "stores/personal-fields"
import { ref } from "vue"

const {
  getComponentName,
  getComponentClassName,
  getComponentProps,
  isEmployeeField
} = useConfigs()
const { getChangedFields } = useChanges()

interface IKtPersonalInfoEditField {
  field: IEditField;
}

const props = defineProps<IKtPersonalInfoEditField>()

const FIELD = ref(props.field)
</script>
