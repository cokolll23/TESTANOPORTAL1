<template>
  <div class="kt-personal-info-edit-mode">
    <q-form ref="editFormRef" @submit="submit">
      <div>
        <h6>Основная информация:</h6>
      </div>
      <template v-for="FIELD in staticFields" :key="FIELD.name">
        <kt-personal-info-edit-field :field="FIELD"/>
      </template>
      <vue-draggable v-model="mainFields"
                     v-bind="dragOptions"
                     v-if="!SORT_DISABLE || SHOW_SONET_ADMIN"
                     item-key="name"
                     group="a"
                     handle=".draggable-handle-icon"
                     @start="isDragging = true"
                     @end="isDragging = false"
      >
        <template #item="{ element }">
          <div :key="element.name">
            <q-btn flat padding="none" icon="drag_indicator" class="draggable-handle-icon" />
            <kt-personal-info-edit-field :field="element" />
          </div>
        </template>
      </vue-draggable>
      <template v-else v-for="FIELD in mainFields" :key="FIELD.name">
        <kt-personal-info-edit-field :field="FIELD"/>
      </template>
      <div>
        <h6>Дополнительная информация:</h6>
      </div>
      <vue-draggable v-model="additionalFields"
                     v-bind="dragOptions"
                     handle=".draggable-handle-icon"
                     item-key="name"
                     group="a"
                     v-if="!SORT_DISABLE || SHOW_SONET_ADMIN"
                     @start="isDragging = true"
                     @end="isDragging = false"
      >
        <template #item="{ element }">
          <div :key="element.name">
            <q-btn flat padding="none" icon="drag_indicator" class="draggable-handle-icon" />
            <kt-personal-info-edit-field :field="element" />
          </div>
        </template>
      </vue-draggable>
      <template v-else v-for="FIELD in additionalFields" :key="FIELD.name">
        <kt-personal-info-edit-field :field="FIELD"/>
      </template>
      <div v-if="SHOW_SONET_ADMIN">
        <h6>Управление сортировкой полей:</h6>
        <q-checkbox v-model="SORT_DISABLE">
          {{ $t('personalInfoEdit.checkboxes.disableSort') }}
        </q-checkbox>
        <q-checkbox v-model="setFromDefault" v-if="!SORT_DISABLE || SHOW_SONET_ADMIN">
          {{ $t('personalInfoEdit.checkboxes.setFromDefault') }}
        </q-checkbox>
        <q-checkbox v-model="updateDefault">
          {{ $t('personalInfoEdit.checkboxes.updateDefault') }}
        </q-checkbox>
      </div>
      <div class="kt-personal-info-edit-mode__actions">
        <kt-button theme="primary" :loading="isLoading" :disable="!editFormValid" @click="submit">
          {{ $t('personalInfoEdit.btnSave') }}
        </kt-button>

        <kt-button @click="reset">
          {{ $t('personalInfoEdit.btnCancel') }}
        </kt-button>
      </div>
    </q-form>
  </div>
</template>

