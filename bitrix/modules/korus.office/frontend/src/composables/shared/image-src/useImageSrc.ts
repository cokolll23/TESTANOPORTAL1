import { defaultUserAvatar } from '@/assets'

export function useImageSrc (src?: string | null) {
  if (src == null) {
    return {
      src: defaultUserAvatar,
      style: {
        backgroundColor: 'var(--kt-ui-icon-common-user-bg-color, #f4f4f4)'
      },
      imgStyle: {
        width: '20px',
        height: '20px',
        borderRadius: 0
      },
      imgClass: 'absolute-center'
    }
  }

  return {
    src
  }
}
