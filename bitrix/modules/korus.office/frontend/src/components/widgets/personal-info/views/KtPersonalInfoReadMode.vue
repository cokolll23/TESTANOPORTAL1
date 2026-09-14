<script lang="ts" setup>
import { markRaw } from 'vue'
import { storeToRefs } from 'pinia'
import { usePersonalStore, NO_VALUE_PLACEHOLDER } from '@/stores/personal'
import { usePersonalFieldsStore, type IPersonalField } from '@/stores/personal-fields'
import { usePermissionsStore } from '@/stores/permissions'
import { isEmpty, getTimezoneFormatted } from '@/utils'

import { KtDetails } from '@/components/shared'
import {
  KtContactTable,
  KtContactLink,
  KtContactText,
  KtContactPhone,
  KtContactUsers,
  KtContactWorkplace,
  KtContactMessengers
} from '../components'

const personalStore = usePersonalStore()
const personalFieldsStore = usePersonalFieldsStore()

const { FIELDS } = storeToRefs(personalFieldsStore)
const { IS_OWN_PROFILE } = storeToRefs(usePermissionsStore())

const mainFields = getContactFields('M')
const additionalFields = getContactFields('A')

function getContactFields (sortType: 'M' | 'A'): IPersonalField[] {
  const excludeFields = ['NAME', 'LAST_NAME', 'SECOND_NAME', 'PERSONAL_PHOTO']

  return FIELDS.value
    .filter(field => {
      return (
        field.sort.type === sortType
        && !excludeFields.includes(field.name)
        && !isEmpty(personalStore.$state[field.name])
        && (!field.management || IS_OWN_PROFILE.value || !field.managementStatus)
      )
    })
    .sort((a, b) => {
      if (a.sort.value > b.sort.value)      return 1
      else if (a.sort.value < b.sort.value) return -1
      else                                  return 0
    })
}

function getContactComponentName (field: IPersonalField) {
  switch (field.type) {
    case 'link':
      return markRaw(KtContactLink)
    case 'phone':
      return markRaw(KtContactPhone)
    case 'userField':
      if (field.data.fieldInfo.USER_TYPE_ID === 'employee') {
        return markRaw(KtContactUsers)
      }
      if (field.name === 'UF_WORKPLACE') {
        return markRaw(KtContactWorkplace)
      }
      if (field.name === 'UF_MESSENGERS') {
        return markRaw(KtContactMessengers)
      }

      return markRaw(KtContactText)
    default:
      return markRaw(KtContactText)
  }
}

function getContactComponentValue (field: IPersonalField) {
  const value = personalStore.$state[field.name]

  switch (field.type) {
    case 'datetime': {
      if (field.name === 'PERSONAL_BIRTHDAY') {
        return personalStore.PERSONAL_BIRTHDAY_FORMATTED
      }

      const format = field.data.dateViewFormat
      const timestamp = new Date(value as string).getTime() / 1000

      return value ? BX.date.format(format, timestamp) : NO_VALUE_PLACEHOLDER
    }
    case 'list': {
      const item = field.data.items.find(item => {
        return String(item.VALUE) === String(value)
      })

      return value ? (item?.NAME || NO_VALUE_PLACEHOLDER) : field.data.items[0].NAME
    }
    case 'multilist': {
      if (typeof value === 'object' && value != null) {
        const items = field.data.items.filter(item => {
          const itemValue = typeof item.VALUE === 'string' ? parseInt(item.VALUE) : item.VALUE

          return (value as number[]).includes(itemValue)
        })

        return items.map(item => item.NAME).join(', ')
      }

      return null
    }
    case 'timezone':
      return getTimezoneFormatted({
        autoTimeZone: personalStore.$state.AUTO_TIME_ZONE,
        timeZone: String(value)
      })
    case 'userField':
      if (field.data.fieldInfo.USER_TYPE_ID === 'boolean') {
        return value ? 'Да' : 'Нет'
      }

      if (field.data.fieldInfo.USER_TYPE_ID === 'date') {
        const format = 'd F Y'
        const timestamp = new Date(value as string).getTime() / 1000

        return value ? BX.date.format(format, timestamp) : NO_VALUE_PLACEHOLDER
      }

      if (
        field.data.fieldInfo.USER_TYPE_ID === 'employee'
        || field.name === 'UF_MESSENGERS'
        || field.name === 'UF_WORKPLACE'
      ) {
        return value
      }

      return value ? String(value) : NO_VALUE_PLACEHOLDER
    default:
      return value ? String(value) : NO_VALUE_PLACEHOLDER
  }
}

function getContactComponentProps (field: IPersonalField) {
  return {
    content: getContactComponentValue(field),
    fieldCode: field.name,
    private: field.management,
    hidden: field.managementStatus
  }
}
</script>

<template>
  <div class="kt-personal-contacts-read-mode">
    <kt-details :expand-text="$t('personalContacts.details.expandText')"
                :collapse-text="$t('personalContacts.details.collapseText')"
    >
      <template #header>
        <kt-contact-table :rows="mainFields">
          <template #value="{ row }">
            <component :is="getContactComponentName(row)" v-bind="getContactComponentProps(row)" />
          </template>
        </kt-contact-table>
      </template>

      <kt-contact-table :rows="additionalFields">
        <template #value="{ row }">
          <component :is="getContactComponentName(row)" v-bind="getContactComponentProps(row)" />
        </template>
      </kt-contact-table>
    </kt-details>
  </div>
</template>

<style lang="scss">
.kt-personal-contacts-read-mode {
}
</style>