<script lang="ts" setup>
import { ref, Ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { QForm, useQuasar, extend } from 'quasar'
import vueDraggable from 'vuedraggable'
import { usePersonalFieldsStore, IEditFields } from 'stores/personal-fields'
import { useConfigs, useChanges } from '@/composables/personal-info'
import { KtButton } from 'components/old/button'
import { getType, isEmpty } from '@/utils'
import KtPersonalInfoEditField from "./KtPersonalInfoEditField.vue"
import { usePermissionsStore } from "stores/permissions"
const { SHOW_SONET_ADMIN, SORT_DISABLE } = storeToRefs(usePermissionsStore())

const $q = useQuasar()
const emit = defineEmits(['change-mode'])
const store = usePersonalFieldsStore()

const isLoading = ref(false)
const { FIELDS_EDITABLE } = storeToRefs(store)

const isDragging = ref(false)
const dragOptions = computed(() => ({
  animation: 150,
  disabled: false
}))
const draggableModel = ref([])

const setFromDefault = ref(false)
const updateDefault = ref(false)

const {
  getComponentValue,
  getComponentPreselectedValue
} = useConfigs()
const { getChangedFields } = useChanges()

const mainFields: Ref<IEditFields> = ref([])
const staticFields: Ref<IEditFields> = ref([])
const additionalFields: Ref<IEditFields> = ref([])

const staticFieldsCodes = ['NAME', 'LAST_NAME', 'SECOND_NAME']

const sortField = function (a: any, b: any) {
  if (a.sort.value > b.sort.value) {
    return 1
  } else if (a.sort.value < b.sort.value) {
    return -1
  }
  return 0
}

const setFields = () => {
  FIELDS_EDITABLE.value.forEach(field => {
    const fieldObject = Object.assign(field, getComponentValue(field), getComponentPreselectedValue(field))
    if (field.sort.type === 'M') {
      if (staticFieldsCodes.indexOf(field.name) !== -1) {
        staticFields.value.push(fieldObject)
      } else {
        mainFields.value.push(fieldObject)
      }
    }
    if (field.sort.type === 'A') {
      additionalFields.value.push(fieldObject)
    }
  })

  mainFields.value.sort(sortField)
  staticFields.value.sort(sortField)
  additionalFields.value.sort(sortField)
}

const editFormRef: Ref<QForm | null> = ref(null)
const editFormValid = ref(true)

const generateFormData = (diff: any[]) => {
  const formData = new FormData()

  mainFields.value.forEach((item, index) => {
    formData.set(`sortSettings[main][${item.name}]`, String(index))
  })

  additionalFields.value.forEach((item, index) => {
    formData.set(`sortSettings[additional][${item.name}]`, String(index))
  })

  formData.set('sortSettings[disableSort]', (SORT_DISABLE.value) ? 'Y' : 'N')
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

const reset = () => {
  emit('change-mode', 'read')
  window.scrollTo(0, 0)
  mainFields.value.forEach(item => {
    item.value = item.valueInitial !== null && typeof item.valueInitial === 'object'
      ? extend(true, Array.isArray(item.valueInitial) ? [] : {}, item.valueInitial)
      : item.valueInitial
  })
  additionalFields.value.forEach(item => {
    item.value = item.valueInitial !== null && typeof item.valueInitial === 'object'
      ? extend(true, Array.isArray(item.valueInitial) ? [] : {}, item.valueInitial)
      : item.valueInitial
  })
  editFormRef.value?.resetValidation()
  editFormValid.value = true
}
const submit = async () => {
  editFormValid.value = Boolean(await editFormRef.value?.validate())

  if (!editFormValid.value) {
    $q.notify({
      type: 'negative',
      message: 'Проверьте корректность заполнения полей',
      position: 'top'
    })

    return false
  }

  const diff = getChangedFields(mainFields.value)
    .concat(getChangedFields(additionalFields.value))
    .concat(getChangedFields(staticFields.value))

  isLoading.value = true
  await store.submit(generateFormData(diff))
  isLoading.value = false

  reset()
}

setFields()
store.$onAction(({ after, name }) => {
  if (name !== 'submit') {
    return false
  }

  after(setFields)
})
</script>

<style lang="scss">
.pgk {
  .kt-personal-info-edit-mode {
    $width: 100%;

    width: $width;

    h6 {
      margin: 20px 0;
    }

    &__actions {
      width: 100%;

      display: flex;
      justify-content: center;
      align-items: center;

      position: fixed;
      left: 0;
      bottom: 0;
      z-index: 100;

      padding: 10px 0;
      background-color: var(--q-white);
    }

    .kt-widget-layout-row .kt-widget-layout-row__label,
    .kt-widget-layout-row .kt-widget-layout-row__content {
      $width: 100%;

      width: $width;
    }

    .kt-widget-layout-row.is-checkbox-field .kt-widget-layout-row__label {
      $width: 40%;

      width: $width;
    }

    .kt-widget-layout-row.is-checkbox-field .kt-widget-layout-row__content {
      $width: auto;

      width: $width;
    }

    .kt-widget-layout-row.is-datetime-field .kt-widget-layout-row__content {
      $width: 50%;

      width: $width;
      flex-grow: 0;

      @media screen and (max-width: $breakpoint-xs) {
        $width: 100%;

        width: $width;
      }
    }

    .q-field__control,
    .q-field__marginal {
      $height: 40px;

      height: $height;
      min-height: $height;
    }

    .kt-selector.q-field--dense {
      .q-field__control {
        height: auto;
      }
    }

    .q-field--auto-height .q-field__control,
    .q-field--auto-height .q-field__native {
      $min-height: 40px;

      min-height: $min-height;
    }
  }
  .draggable-handle-icon {
    position: absolute;
    left: -24px;
  }
}
</style>
