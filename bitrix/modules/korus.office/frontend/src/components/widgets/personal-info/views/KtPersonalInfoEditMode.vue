<script lang="ts" setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useQuasar } from 'quasar'
import { cloneDeep } from 'lodash-es'
import { usePersonalFieldsStore, IEditField } from '@/stores/personal-fields'
import { usePermissionsStore } from '@/stores/permissions'
import { useConfigs, useChanges } from '@/composables/personal-info'
import { isEmpty, getType } from '@/utils'

import { KtForm, KtTitle, KtBtn } from '@/components/shared'
import { KtContactEditTable, KtContactEditSettings } from '@/components/widgets/personal-info/components'

interface IEmits {
  (e: 'change-mode', mode: 'read'): void;
}

const $q = useQuasar()
const emit = defineEmits<IEmits>()

const personalFieldsStore = usePersonalFieldsStore()
const { SHOW_SONET_ADMIN, SORT_DISABLE } = storeToRefs(usePermissionsStore())

const { FIELDS_EDITABLE } = storeToRefs(personalFieldsStore)
const {
  getComponentName,
  getComponentProps,
  getComponentValue,
  getComponentPreselectedValue
} = useConfigs()
const { getChangedFields } = useChanges()

const staticFieldsCodes = ['NAME', 'LAST_NAME', 'SECOND_NAME']
const mainFields = ref<IEditField[]>([])
const additionalFields = ref<IEditField[]>([])

const mainFieldsFilter = ref('')

const formRef = ref<null | typeof KtForm>(null)
const formLoading = ref(false)
const formRestoring = ref(false)
const formValid = ref(true)

const settingsVisible = ref(false)

const disableSort = ref(SORT_DISABLE.value)
const updateDefault = ref(false)
const setFromDefault = ref(false)

setEditFields()
setEditFieldsFilter()

function getContactEditFields (sortType: 'M' | 'A'): IEditField[] {
  return FIELDS_EDITABLE.value
    .filter(field => {
      return field.sort.type === sortType
    })
    .map(field => {
      return Object.assign(field, getComponentValue(field), getComponentPreselectedValue(field))
    })
    .sort((a, b) => {
      if (a.sort.value > b.sort.value)      return 1
      else if (a.sort.value < b.sort.value) return -1
      else                                  return 0
    })
}

function setEditFields () {
  mainFields.value = getContactEditFields('M')
  additionalFields.value = getContactEditFields('A')
}

function setEditFieldsFilter () {
  mainFieldsFilter.value = staticFieldsCodes
    .map(code => {
      return `.kt-contact-edit-field--${code.toLowerCase()}`
    })
    .join(', ')
}

async function submit () {
  formValid.value = Boolean(await formRef.value?.qFormRef?.validate())

  if (!formValid.value) {
    $q.notify({
      type: 'negative',
      message: 'Проверьте корректность заполнения полей',
      position: 'top'
    })

    return false
  }

  SHOW_SONET_ADMIN.value ? showSettingsPopup() : save()
}

function saveSettings (payload: {
  disableSort:   boolean;
  updateDefault: boolean;
}) {
  disableSort.value = payload.disableSort
  updateDefault.value = payload.updateDefault

  save()
}

async function save () {
  const diff = getChangedFields(mainFields.value).concat(getChangedFields(additionalFields.value))

  formLoading.value = !formRestoring.value

  try {
    await personalFieldsStore.submit(generateFormData(diff))
  }
  finally {
    formLoading.value = false
  }

  reset()
}

async function restore () {
  setFromDefault.value = true
  formRestoring.value = true
  resetFields()

  try {
    await save()
  }
  finally {
    setFromDefault.value = false
    formRestoring.value = false
  }
}

function reset () {
  resetFields()
  emit('change-mode', 'read')
  window.scrollTo(0, 0)
}

function resetFields () {
  mainFields.value.forEach(item => {
    item.value = cloneDeep(item.valueInitial)
  })

  additionalFields.value.forEach(item => {
    item.value = cloneDeep(item.valueInitial)
  })

  formRef.value?.qFormRef?.resetValidation()
  formValid.value = true
}

