<script lang="ts" setup>
import { ref, computed, watch, defineModel, defineSlots, type VNode, type Ref } from 'vue'
import { useQuasar, uid } from 'quasar'
import { isEmpty } from '@/utils'

import { KtTitle, KtInput, KtTag, KtMenu, KtBtnLink } from '@/components/shared'

interface IProps {
  tags:            string[];
  hintPopoverTags: string[];
  hintPopoverText?:  string;
  title?:          string;
}

interface IEmits {
  (e: 'submit', tag: string): void;
  (e: 'tag:search', tag: string): void;
  (e: 'tag:remove', tag: string): void;
}

interface ISlots {
  tag: (scope: {
    tag:      string;
    selected: Ref<Record<string, boolean>>;
    remove:   (tag: string) => void;
  }) => VNode;
  popoverTag: (scope: {
    tag: string;
    addPrepared: (preparedTag?: string) => void;
  }) => VNode;
  preparedTag: (scope: {
    tag: string;
    selected: Ref<Record<string, boolean>>;
    removePrepared: (preparedTag?: string) => void;
  }) => VNode;
}

const $q = useQuasar()

const props = defineProps<IProps>()
const emit = defineEmits<IEmits>()
const slots = defineSlots<ISlots>()

const model = defineModel<string[]>({ required: true })
const selected = ref<Record<string, boolean>>(Object.create(null))

const newTagModel = ref('')
const isInit = ref(false)
const inputSelectorId = uid()

const isHintPopoverVisible = ref(false)
const hintPopoverMaxWidth = computed(() => {
  return $q.screen.width < 768 ? '80vw' : '480px'
})

const hasSelected = computed(() => {
  return props.tags.length > 0 || model.value.length > 0
})
const hasPopoverTags = computed(() => {
  return props.hintPopoverTags.length > 0
})

watch([
  model,
  () => props.tags
], setSelected, { immediate: true, deep: true })

watch(() => props.hintPopoverTags, () => {
  isHintPopoverVisible.value = isInit.value && (isEmpty(newTagModel.value) || hasPopoverTags.value)
}, { deep: true })

watch(newTagModel, search)

function setSelected () {
  selected.value = [...props.tags, ...model.value].reduce((acc, tag) => {
    acc[tag] = props.tags.includes(tag) || model.value.includes(tag)
    return acc
  }, Object.create(null))
}

function add () {
  emit('submit', newTagModel.value)
  newTagModel.value = ''
}

function remove (tag: string) {
  emit('tag:remove', tag)
}

function search () {
  emit('tag:search', newTagModel.value)
}

function addPrepared (preparedTag?: string) {
  if (typeof preparedTag !== 'undefined') {
    preparedTag = preparedTag.toLowerCase()

    if (!model.value.includes(preparedTag)) {
      model.value.push(preparedTag)
      newTagModel.value = ''
      isInit.value = false
      isHintPopoverVisible.value = false
    }
    return
  }

  if (newTagModel.value === '') {
    return
  }

  model.value.push(newTagModel.value.toLowerCase())
  newTagModel.value = ''
}

function removePrepared (preparedTag?: string) {
  if (newTagModel.value === '' && typeof preparedTag === 'undefined') {
    preparedTag = model.value[model.value.length - 1]
  }

  model.value = model.value.filter(_ => _ !== preparedTag)
}

function onInputFocusHandler () {
  isInit.value = true
  isHintPopoverVisible.value = isEmpty(newTagModel.value) || hasPopoverTags.value
}
</script>

<template>
  <div class="kt-tag-select">
    <kt-input v-model.trim="newTagModel"
              :placeholder="$t('tagSelect.placeholder')"
              :data-input-id="inputSelectorId"
              prefix="#"
              @focus="onInputFocusHandler"
              @keydown.enter="add"
              @keydown.space.prevent="addPrepared()"
              @keydown.delete="removePrepared()"
    >
      <template #append>
        <kt-btn-link icon="arrow-enter" class="kt-tag-select__enter-btn" @click="add" />
      </template>
    </kt-input>

    <kt-menu v-model="isHintPopoverVisible"
             :offset="[0, 4]"
             :max-width="hintPopoverMaxWidth"
             :target="`[data-input-id='${inputSelectorId}']`"
             no-focus
             no-refocus
             no-parent-event
             class="kt-tag-select-popover"
    >
      <header v-if="props.title" class="kt-tag-select-popover__header">
        <kt-title :level="3" :text="props.title" class="kt-tag-select-popover__title" />
      </header>

      <div v-if="hasPopoverTags" class="kt-tag-select-popover__tags">
        <template v-for="popoverTag in props.hintPopoverTags" :key="popoverTag">
          <slot name="popover-tag" :tag="popoverTag" :addPrepared="addPrepared">
            <kt-tag :text="popoverTag"
                    theme="purple"
                    outline
                    clickable
                    @click="addPrepared(popoverTag)"
            />
          </slot>
        </template>
      </div>

      <p v-if="props.hintPopoverText" class="kt-tag-select-popover__text">
        {{ props.hintPopoverText }}
      </p>
    </kt-menu>

    <div v-if="hasSelected" class="kt-tag-select__selected-tags">
      <template v-for="tag in props.tags" :key="tag">
        <slot name="tag" :tag="tag" :selected="selected" :remove="remove">
          <kt-tag :selected="selected[tag]"
                  :text="tag"
                  outline
                  removable
                  @remove="remove(tag)"
          />
        </slot>
      </template>

      <template v-for="tag in model" :key="tag">
        <slot name="prepared-tag" :tag="tag" :selected="selected" :removePrepared="removePrepared">
          <kt-tag :selected="selected[tag]"
                  :text="tag"
                  theme="purple"
                  outline
                  removable
                  @remove="removePrepared(tag)"
          />
        </slot>
      </template>
    </div>
  </div>
</template>

<style lang="scss">
.kt-tag-select {
  .q-field__control {
    height: var(--kt-ui-field-size-md);
  }

  .q-field__control-container {
    flex-wrap: wrap;
    align-items: center;
  }

  .q-field__native {
    width: auto;
    flex: 1;
    order: 2;
    padding-left: 4px;
  }

  .q-field__prefix {
    color: var(--kt-ui-text-placeholder);
  }

  &-popover {
    display: flex;
    flex-direction: column;
    gap: var(--kt-ui-offset-lg);
  }

  &-popover__title {
    --kt-title-font-size: 16px;
    --kt-title-line-height: 1.375;

    font-weight: 700;
  }

  &-popover__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  &-popover__tags .kt-tag {
    margin: 0;
  }

  &-popover__tags .kt-tag.q-chip--clickable:hover {
    color: var(--kt-ui-white);
    border-color: var(--kt-ui-tag-purple-50) !important;
    background-color: var(--kt-ui-tag-purple-50) !important;
  }

  &-popover__tags .kt-tag.q-chip--clickable:active {
    color: var(--kt-ui-white);
    border-color: var(--kt-ui-tag-purple-50-hover) !important;
    background-color: var(--kt-ui-tag-purple-50-hover) !important;
  }

  &-popover__text {
    color: var(--kt-ui-text-secondary);
  }

  &__enter-btn {
    color: var(--kt-ui-icon-secondary);
  }

  &__selected-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
  }

  &__selected-tags .kt-tag {
    margin: 0;
    cursor: default !important;
  }
}
</style>
