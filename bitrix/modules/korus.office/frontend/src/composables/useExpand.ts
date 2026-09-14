import { computed, ref } from 'vue'

interface IExpandOptions {
  defaultValue: boolean;
  expandText:   string;
  expandedText: string;
}

export function useExpand (options: IExpandOptions) {
  const isExpanded = ref(options.defaultValue)
  const toggle = () => {
    isExpanded.value = !isExpanded.value
  }
  const expandStateText = computed(
    () => !isExpanded.value ? options.expandText : options.expandedText
  )

  return {
    isExpanded,
    toggle,
    expandStateText
  }
}