function generateFormData (diff: any[]) {
  const formData = new FormData()

  mainFields.value.forEach((item, index) => {
    formData.set(`sortSettings[main][${item.name}]`, String(index))
  })

  additionalFields.value.forEach((item, index) => {
    formData.set(`sortSettings[additional][${item.name}]`, String(index))
  })

  formData.set('sortSettings[disableSort]', (disableSort.value) ? 'Y' : 'N')
  formData.set('sortSettings[setFromDefault]', (setFromDefault.value) ? 'Y' : 'N')
  formData.set('sortSettings[updateDefault]', (updateDefault.value) ? 'Y' : 'N')

  if (isEmpty(diff)) {
    return formData
  }
  diff.forEach((item: any) => {
    const name = `data[${item.name}]`
    const value = item.changes

    switch (getType(item)) {
      case 'messengers':
        if (value.length > 0) {
          value.forEach((option: any, index: number) => {
            const messenger = `${name}[${index}][messenger]`
            const link = `${name}[${index}][value]`

            formData.append(messenger, option.messenger)
            formData.append(link, option.value)
          })
        } else {
          formData.set(`${name}[]`, '')
        }
        break
      case 'timezone':
        formData.set('data[AUTO_TIME_ZONE]', value.AUTO_TIME_ZONE)
        formData.set('data[TIME_ZONE]', value.TIME_ZONE)
        break
      case 'list':
        formData.set(name, value)
        break
      case 'multilist':
        value.forEach((option: number | string, index: number) => {
          formData.append(`${name}[${index}]`, String(option))
        })
        break
      default:
        formData.set(name, value)
    }
  })

  return formData
}

function showSettingsPopup () {
  settingsVisible.value = true
}
</script>

<template>
  <div class="kt-personal-contacts-edit-mode">
    <kt-form ref="formRef" @submit="submit">
      <section class="kt-personal-contacts-edit-mode__section">
        <kt-title :level="2" text="Основная информация" />

        <kt-contact-edit-table v-model="mainFields"
                               :filter="mainFieldsFilter"
                               :sort-disabled="SORT_DISABLE && !SHOW_SONET_ADMIN"
        >
          <template #value="{ row }">
            <component :is="getComponentName(row)" v-bind="getComponentProps(row)" />
          </template>
        </kt-contact-edit-table>
      </section>

      <section class="kt-personal-contacts-edit-mode__section">
        <kt-title :level="2" text="Дополнительная информация" />

        <kt-contact-edit-table v-model="additionalFields"
                               :sort-disabled="SORT_DISABLE && !SHOW_SONET_ADMIN"
        >
          <template #value="{ row }">
            <component :is="getComponentName(row)" v-bind="getComponentProps(row)" />
          </template>
        </kt-contact-edit-table>
      </section>

      <footer class="kt-personal-contacts-edit-mode__actions">
        <kt-btn theme="ghost" :label="$t('common.btn.cancel')" @click="reset" />

        <kt-btn v-if="!SORT_DISABLE || SHOW_SONET_ADMIN"
                theme="tertiary"
                :label="$t('common.btn.restore')"
                :loading="formRestoring"
                @click="restore"
        />

        <kt-btn theme="primary"
                type="submit"
                :label="$t('common.btn.save')"
                :loading="formLoading"
                :disable="!formValid"
        />
      </footer>
    </kt-form>

    <kt-contact-edit-settings v-model="settingsVisible"
                              :disable-sort="disableSort"
                              @submit="saveSettings"
    />
  </div>
</template>

<style lang="scss">
.kt-personal-contacts-edit-mode {
  &__section {
    display: flex;
    flex-direction: column;
    gap: var(--kt-ui-offset-xl);
  }

  &__section + &__section {
    margin-top: var(--kt-ui-offset-xl);
  }

  &__actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: var(--kt-ui-offset-md);
    margin-top: var(--kt-ui-offset-xl);
  }
}
</style>
