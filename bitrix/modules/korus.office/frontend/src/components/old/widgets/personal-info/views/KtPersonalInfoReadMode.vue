<template>
  <div class="kt-personal-info-read-mode">
    <div class="row full-width">
      <div class="column col-xs-12 xl-inline-block col-xl-7 q-space relative-position">
        <kt-button v-if="CAN_EDIT"
                   theme="text"
                   class="q-ml-auto kt-personal-info-read-mode__change-mode-btn"
                   @click="$emit('change-mode', 'edit')"
        >
          Изменить
        </kt-button>

        <q-expansion-item v-model="isExpanded" expand-icon-toggle class="kt-personal-info-read-mode__show-more">
          <template #header>
            <template v-for="field in mainFields" :key="field.name">
              <template v-if="!isEmpty(personalStore.$state[field.name])">
                <kt-personal-info-user
                  :label="field.title"
                  :user="getComponentValue(field)"
                  :color="getComponentColor(field)"
                  :multiple="field?.data?.fieldInfo?.MULTIPLE === 'Y'"
                  v-if="field?.data?.fieldInfo?.USER_TYPE_ID === 'employee'"
                  :private="getFieldManagement(field.name)"
                  :hidden="getFieldManagementStatus(field.name)"
                  :field-code="field.name"
                  class-list="w-100"
                />
                <component :is="getComponentName(field)"
                           :label="field.title"
                           :content="getComponentValue(field)"
                           :color="getComponentColor(field)"
                           v-else
                           :private="getFieldManagement(field.name)"
                           :hidden="getFieldManagementStatus(field.name)"
                           :field-code="field.name"
                />
              </template>
            </template>
          </template>

          <template #default>
            <template v-for="field in additionalFields" :key="field.name">
              <template v-if="!isEmpty(personalStore.$state[field.name])">
                <kt-personal-info-user
                  :label="field.title"
                  :user="getComponentValue(field)"
                  :color="getComponentColor(field)"
                  :multiple="field?.data?.fieldInfo?.MULTIPLE === 'Y'"
                  v-if="field?.data?.fieldInfo?.USER_TYPE_ID === 'employee'"
                  :private="getFieldManagement(field.name)"
                  :hidden="getFieldManagementStatus(field.name)"
                  :field-code="field.name"
                  class-list="w-100"
                />
                <component :is="getComponentName(field)"
                           :label="field.title"
                           :content="getComponentValue(field)"
                           :color="getComponentColor(field)"
                           v-else
                           :private="getFieldManagement(field.name)"
                           :hidden="getFieldManagementStatus(field.name)"
                           :field-code="field.name"
                />
              </template>
            </template>
          </template>
        </q-expansion-item>
      </div>

      <q-separator vertical color="app-grey-15" class="kt-personal-info-read-mode__vertical-separator" />
      <q-separator color="app-grey-15" class="kt-personal-info-read-mode__horizontal-separator" />

      <div class="col-xs-12 xl-inline-block col-xl-4 q-space">
        <q-expansion-item v-model="isExpanded" expand-icon-toggle class="kt-personal-info-read-mode__show-more">
          <template #header>
            <kt-structure />
          </template>
        </q-expansion-item>
      </div>
    </div>

    <q-separator class="q-my-xs full-width" color="app-grey-15" />

    <footer class="q-pt-sm full-width" :class="isExpanded ? 'is-expanded' : ''">
      <kt-button theme="text" color="primary" class="kt-show-more-btn" @click="toggle">
        {{ expandStateText }} <q-icon name="kt:chevron-down" />
      </kt-button>
    </footer>
  </div>
</template>

<script lang="ts" setup>
import { Component, markRaw, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useExpand } from '@/composables/useExpand'
import { NO_VALUE_PLACEHOLDER, usePersonalStore } from 'stores/personal'
import { usePersonalFieldsStore, IEditField, IPersonalField } from 'stores/personal-fields'
import { usePermissionsStore } from 'stores/permissions'
import { formatDate, isEmpty, getTimezoneFormatted } from '@/utils'

import {
  KtPersonalInfoText,
  KtPersonalInfoLink,
  KtPersonalInfoPhone,
  KtPersonalInfoMessengers,
  KtPersonalInfoWorkplace,
  KtPersonalInfoUser
} from 'components/old/personal-info-views/read'
import { KtStructure } from 'components/old/structure'
import { KtButton } from 'components/old/button'

defineEmits(['change-mode'])

const fieldsStore = usePersonalFieldsStore()
const { FIELDS } = storeToRefs(fieldsStore)
const { CAN_EDIT } = storeToRefs(usePermissionsStore())

const {
  PERSONAL_CITY_NORMALIZED,
  EMAIL_NORMALIZED,
  WORK_PHONE_NORMALIZED,
  UF_MESSENGERS_NORMALIZED,
  PERSONAL_PHONE_NORMALIZED,
  UF_HIDE_PERSONAL_PHONE_NORMALIZED,
  UF_WORKPLACE_NORMALIZED,
  COMPANY_EXPERIENCE_NORMALIZED,
  UF_EMPLOYEE_ID_NORMALIZED,
  UF_EMPLOYMENT_DATE_NORMALIZED,
  PERSONAL_BIRTHDAY_FORMATTED_NORMALIZED
} = storeToRefs(usePersonalStore())
const personalStore = usePersonalStore()

const { isExpanded, toggle, expandStateText } = useExpand({
  defaultValue: false,
  expandText: 'Показать больше',
  expandedText: 'Свернуть'
})

