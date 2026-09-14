import { computed } from 'vue'
import { KtBtnProps } from './types'

export function useBtn (props: KtBtnProps) {
  const layoutClasses = computed(() => ([
    `kt-btn--${props.theme}-theme`
  ]))

  return {
    layoutClasses
  }
}
