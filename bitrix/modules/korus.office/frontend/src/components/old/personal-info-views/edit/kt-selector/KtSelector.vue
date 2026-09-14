<template>
  <kt-widget-layout-row :label="props.label">
    <q-field :model-value="props.modelValue"
             :outlined="props.outlined"
             :rules="props.rules"
             :lazy-rules="props.lazyRules"
             :loading="loading"
             :readonly="props.readonly"
             :dense="props.dense"
             class="kt-selector"
    >
      <template v-slot:control>
        <button class="kt-selector__trigger self-center full-width no-outline"
                tabindex="0"
                type="button"
                ref="selectorTriggerEl"
                @click="showSelector"
        />

        <div @click="showSelector">
          <div v-for="entity in selectedEntities" :key="entity.id">
            {{ props.modelValue ? entity.title : 'Не указано' }}
          </div>
        </div>
      </template>

      <template v-slot:append v-if="props.showIcon">
        <q-icon name="expand_more" color="cursor-pointer" @click="showSelector" />
      </template>
    </q-field>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { ref, Ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { isEmpty, isEmptyPreselectedItems } from '@/utils'
import { Notify } from 'quasar'
import {
  IDialogOptions,
  IEntityOptions,
  IItemId,
  IItemOptions,
  ITargetElement
} from './types'

interface IProps {
  label:             string;
  outlined:          boolean;
  modelValue:        any;
  entities:          IEntityOptions[];
  id?:               string;
  loading?:          boolean;
  readonly?:         boolean;
  showFooter?:       boolean;
  rules?:            any[];
  lazyRules?:        boolean;
  preselectedItems?: IItemId[];
  preload?:          boolean;
  multiple?:         boolean;
  dropdownMode?:     boolean;
  enableSearch?:     boolean;
  autoHide?:         boolean;
  hideByEsc?:        boolean;
  hideOnSelect?:     boolean;
  hideOnDeselect?:   boolean;
  targetElement?:    ITargetElement;
  showIcon?: boolean
  dense?:            boolean;
}

const props = withDefaults(defineProps<IProps>(), {
  outlined: true,
  dropdownMode: false,
  enableSearch: true,
  autoHide: true,
  multiple: false,
  hideByEsc: false,
  hideOnSelect: true,
  hideOnDeselect: false,
  showFooter: false,
  targetElement: 'field',
  showIcon: true,
  dense: false
})
const emit = defineEmits(['update:modelValue', 'select:item', 'show', 'hide', 'onDeselect:item'])

const BX = window.BX

const loading = ref(true)
const shouldUpdateModel = ref(false)
const selectedEntities: Ref<IItemOptions[]> = ref([])
const selectorTriggerEl: Ref<null | HTMLElement> = ref(null)

let selector: any = null

onMounted(initSelector)
onBeforeUnmount(destroySelector)

watch(() => props.preselectedItems, onChangePreselectedItems)
watch(() => props.loading, onChangeLoading)
watch(() => props.targetElement, onChangeTargetElement)

function onChangePreselectedItems () {
  if (selector.loadState === 'DONE') {
    deselectItems()
  }

  selector.setPreselectedItems(props.preselectedItems)

  if (selector.loadState === 'UNSENT') {
    loading.value = true
    shouldUpdateModel.value = true
    selector.load()
  }
  else if (selector.loadState === 'DONE') {
    selectItems()
    updateModel()
    selectedEntities.value = selector.getSelectedItems()
  }
}

function onChangeLoading () {
  loading.value = !!props.loading
}

function onChangeTargetElement () {
  const popup = selector.getPopup()
  const target = getSelectorTarget()

  popup.setBindElement(target)
}

function updateModel () {
  const selectedItems = selector.getSelectedItems()
  const model = props.multiple
    ? selectedItems.map((item: any) => item.id)
    : selectedItems[0]?.id

  emit('update:modelValue', model)
}

function showSelector () {
  if (props.readonly) {
    return
  }

  setTopPopupOffset()

  selector.show()
}

function initSelector () {
  selector = new BX.UI.EntitySelector.Dialog(getSelectorOptions())

  if (isEmptyPreselectedItems(props.preselectedItems)) {
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
    events: {
      onLoad,
      'Item:onSelect': onItemSelect,
      'Item:onDeselect': onItemDeselect,
      'Item:onBeforeSelect': onBeforeSelect,
      onShow,
      onHide
    }
  }

  if (!props.showFooter) {
    dialogOptions.footer = undefined
  }

  return dialogOptions
}

function onBeforeSelect (event: any) {
  const cantSelect = event.data.item.customData.get("cantSelect")
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
  selectedEntities.value = selector.getSelectedItems()

  if (!props.showFooter) {
    selector.setFooter(null)
  }
}

function onItemSelect (event: any) {
  const { item: selectedItem } = event.getData()

  updateModel()
  selectedEntities.value = selector.getSelectedItems()
  emit('select:item', selectedItem)
}

function selectItems () {
  selector.getPreselectedItems().forEach((preselectedItem: IItemId) => {
    const item = selector.getItem(preselectedItem)
    if (item) {
      item.select(true)
    }
  })
}

function onItemDeselect () {
  updateModel()
  selectedEntities.value = selector.getSelectedItems()
  emit('onDeselect:item')
}

function onShow () {
  emit('show')
}

function onHide () {
  emit('hide')
}

function deselectItems () {
  selector.deselectAll()
}

function setTopPopupOffset () {
  const target = getSelectorTarget()
  const offset = target == null ? (parseFloat(document.body.style.top) * -1) : 0

  selector.setOffsetTop(offset)
}

function getSelectorTarget () {
  if (props.targetElement === 'field') {
    return selectorTriggerEl.value as HTMLButtonElement
  }

  return undefined
}

function destroySelector () {
  selector.destroy()
}
</script>

<style lang="scss">
.pgk {
  .kt-selector {
    &__trigger {
      $height: 100%;
      $top: 0;
      $left: 0;
      $padding: 0;
      $border: none;
      $background: none;

      height: $height;
      padding: $padding;
      position: absolute;
      top: $top;
      left: $left;
      border: $border;
      background: $background;
    }

    &-actions > .ui-btn {
      $font-size: 14px;

      font-size: $font-size;
    }
  }

  .ui-selector-tab-label-active,
  .ui-selector-tab-label-active.ui-selector-tab-label-hover {
    $background-color: $secondary;

    background-color: $background-color;
  }

  .ui-selector-item-box-selected {
    &.ui-selector-item-box-focused {
      & > .ui-selector-item,
      .ui-selector-item-link {
        $background-color: rgba($secondary, 0.2);

        background-color: $background-color;
      }
    }

    & > .ui-selector-item {
      $background-color: rgba($secondary, 0.2);

      background-color: $background-color;

      & > .ui-selector-item-indicator {
        $background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg width='15' height='12' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M5.14 11.461L.012 6.464l1.795-1.749L5.14 7.963 12.68.613l1.795 1.75L5.14 11.46z' fill='%23F9423A'/%3E%3C/svg%3E");

        background-image: $background-image;
      }
    }
  }

  .ui-selector-footer-default {
    $background-color: rgba($secondary, 0.1);

    background-color: $background-color;
  }

  .ui-selector-footer .ui-selector-footer-link-add:before {
    $background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg width='24' height='24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M12 24c6.627 0 12-5.373 12-12S18.627 0 12 0 0 5.373 0 12s5.373 12 12 12z' fill='%23F9423A'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7z' fill='%23fff'/%3E%3C/svg%3E");

    background-image: $background-image;
  }
}
</style>
