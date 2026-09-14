import { useQuasar } from 'quasar'
import { useLegacy } from '@/composables/legacy'
import {
  like,
  heart,
  compass,
  cup,
  money,
  crown,
  drink,
  cake,
  theFirst,
  star,
  flag,
  beer,
  smile,
  flower,
  cursorHand,
  burger,
  chatDots,
  arrow,
  arrowCircle,
  chevronUp,
  chevronDown,
  palm,
  pulse,
  mobilePhone,
  documentExport,
  medicineBox,
  clipboardBulletList,
  phone,
  treeArrowDown,
  gift,
  starFilled,
  dropdown,
  close,

  editorBold,
  editorItalic,
  editorUnderline,
  editorStrike,
  editorRemoveFormat,
  editorTextColor,
  editorUnorderedList,
  editorOrderedList,
  editorLink,
  editorVideo,
  editorCode,
  editorFontFamily,
  editorFontSize,
  editorQuote,
  editorImage,
  editorTable,
  editorSmiles,
  editorLeftAlign,
  editorFullscreen,

  notebook,
  hobbies,
  competencies,
  remove,
  event,
  menuRound,
  eye,
  eyeSlashed
} from 'app/public/icons/icons'

export function useAppIcons () {
  const icons = {
    'kt:like': like,
    'kt:heart': heart,
    'kt:compass': compass,
    'kt:cup': cup,
    'kt:money': money,
    'kt:crown': crown,
    'kt:drink': drink,
    'kt:cake': cake,
    'kt:thefirst': theFirst,
    'kt:flower': flower,
    'kt:flag': flag,
    'kt:star': star,
    'kt:beer': beer,
    'kt:cursor-hand': cursorHand,
    'kt:smile': smile,
    'kt:burger': burger,
    'kt:chat-dots': chatDots,
    'kt:arrow': arrow,
    'kt:arrow-circle': arrowCircle,
    'kt:chevron-right': chevronUp,
    'kt:chevron-down': chevronDown,
    'kt:palm': palm,
    'kt:pulse': pulse,
    'kt:mobile-phone': mobilePhone,
    'kt:document': documentExport,
    'kt:medicine-box': medicineBox,
    'kt:clipboard-bullet-list': clipboardBulletList,
    'kt:phone': phone,
    'kt:tree-arrow-down': treeArrowDown,
    'kt:gift': gift,
    'kt:star-filled': starFilled,
    'kt:dropdown': dropdown,
    'kt:close': close,

    'kt:editor-bold': editorBold,
    'kt:editor-italic': editorItalic,
    'kt:editor-underline': editorUnderline,
    'kt:editor-strike': editorStrike,
    'kt:editor-remove-format': editorRemoveFormat,
    'kt:editor-text-color': editorTextColor,
    'kt:editor-unordered-list': editorUnorderedList,
    'kt:editor-ordered-list': editorOrderedList,
    'kt:editor-link': editorLink,
    'kt:editor-video': editorVideo,
    'kt:editor-code': editorCode,
    'kt:editor-font-family': editorFontFamily,
    'kt:editor-font-size': editorFontSize,
    'kt:editor-quote': editorQuote,
    'kt:editor-image': editorImage,
    'kt:editor-table': editorTable,
    'kt:editor-smiles': editorSmiles,
    'kt:editor-left-align': editorLeftAlign,
    'kt:editor-fullscreen': editorFullscreen,

    'kt:notebook': notebook,
    'kt:hobbies': hobbies,
    'kt:competencies': competencies,
    'kt:remove': remove,
    'kt:event': event,
    'kt:menu-round': menuRound,
    'kt:eye': eye,
    'kt:eye-slashed': eyeSlashed
  } as const

  type IconType = typeof icons

  const $q = useQuasar()

  $q.iconMapFn = (iconName: string) => {
    if (!useLegacy().isLegacyMode.value) {
      return
    }

    let iconNameNormalized

    if (iconName.startsWith('/bitrix')) {
      return { icon: `img:${iconName}` }
    }
    else if (!iconName.startsWith('kt:')) {
      iconNameNormalized = 'kt:' + iconName
    }
    else {
      iconNameNormalized = iconName
    }

    if (Object.hasOwn(icons, iconNameNormalized)) {
      const icon = icons[iconNameNormalized as keyof IconType]

      return { icon }
    }
  }
}
