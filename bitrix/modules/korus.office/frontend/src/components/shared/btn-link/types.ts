export type KtBtnLinkTheme = 'primary' | 'secondary' | 'dark';

export type KtBtnLinkProps = {
  theme:        KtBtnLinkTheme;
  underline:    boolean;
  iconFit:      'auto' | 'cover' | 'contain';
  iconRightFit: 'auto' | 'cover' | 'contain';
};
