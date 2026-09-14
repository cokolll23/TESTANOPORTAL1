<script lang="ts" setup>
import { ref, computed, watch, defineSlots, h, useAttrs } from 'vue'
import { useQuasar } from 'quasar'
import { KtMenu, KtDialog } from '@/components/shared'

interface IProps {
  target:         boolean | string | Element;
  breakpoint:     string | number;
  noParentEvent?: boolean;
  contextMenu?:   boolean;
}

interface IEmits {
  (e: 'show', event?: Event): void;
  (e: 'hide', event?: Event): void;
}

const $q = useQuasar()
const props = withDefaults(defineProps<IProps>(), {
  target: true,
  breakpoint: 450
})
const emit = defineEmits<IEmits>()
const slots = defineSlots()

const breakpoint = computed(() => {
  if (typeof props.breakpoint === 'string') {
    return parseInt(props.breakpoint, 10)
  }

  return props.breakpoint
})

const showing = ref(false)
const type = ref<'dialog' | 'menu'>(getType())

const KtPopupProxyRef = ref<null | InstanceType<typeof KtMenu> | InstanceType<typeof KtDialog>>(null)

const popupProps = computed(() => {
  return type.value === 'menu' ? { maxHeight: '99vh' } : {}
})

watch(() => getType(), val => {
  if (showing.value !== true) {
    type.value = val
  }
})

function getType () {
  return $q.screen.width < breakpoint.value || $q.screen.height < breakpoint.value
    ? 'dialog'
    : 'menu'
}

function render () {
  const attrs = useAttrs()
  const data = {
    ref: KtPopupProxyRef,
    ...popupProps.value,
    ...attrs,
    onShow,
    onHide
  }

  let component

  if (type.value === 'dialog') {
    component = KtDialog
  }
  else {
    component = KtMenu
    Object.assign(data, {
      target: props.target,
      contextMenu: props.contextMenu,
      noParentEvent: true,
      separateClosePopup: true
    })
  }

  return h(component, {
    ...data,
    ...attrs,
    ref: KtPopupProxyRef,
    class: 'kt-popup-proxy'
  }, slots.default)
}

function onShow (event?: Event) {
  showing.value = true
  emit('show', event)
}

function onHide (event?: Event) {
  showing.value = false
  type.value = getType()
  emit('hide', event)
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-popup-proxy {
}
</style>
