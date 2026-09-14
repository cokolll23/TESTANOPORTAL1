<script lang="ts" setup>
import { ref, Ref, computed, watch, onMounted, onUnmounted, defineSlots, type VNode } from 'vue'
import { useI18n } from 'vue-i18n'
import { Notify, QField, QFieldProps, QFieldSlots } from 'quasar'
import { omit } from 'lodash-es'
import { $vuelidate } from '@/boot/vuelidate-rules'
import { useField, useValidationRules } from '@/composables/shared'
import { isEmpty, ObjectKeys } from '@/utils'
import { getIconSource } from '@/components/shared/icon/getSource'
import {
  IDialogOptions,
  IEntityOptions,
  IFooterContent,
  IItemId,
  IItemOptions,
  IItemModel,
  ITargetElement
} from './types'

import { KtBtnLink, KtIcon } from '@/components/shared'

interface IProps extends QFieldProps {
  id: string;
  entities: IEntityOptions[];
  textMode?: boolean;
  validation?: (keyof typeof $vuelidate)[];
  showDropdownIcon?: boolean;
  showActionMoreBtn?: boolean;
  showPopupByFieldClick?: boolean;
  footer?: IFooterContent;
  preselectedItems?: IItemId[];
  preload?: boolean;
  multiple?: boolean;
  dropdownMode?: boolean;
  enableSearch?: boolean;
  autoHide?: boolean;
  hideByEsc?: boolean;
  hideOnSelect?: boolean;
  hideOnDeselect?: boolean;
  size?: string;
  targetElement?: ITargetElement;
}

interface IEmits {
  (e: 'update:model-value', model: IItemModel | IItemModel[]): void;

  (e: 'select:item', item: IItemOptions): void;

  (e: 'show'): void;

  (e: 'hide'): void;
}

interface ISlots extends Omit<QFieldSlots, 'append' | 'control'> {
  selected: (scope: {
    selectedEntities: Ref<IItemOptions[]>;
    showSelector: (trigger?: 'field') => void;
  }) => VNode[];
}

const loading = ref(true)

const props = withDefaults(defineProps<IProps>(), {
  outlined: false,
  standout: false,
  dense: false,
  dropdownMode: true,
  enableSearch: true,
  autoHide: true,
  multiple: false,
  hideByEsc: false,
  hideOnSelect: true,
  hideOnDeselect: false,
  footer: null,
  borderless: false,
  showPopupByFieldClick: false,
  showDropdownIcon: false,
  showActionMoreBtn: false,
  size: 'md',
  targetElement: 'field'
})
const propsField = computed(() => {
  return omit(props, [
    'id',
    'entities',
    'textMode',
    'validation',
    'showDropdownIcon',
    'showActionMoreBtn',
    'showPopupByFieldClick',
    'footer',
    'preselectedItems',
    'preload',
    'multiple',
    'dropdownMode',
    'enableSearch',
    'autoHide',
    'hideByEsc',
    'hideOnSelect',
    'hideOnDeselect',
    'size',
    'targetElement'
  ])
})

const emit = defineEmits<IEmits>()

const slots = defineSlots<ISlots>()
const slotsVisible = computed<typeof slots>(() => {
  const slotsExcluded = ['append', 'control', 'selected']

  return ObjectKeys(slots)
    .filter(slotName => {
      return !slotsExcluded.includes(slotName)
    })
    .reduce((acc, slotName) => {
      acc[slotName] = slots[slotName]
      return acc
    }, Object.create(null))
})

BX.namespace('BX.Kt.Selector')

const { t } = useI18n()

const defaultSize = computed(() => ['sm', 'md'].includes(props.size))
const layoutClasses = computed(() => ({
  [`kt-selector--${props.size}`]: defaultSize.value,
  'q-field--text-mode': props.textMode
}))

const shouldUpdateModel = ref(false)
const selectorRef = ref<QField>()
const selectedEntities: Ref<IItemOptions[]> = ref([])
const selectorTriggerEl: Ref<null | HTMLElement> = ref(null)

const actionBtnText = computed(() => {
  if (isEmpty(props.modelValue)) {
    return t('common.btn.add')
  }

  return props.multiple
    ? t('common.btn.addMore')
    : t('common.btn.change')
})

const { validate, resetValidation } = useField(selectorRef, props)
const { rules } = useValidationRules(props.rules, props.validation)

const clearIcon = computed(() => {
  if (typeof props.clearIcon === 'undefined') {
    return undefined
  }

  return getIconSource(props.clearIcon)
})

defineExpose({
  selectorRef,
  validate,
  resetValidation
})

onMounted(initSelector)
onUnmounted(destroySelector)

watch(() => props.preselectedItems, onChangePreselectedItems)
watch(() => props.loading, onChangeLoading)
watch(() => props.id, onChangedId)
watch(() => props.targetElement, onChangeTargetElement)
watch(() => props.hideOnSelect, onChangeHideOnSelect)
watch(() => props.hideOnDeselect, onChangeHideOnDeselect)

