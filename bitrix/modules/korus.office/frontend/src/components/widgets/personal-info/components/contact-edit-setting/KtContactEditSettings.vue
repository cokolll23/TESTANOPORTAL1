<script lang="ts" setup>
import { ref } from 'vue'
import { KtDialog, KtCheckbox, KtBtn } from '@/components/shared'

interface IProps {
  modelValue:  boolean;
  disableSort: boolean;
}

interface IEmits {
  (e: 'update:model-value', state: boolean): void;
  (e: 'submit', payload: {
    disableSort:   boolean;
    updateDefault: boolean;
  }): void;
}

const props = defineProps<IProps>()
const emit = defineEmits<IEmits>()

const disableSort = ref(props.disableSort)
const updateDefault = ref(false)

function submit () {
  emit('submit', {
    disableSort: disableSort.value,
    updateDefault: updateDefault.value
  })

  close()
}

function close () {
  updateModel(false)
}

function updateModel (state: boolean) {
  emit('update:model-value', state)
}
</script>

<template>
  <kt-dialog :model-value="props.modelValue"
             :title-text="$t('contactSettings.title')"
             ref="dialogRef"
             class="kt-contact-settings-popup"
             @update:model-value="updateModel"
  >
    <p class="kt-contact-settings-popup__text">{{ $t('contactSettings.text') }}</p>

    <div class="kt-contact-settings-popup__settings">
      <kt-checkbox v-model="disableSort" :label="$t('contactSettings.disableSort')" />
      <kt-checkbox v-model="updateDefault" :label="$t('contactSettings.updateDefault')" />
    </div>

    <footer class="kt-contact-settings-popup__actions">
      <kt-btn theme="ghost" :label="$t('common.btn.cancel')" @click="close" />
      <kt-btn theme="primary" :label="$t('common.btn.save')" @click="submit" />
    </footer>
  </kt-dialog>
</template>

<style lang="scss">
.kt-contact-settings-popup {
  &__settings {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: var(--kt-ui-offset-xl);
    margin-top: var(--kt-ui-offset-lg);
  }

  &__actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: var(--kt-ui-offset-md);
    margin-top: var(--kt-ui-offset-lg);
  }
}
</style>
