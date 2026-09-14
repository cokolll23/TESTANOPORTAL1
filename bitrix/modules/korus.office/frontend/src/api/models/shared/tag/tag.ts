export type ITagTheme =
  | 'default'
  | 'blue'
  | 'cyan'
  | 'gray'
  | 'green'
  | 'magenta'
  | 'orange'
  | 'purple'
  | 'red'
  | 'teal'
  | 'yellow';


export type ITag = {
  text:  string;
  theme: ITagTheme;
};