function onChangePreselectedItems () {
  if (getSelector().loadState === 'DONE') {
    deselectItems()
  }

  getSelector().setPreselectedItems(props.preselectedItems)

  if (getSelector().loadState === 'UNSENT') {
    loading.value = true
    shouldUpdateModel.value = true
    getSelector().load()
  } else if (getSelector().loadState === 'DONE') {
    selectItems()
    updateModel()
    selectedEntities.value = getSelector().getSelectedItems()
  }
}

function onChangeLoading () {
  loading.value = Boolean(props.loading)
}

function onChangedId (id: string, oldId: string) {
  BX.Kt.Selector[id] = BX.Kt.Selector[oldId]
  delete BX.Kt.Selector[oldId]
}

function onChangeTargetElement () {
  const popup = getPopup()
  const target = getSelectorTarget()

  popup.setBindElement(target)
}

function onChangeHideOnSelect () {
  getSelector().setHideOnSelect(props.hideOnSelect)
}

function onChangeHideOnDeselect () {
  getSelector().setHideOnDeselect(props.hideOnDeselect)
}

function updateModel () {
  const selectedItems = getSelector().getSelectedItems()
  const model = props.multiple
    ? selectedItems.map((item: IItemOptions) => item.id)
    : selectedItems[0]?.id

  emit('update:model-value', model)
}

function remove (itemOption: IItemOptions) {
  itemOption.deselect()
  updateModel()
  selectedEntities.value = getSelector().getSelectedItems()
}

function showSelector (trigger?: 'field') {
  const clickedOnField = trigger === 'field'

  if (props.readonly || (clickedOnField && !props.showPopupByFieldClick)) {
    return
  }

  setTopPopupOffset()
  getSelector().show()
}

function hideSelector () {
  getSelector().hide()
}

async function initSelector () {
  if (!BX.UI?.EntitySelector?.Dialog) {
    await BX.Runtime.loadExtension('ui.entity-selector')
  }

  BX.Kt.Selector[props.id] = new BX.UI.EntitySelector.Dialog(getSelectorOptions())

  if (isEmpty(props.preselectedItems)) {
    loading.value = false
  }
}

function getSelectorOptions () {
  const hideOnSelect = !props.multiple
  const dialogOptions: IDialogOptions = {
    targetNode: getSelectorTarget(),
    enableSearch: props.enableSearch,
    entities: props.entities,
    multiple: props.multiple,
    preload: props.preload,
    dropdownMode: props.dropdownMode,
    autoHide: props.autoHide,
    hideByEsc: props.hideByEsc,
    hideOnSelect,
    hideOnDeselect: props.hideOnDeselect,
    preselectedItems: props.preselectedItems,
    footer: getFooter(),
    events: {
      onLoad,
      'Item:onSelect': onItemSelect,
      'Item:onDeselect': onItemDeselect,
      'Item:onBeforeSelect': onBeforeSelect,
      onShow,
      onHide
    }
  }

  return dialogOptions
}

function onBeforeSelect (event: any) {
  const cantSelect = event.data.item.customData.get('cantSelect')

  if (cantSelect) {
    if (cantSelect.value === true) {
      event.preventDefault()
      Notify.create({
        type: 'negative',
        message: cantSelect.message,
        position: 'top'
      })
    }
  }
}

function onLoad () {
  if (shouldUpdateModel.value) {
    updateModel()
    shouldUpdateModel.value = false
  }

  loading.value = false
  selectedEntities.value = getSelector().getSelectedItems()

  getSelector().setFooter(getFooter())
}

function onShow () {
  emit('show')
}

function onHide () {
  emit('hide')
}

function onItemSelect (event: any) {
  const { item: selectedItem } = event.getData()

  updateModel()
  selectedEntities.value = getSelector().getSelectedItems()
  emit('select:item', selectedItem)
}

function onItemDeselect () {
  updateModel()
  selectedEntities.value = getSelector().getSelectedItems()
}

function selectItems () {
  getSelector().getPreselectedItems()
    .forEach((preselectedItem: IItemId) => {
      const item = getSelector().getItem(preselectedItem)
      if (item) {
        item.select(true)
      }
    })
}

function deselectItems () {
  getSelector().deselectAll()
}

function setTopPopupOffset () {
  const target = getSelectorTarget()
  const offset = target == null ? (parseFloat(document.body.style.top) * -1) : 0

  getSelector().setOffsetTop(offset)
}

function getSelectorTarget () {
  if (props.targetElement === 'field') {
    return selectorTriggerEl.value as HTMLButtonElement
  }

  return undefined
}

function getFooter () {
  return props.footer === 'actions-button' ? getActionButtonsFooter() : props.footer
}

function getActionButtonsFooter () {
  return BX.create('div', {
    props: {
      className: 'flex justify-end full-width'
    },
    children: [
      getActionCancelButton()
    ]
  })
}

