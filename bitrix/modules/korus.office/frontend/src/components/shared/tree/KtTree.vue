<script lang="ts" setup>
import { h, VNode } from 'vue'
import { KtLink, KtIcon } from '@/components/shared'

interface IKtTreeNode {
  label:     string;
  URL:       string;
  children?: IKtTreeNode[]
}

interface IKtTreeProps {
  nodes:            IKtTreeNode[];
  defaultExpandAll: boolean;
}

const props = withDefaults(defineProps<IKtTreeProps>(), {
  defaultExpandAll: false
})

function renderNode (node: IKtTreeNode) {
  const classes = ['kt-tree-node', !node.children ? 'kt-tree-node--child' : 'kt-tree-node--parent']

  return h('div', { class: classes.join(' ') }, [
    renderLabel(node),
    renderChildren(node)
  ])
}

function renderLabel (node: IKtTreeNode) {
  const children: VNode[] = []

  if (node.children) {
    children.push(h(KtIcon, {
      name: 'arrow-drop-down',
      class: 'kt-tree-node__arrow-icon'
    }))
  }

  children.push(h(KtLink, {
    href: node.URL,
    text: node.label,
    theme: node.children ? 'dark' : 'primary',
    class: 'kt-tree-node__link'
  }))

  return h('div', { class: 'kt-tree-node__label' }, children)
}

function renderChildren (node: IKtTreeNode): null | VNode {
  if (!Array.isArray(node.children)) {
    return null
  }

  return h('div', { class: 'kt-tree-node__children' }, node.children.map(renderNode))
}

function renderTree () {
  return h('div', { class: 'kt-tree' }, props.nodes.map(renderNode))
}
</script>

<template>
  <renderTree />
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-tree {
    & > .kt-tree-node > .kt-tree-node__label {
      margin-top: 0;
      font-size: 16px;
      line-height: 1.375;
    }

    &-node__label {
      display: flex;
      align-items: center;
      margin-top: 4px;
      color: var(--kt-ui-gray-20-hover);
    }

    &-node--child &-node__label {
      position: relative;

      &::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
        align-self: flex-start;
        position: relative;
        top: 6.5px;
        left: 0;
        background-color: var(--kt-ui-layer-02-hover);
      }
    }

    &-node__link {
      margin-left: 10px;
    }

    &-node__children {
      padding-left: 20px;
    }
  }
}
</style>
