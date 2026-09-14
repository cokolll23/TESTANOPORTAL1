export type KtBtnTheme =
  | 'primary'
  | 'secondary'
  | 'tertiary'
  | 'ghost';

export type KtBtnProps = {
  theme:         KtBtnTheme;
  iconFit:      'auto' | 'cover' | 'contain';
  iconRightFit: 'auto' | 'cover' | 'contain';
};
