<template>
  <kt-widget-layout :title="$t('aboutMe.title')" padding-bottom="30px" class="kt-about-me">
    <template v-if="mode === 'read' && CAN_EDIT" v-slot:title-suffix>
      <kt-button theme="text" @click="showEditor">{{ $t('aboutMe.btnChange') }}</kt-button>
    </template>

    <kt-widget-layout-stub v-if="mode === 'stub'"
                           icon="kt:notebook"
                           :text="$t('aboutMe.description')"
                           :btn-text="$t('aboutMe.btn')"
                           @click="showEditor"
    />

    <div v-show="mode === 'read'"
         ref="contentElement"
         class="kt-about-me__content"
    />

    <div v-show="mode === 'edit'" class="kt-about-me__editor-wrapper">
      <q-spinner v-if="editorLoading"/>
      <form v-once
           ref="editorElement"
           class="kt-about-me__editor"
      />

      <div class="d-flex justify-center items-center q-gutter-md">
        <kt-button theme="primary" @click="save">{{ $t('aboutMe.btnSave') }}</kt-button>
        <kt-button theme="text" @click="cancel">{{ $t('aboutMe.btnCancel') }}</kt-button>
      </div>
    </div>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { computed, nextTick, ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useAboutMeStore } from 'stores/about-me'
import { usePermissionsStore } from 'stores/permissions'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtWidgetLayoutStub } from 'components/old/widget-layout-stub'
import { KtButton } from 'components/old/button'

import { processComponent } from '@/utils'
import { IComponentContent } from 'stores/types'

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
const contentElement = ref()
const editorElement = ref()
const editorLoading = ref(true)
const editorRendered = ref(false)

const changeMode = (nextMode: AboutMode) => {
  mode.value = nextMode
}

const getLHE = () => window.LHEPostForm || null

const getEditor = () => getLHE()?.getEditor(editorHandlerId.value) || null

const getEditorHandler = () => getLHE()?.getHandler(editorHandlerId.value) || null

const showEditor = async () => {
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

const save = async () => {
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
const cancel = () => {
  changeMode(modeNext.value)
}

const reInitEditor = () => {
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

<style lang="scss">
.pgk {
  .kt-about-me {
    &__editor-wrapper {
      $width: 100%;

      width: $width;
    }

    &__editor {
      margin-bottom: 16px;
      padding-bottom: 19px;
      resize: none;
    }
  }
}
</style>