const excludeFields = ['NAME', 'LAST_NAME', 'SECOND_NAME', 'PERSONAL_PHOTO']

const viewSort = function (a: IPersonalField, b: IPersonalField) {
  if (a.sort.value > b.sort.value) {
    return 1
  } else if (a.sort.value < b.sort.value) {
    return -1
  }
  return 0
}

const mainFields = FIELDS.value.filter(field => field.sort.type === 'M' && !excludeFields.includes(field.name)).sort(viewSort)
const additionalFields = FIELDS.value.filter(field => field.sort.type === 'A' && !excludeFields.includes(field.name)).sort(viewSort)

const getFieldTitle = (code: string): string | undefined => {
  const field = FIELDS.value.find(field => code === field.name)

  return field?.title
}

const getFieldManagement = (code: string): string | undefined | boolean => {
  const field = FIELDS.value.find(field => code === field.name)
  return field?.management
}
const getFieldManagementStatus = (code: string): string | undefined | boolean => {
  const field = FIELDS.value.find(field => code === field.name)
  return field?.managementStatus
}

const getComponentName = (field: IEditField): Component => {
  switch (field.type) {
    case 'link':
      return markRaw(KtPersonalInfoLink)
    case 'phone':
      return markRaw(KtPersonalInfoPhone)
    case 'userField':
      if (field.name === 'UF_WORKPLACE') {
        return markRaw(KtPersonalInfoWorkplace)
      }
      if (field.name === 'UF_MESSENGERS') {
        return markRaw(KtPersonalInfoMessengers)
      }

      if (field.data.fieldInfo.USER_TYPE_ID === 'employee') {
        return markRaw(KtPersonalInfoUser)
      }
      return markRaw(KtPersonalInfoText)
    default:
      return markRaw(KtPersonalInfoText)
  }
}

const getComponentValue = (field: IEditField): any => {
  const value = personalStore.$state[field.name]

  switch (field.type) {
    case 'datetime':
      if (field.name === 'PERSONAL_BIRTHDAY') {
        return personalStore.$state.PERSONAL_BIRTHDAY_FORMATTED
      }

      return value ? formatDate(new Date(String(value))) : NO_VALUE_PLACEHOLDER

    case 'multilist': {
      if (typeof value === 'object' && value !== null) {
        const items = field.data.items.filter(item => value.indexOf(parseInt(item.VALUE)) !== -1)

        return items.map(item => item.NAME).join(', ')
      }

      return null
    }
    case 'list': {
      const item = field.data.items.find(item => String(item.VALUE) === String(value))

      return value ? (item?.NAME || NO_VALUE_PLACEHOLDER) : field.data.items[0].NAME
    }
    case 'timezone': {
      return getTimezoneFormatted({
        autoTimeZone: personalStore.$state.AUTO_TIME_ZONE,
        timeZone: String(value)
      })
    }
    case 'userField':
      if (field.data.fieldInfo.USER_TYPE_ID === 'boolean') {
        return value ? 'Да' : 'Нет'
      }

      if (field.data.fieldInfo.USER_TYPE_ID === 'date') {
        return value ? formatDate(new Date(String(value))) : NO_VALUE_PLACEHOLDER
      }

      if (field.data.fieldInfo.USER_TYPE_ID === 'employee' || field.name === 'UF_MESSENGERS' || field.name === 'UF_WORKPLACE') {
        return value
      }

      return !value ? NO_VALUE_PLACEHOLDER : String(value)
    default:
      return !value ? NO_VALUE_PLACEHOLDER : String(value)
  }
}

const getComponentColor = (field: IEditField): string => {
  const value = personalStore.$state[field.name]
  const isBool = field.type === 'userField' && field.data.fieldInfo.USER_TYPE_ID === 'boolean'

  return value || isBool ? 'primary' : 'app-grey-5'
}
</script>

<style lang="scss">
.pgk {
  .kt-personal-info-read-mode {
    &__change-mode-btn {
      $top: -30px;
      $right: -15px;

      position: absolute;
      top: $top;
      right: $right;
    }

    .kt-widget-layout-row__label {
      $min-width: 150px;
      $max-width: 150px;
      $margin-right: 30px;

      min-width: $min-width;
      max-width: $max-width;
      margin-right: $margin-right;

      @media screen and (max-width: $breakpoint-xs) {
        $min-width: auto;
        $max-width: auto;

        min-width: $min-width;
        max-width: $max-width;
      }
    }

    &__show-more {
      $padding: 0 0 30px 0;

      padding: $padding;

      .q-item {
        $min-height: initial;
        $padding: 0;

        flex-wrap: wrap;
        min-height: $min-height;
        padding: $padding;

        &__section--side {
          display: none;
        }
      }
    }

    &__vertical-separator {
      $margin: 0 30px 30px;

      margin: $margin;

      @media screen and (max-width: $breakpoint-md) {
        display: none;
      }
    }

    &__horizontal-separator {
      $width: 100%;
      $height: 1px;
      $margin: 10px 0;

      display: none;
      width: $width;
      height: $height;
      margin: $margin;

      @media screen and (max-width: $breakpoint-md) {
        display: block;
      }
    }

    .xl-inline-block {
      @media (min-width: 1366px) and (max-width: 1599px) {

        height: auto;
        width: 41.6667%;
      }
    }

    .kt-widget-layout-row__content {
      max-width: unset;
      position: relative;
    }
  }

  .w-100 {
    width: 100%;
  }
}
</style>
