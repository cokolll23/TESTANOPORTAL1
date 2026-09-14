import { ref, computed } from 'vue'
import { useGratitudesStore, gratitudeDataInitial } from '@/stores/gratitudes'

export function useGratitudePosts () {
  const gratitudeStore = useGratitudesStore()

  const gratitudePostsLoading = ref(false)
  const gratitudePosts = computed(() => {
    const POSTS = gratitudeStore.POSTS ?? Object.create(null)

    return Object.keys(POSTS).map(ID => {
      const POST = POSTS[ID]
      const SENDER = gratitudeStore.USERS[POST.AUTHOR_ID]

      let data = null
      let index = -1

      while (++index < gratitudeStore.BADGES.length) {
        const badge = gratitudeStore.BADGES[index]
        const postBadgeId = Number(POST.BADGE_ID)
        const badgeId = Number(badge.ID)

        if (postBadgeId === badgeId) {
          data = gratitudeDataInitial.find(item => item.CODE === badge.CODE)
          break
        }
      }

      if (typeof data === 'undefined') {
        return Object.assign({ SENDER }, POST)
      }

      return Object.assign({ SENDER }, POST, data)
    })
  })

  const canLoadMorePosts = computed(
    () => gratitudeStore.NAV.pageSize * gratitudeStore.NAV.currentPage < gratitudeStore.NAV.recordCount
  )

  const canLoadPreviousPosts = computed(
    () => gratitudeStore.NAV.currentPage > 1
  )

  const loadMorePosts = async () => {
    try {
      gratitudePostsLoading.value = true
      await gratitudeStore.loadMorePosts()
    }
    finally {
      gratitudePostsLoading.value = false
    }
  }

  const loadPreviousPosts = async () => {
    try {
      gratitudePostsLoading.value = true
      await gratitudeStore.loadPreviousPosts()
    }
    finally {
      gratitudePostsLoading.value = false
    }
  }

  const openGratitudePostPage = (path: string) => {
    const BX = window.BX
    const options = {
      width: 1000
    }

    BX.SidePanel.Instance.open(path, options)
  }

  return {
    gratitudePosts,
    canLoadMorePosts,
    canLoadPreviousPosts,
    gratitudePostsLoading,

    openGratitudePostPage,
    loadMorePosts,
    loadPreviousPosts
  }
}
