<template>
  <renderTree />
</template>

<script lang="ts" setup>
import { h, VNode } from 'vue'
import { QIcon } from 'quasar'

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

const renderLabel = (node: IKtTreeNode): VNode => {
  const children: VNode[] = []

  if (node.children) {
    children.push(h(QIcon, {
      name: 'kt:tree-arrow-down',
      color: 'app-grey-13',
      size: '10px',
      class: 'kt-tree-node__arrow-icon'
    }))
  }

  children.push(h('a', {
    class: 'kt-tree-node__link',
    href: node.URL
  }, node.label))

  return h('div', { class: 'kt-tree-node__label' }, children)
}
const renderChildren = (node: IKtTreeNode): VNode | null => {
  if (!Array.isArray(node.children)) {
    return null
  }

  return h('div', { class: 'kt-tree-node__children' }, node.children.map(renderNode))
}
const renderNode = (node: IKtTreeNode): VNode => {
  const classes = ['kt-tree-node', !node.children ? 'kt-tree-node--child' : 'kt-tree-node--parent']

  return h('div', { class: classes.join(' ') }, [
    renderLabel(node),
    renderChildren(node)
  ])
}
const renderTree = () => {
  return h('div', { class: 'kt-tree' }, props.nodes.map(renderNode))
}
</script>

<style lang="scss">
.pgk {
  .kt-tree {
    & > .kt-tree-node > .kt-tree-node__label {
      $margin-top: 0;
      $font-weight: 600;
      $color: var(--q-app-grey-4);

      margin-top: $margin-top;
      font-weight: $font-weight;
      color: $color;
    }

    &-node__label {
      $margin-top: 5px;

      display: flex;
      align-items: center;
      margin-top: $margin-top;
    }

    &-node--child &-node__label {
      $color: var(--q-secondary);

      position: relative;
      color: $color;

      &::before {
        $width: 7px;
        $height: 7px;
        $top: 6.5px;
        $left: 0;
        $background-color: var(--q-app-grey-13);

        content: '';
        width: $width;
        height: $height;
        border-radius: 50%;
        flex-shrink: 0;
        align-self: flex-start;
        position: relative;
        top: $top;
        left: $left;
        background-color: $background-color;
      }
    }

    &-node__arrow-icon {
      $transform: rotate(90deg);

      transform: $transform;
    }

    &-node__link {
      $color: var(--q-app-grey-3);
      $transition: color 0.3s ease-out;
      $margin-left: 10px;

      margin-left: $margin-left;
      color: $color;
      cursor: pointer;
      transition: $transition;
    }

    &-node--child &-node__link {
      $color: var(--ui-link-primary-color);

      color: $color;
      text-decoration: none;
    }

    &-node__link:hover,
    &-node--child &-node__link:hover {
      $color: var(--ui-link-secondary-color);

      color: $color;
    }

    &-node__children {
      $padding-left: 20px;

      padding-left: $padding-left;
    }
  }
}
</style>