function getActionCancelButton () {
  return BX.create('button', {
    props: {
      type: 'button',
      className: 'q-btn q-btn-item non-selectable no-outline q-btn--outline q-btn--rectangle q-btn--actionable q-focusable q-hoverable kt-btn kt-btn--ghost-theme'
    },
    events: { click: hideSelector },
    children: [
      BX.create('span', {
        props: { className: 'q-focus-helper' }
      }),
      BX.create('span', {
        props: {
          className: 'q-btn__content text-center col items-center q-anchor--skip justify-center row'
        },
        children: [
          BX.create('span', { className: 'block', text: t('common.btn.close') })
        ]
      })
    ]
  })
}

function destroySelector () {
  getSelector().destroy()
}

function getSelector () {
  return BX.Kt.Selector[props.id]
}

function getPopup () {
  return getSelector().getPopup()
}
</script>

<template>
  <q-field v-bind="propsField"
           :rules="rules"
           :loading="loading"
           :clear-icon="clearIcon"
           :class="layoutClasses"
           class="kt-selector"
           ref="selectorRef"
  >
    <template v-for="(slotFn, slotName) in slotsVisible" :key="slotName" #[slotName]>
      <slot :name="slotName"></slot>
    </template>

    <template #control>
      <button type="button"
              tabindex="0"
              ref="selectorTriggerEl"
              class="kt-selector__trigger self-center full-width no-outline"
              @click="showSelector('field')"
      />

      <slot name="selected"
            :selected-entities="selectedEntities"
            :show-selector="showSelector"
      >
        <div class="kt-selector__selected-entities">
          <q-chip v-for="entity in selectedEntities"
                  :key="entity.id"
                  dense
                  clickable
                  removable
                  @remove="remove(entity)"
          >
            <span class="ellipsis">{{ entity.title }}</span>
          </q-chip>

          <kt-btn-link v-if="showActionMoreBtn && !props.readonly"
                       :label="actionBtnText"
                       theme="primary"
                       data-test-selector="action-more-btn"
                       class="kt-selector__action-more-btn"
                       @click="showSelector"
          >
            <template #icon>
              <kt-icon v-if="isEmpty(props.modelValue)" name="add"/>
            </template>
          </kt-btn-link>
        </div>
      </slot>
    </template>

    <template v-if="showDropdownIcon" #append>
      <kt-icon name="arrow-drop-down"
               class="kt-selector__dropdown-btn"
               data-test-selector="dropdown-icon"
               @click="showSelector"
      />
    </template>
  </q-field>
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-selector {
    &__trigger {
      height: 100%;
      padding: 0;
      position: absolute;
      top: 0;
      left: 0;
      border: none;
      background: none;
    }

    &__selected-entities {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      position: relative;
      margin: 0 calc(-1 * var(--kt-ui-dropdown-choice-offset)) calc(-1 * var(--kt-ui-dropdown-choice-offset)) 0;
      padding-top: 4px;
      padding-bottom: 4px;
      z-index: 10;
      font-size: 14px;
      color: var(--kt-ui-field-color);
    }

    .q-chip {
      max-width: 24ch;
      display: inline-flex;
      height: var(--kt-ui-dropdown-choice-height);
      line-height: var(--kt-ui-dropdown-choice-height);
      margin: 0 var(--kt-ui-dropdown-choice-offset) var(--kt-ui-dropdown-choice-offset) 0;
      padding: var(--kt-ui-dropdown-choice-padding);
      border: var(--kt-ui-dropdown-choice-border);
      border-radius: var(--kt-ui-dropdown-choice-border-radius);
      color: var(--kt-ui-dropdown-choice-color);
      background-color: var(--kt-ui-dropdown-choice-bg);
      transition: background-color 160ms linear;
    }

    .q-chip:hover {
      background-color: var(--kt-ui-dropdown-choice-bg-hover);
    }

    .q-chip:active {
      background-color: var(--kt-ui-dropdown-choice-bg-active);
    }

    .q-chip__icon--remove {
      width: var(--kt-ui-dropdown-choice-remove-size);
      border: none;
      padding: 0;
      position: absolute;
      top: unset;
      right: 8px;
      left: unset;
      mask: var(--kt-ui-dropdown-choice-remove-icon) no-repeat 50%;
      color: var(--kt-ui-dropdown-choice-remove-color);
      background-color: var(--kt-ui-dropdown-choice-remove-color);
    }

    &.q-field--labeled .kt-selector__selected-entities {
      margin-top: 7px;
    }

    &__action-more-btn {
      margin: 0 var(--kt-ui-dropdown-choice-offset) var(--kt-ui-dropdown-choice-offset) 0;
    }

    &__dropdown-btn {
      color: var(--kt-ui-select-arrow-color);
    }

    &-actions > .ui-btn {
      font-size: 14px;
    }

    .q-field__marginal {
      color: var(--kt-ui-field-color);
      font-style: normal;
      font-weight: 600;
      font-size: 14px;
    }
  }
}
</style>
