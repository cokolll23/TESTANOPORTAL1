<script lang="ts" setup>
import { computed, nextTick, ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useAboutMeStore } from '@/stores/about-me'
import { usePermissionsStore } from '@/stores/permissions'
import { processComponent } from '@/utils'
import type { IComponentContent } from '@/stores/types'

import { KtBtn } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle, KtWidgetStub } from '@/components/lk'

import stubImage from '@/assets/widgets/about-me/stub.png'

type AboutMode = 'read' | 'edit' | 'stub'

const store = useAboutMeStore()
const { content, editor, editorId } = storeToRefs(store)
const { CAN_EDIT } = storeToRefs(usePermissionsStore())

const editorHandlerId = computed(() => `id${editorId.value}`)
const modeNext = computed(() => content.value?.html ? 'read' : 'stub')
const postText = computed(() => content.value?.additionalParams.POST.DETAIL_TEXT || '')
const postFiles = computed((): IComponentContent | null => {
  const _content = content.value
  const _uf = _content?.additionalParams.POST.UF_RENDERED

  return _uf ? {
    html: _uf.CONTENT,
    assets: {
      js: _uf.JS,
      css: _uf.CSS,
      string: []
    }
  } : null
})
const postFilesField = computed(() => {
  const _content = content.value
  const _uf = _content?.additionalParams.POST.UF

  if (_uf?.UF_BLOG_POST_FILE) {
    return {
      UF_BLOG_POST_FILE: {
        USER_TYPE_ID: 'disk_file',
        FIELD_NAME: 'UF_BLOG_POST_FILE[]',
        VALUE: _uf.UF_BLOG_POST_FILE.VALUE ? [..._uf.UF_BLOG_POST_FILE.VALUE] : []
      }
    }
  }

  return {}
})

const mode = ref(modeNext.value)
const isReadMode = computed(() => mode.value === 'read')
const isEditMode = computed(() => mode.value === 'edit')
const isStubMode = computed(() => mode.value === 'stub')

const contentElement = ref()
const editorElement = ref()
const editorLoading = ref(true)
const editorRendered = ref(false)

function changeMode (nextMode: AboutMode) {
  mode.value = nextMode
}

function getLHE () {
  return window.LHEPostForm || null
}

function getEditor () {
  return getLHE()?.getEditor(editorHandlerId.value) || null
}

function getEditorHandler () {
  return getLHE()?.getHandler(editorHandlerId.value) || null
}

async function showEditor () {
  editorLoading.value = true

  changeMode('edit')
  await store.loadEditor()

  if (editor.value) {
    if (editorRendered.value) {
      reInitEditor()
    } else {
      processComponent(editor.value, editorElement.value)
        .then(async () => {
          editorRendered.value = true

          await nextTick()

          const BX = window.BX
          const form = getEditorHandler()

          if (form) {
            BX.onCustomEvent(form.eventNode, 'OnShowLHE', [true])
            reInitEditor()
          }
        })
    }
  }
}

async function save () {
  const editor: any = getEditor()

  if (editor === null) {
    return false
  }

  try {
    if (editorElement.value) {
      const BX = window.BX
      const formData = new FormData()
      const data = BX.ajax.prepareForm(editorElement.value)

      formData.append('data[text]', editor.GetContent())

      for (const key in data.data) {
        if (key) {
          const name = `data[additionalData][${key}]`
          const val = data.data[key]
          if (Array.isArray(val)) {
            val.forEach(v => {
              formData.append(`${name}[]`, String(v))
            })
          } else {
            formData.append(name, val)
          }
        }
      }

      await store.send(formData)

      changeMode(modeNext.value)
    }
  } catch (error) {
  }
}

function cancel () {
  changeMode(modeNext.value)
}

function reInitEditor () {
  getLHE()?.reinitData(editorHandlerId.value, postText.value, postFilesField.value)
  editorLoading.value = false
}

watch(
  [content, contentElement],
  async () => {
    if (contentElement.value) {
      if (content.value) {
        await processComponent(content.value, contentElement.value)
      }

      if (postFiles.value) {
        await processComponent(postFiles.value, contentElement.value, false)
      }
    }
  },
  {
    immediate: true,
    deep: true
  }
)
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-about-me">
      <header class="kt-about-me__header">
        <kt-widget-title :text="$t('aboutMe.title')" />

        <kt-btn v-if="isReadMode && CAN_EDIT"
                theme="tertiary"
                round
                dense
                @click="showEditor"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" fill="none"><path fill="currentColor" d="M16.335 3.834a2.275 2.275 0 0 0-3.214 0L2.92 14.037A3.116 3.116 0 0 0 2 16.254v1.288a.627.627 0 0 0 .627.627h1.288a3.115 3.115 0 0 0 2.218-.918L16.335 7.048a2.275 2.275 0 0 0 0-3.214Zm-11.09 12.53c-.353.351-.831.55-1.33.55h-.66v-.66a1.87 1.87 0 0 1 .551-1.33l7.743-7.743 1.442 1.443-7.745 7.74ZM15.449 6.161l-1.572 1.573-1.443-1.44 1.573-1.573a1.019 1.019 0 1 1 1.44 1.443l.002-.003Z"/></svg>
        </kt-btn>
      </header>

      <div class="kt-about-me__content">
        <div v-if="isStubMode" class="kt-about-me__stub-wr">
          <kt-widget-stub :icon="stubImage"
                          :text="$t('aboutMe.description')"
          />
          <kt-btn theme="primary" :label="$t('aboutMe.btn')" @click="showEditor" />
        </div>

        <div v-show="isReadMode"
             ref="contentElement"
             class="kt-about-me__content"
        />

        <div v-show="isEditMode" class="kt-about-me__editor-wrapper">
          <q-spinner v-if="editorLoading"/>
          <form v-once
                ref="editorElement"
                class="kt-about-me__editor"
          />

          <div class="d-flex justify-center items-center q-mt-md">
            <kt-btn theme="primary" :label="$t('aboutMe.btnSave')" @click="save" />
            <kt-btn theme="ghost" :label="$t('aboutMe.btnCancel')" @click="cancel" />
          </div>
        </div>
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-about-me {
  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-md);
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__editor-wrapper {
    width: 100%;
  }

  &__editor {
    padding-bottom: 19px;
    resize: none;
  }

  &__stub-wr {
    display: flex;
    flex-direction: column;
    gap: var(--kt-ui-offset-lg);

    .kt-btn {
      align-self: center;
    }
  }
}
</style>
