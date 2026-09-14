import { defineStore } from 'pinia'
import { IKtTreeNode } from './apiResponseTypes'
import { IPersonOrgStructureState } from './storeTypes'

export const usePersonOrgStructureStore = defineStore('person-org-structure', {
  state: (): IPersonOrgStructureState => ({
    USERS: Object.create(null),
    STRUCTURE: []
  }),

  getters: {
    ownDivisions (state) {
      return state.STRUCTURE.map(item => {
        let nodeName = ''
        item.TREE.forEach(node => {
          while (node.children) {
            node = node.children[0]
          }

          nodeName = node.NAME
        })

        return nodeName
      })
    },

    ownDivisionDepths (state) {
      return state.STRUCTURE.map(item => {
        let depth = 0
        item.TREE.forEach(node => {
          let target = node

          while (target.children) {
            target = target.children[0]
            depth++
          }

          return depth
        })

        return depth
      })
    }
  },

  actions: {
    createTree () {
      this.STRUCTURE.forEach(item => {
        item.headInfo = item.HEAD !== null ? this.USERS[item.HEAD][item.HEAD] : undefined
        item.TREE.forEach(node => {
          const childNodeDepthLevel = parseInt(node.DEPTH_LEVEL) + 1
          const children: IKtTreeNode[] = []
          let index = -1

          while (++index < item.TREE.length) {
            const nodeDepthLevel = parseInt(item.TREE[index].DEPTH_LEVEL)
            if (nodeDepthLevel === childNodeDepthLevel) {
              children.push(item.TREE[index])
            }
          }

          node.label = node.NAME
          if (children.length > 0) {
            node.children = children
          }
        })
        item.TREE.splice(1, item.TREE.length - 1)
      })
    },

    setData (rowData: IPersonOrgStructureState) {
      this.$patch(state => {
        Object.assign(state, rowData)
      })

      this.createTree()
    }
  }
})
