export type IItemId = [
  string,
    string | number
];

export type IFooterContent = string | HTMLElement | HTMLElement[] | ((...args: any[]) => any);

export type IItemOptions = {
  id:            number;
  entityId:      string;
  entityType?:   string;
  title?:        string;
  avatar?:       string;
  textColor?:    string;
  link?:         string;
  tagOptions?:   { [key: string]: any };
  tabs?:         string[];
  searchable?:   boolean;
  saveable?:     boolean;
  deselectable?: boolean;
  selected?:     boolean;
  hidden?:       boolean;
  customData?:   { [key: string]: any };
  sort?:         number;
};

export type ITabLabelStates = {
  default?:         string;
  selected?:        string;
  hovered?:         string;
  selectedHovered?: string;
};

export type ITextNodeType = 'text' | 'html';

export type TextNodeOptions = {
  text:  string;
  type?: ITextNodeType;
};

export type IItemNodeOrder = any;

export type ITabOptions = {
  id:                 string;
  title?:             string | TextNodeOptions;
  visible?:           boolean;
  itemMaxDepth?:      number;
  itemOrder?:         IItemNodeOrder;
  icon?:              ITabLabelStates | string;
  textColor?:         ITabLabelStates | string;
  bgColor?:           ITabLabelStates | string;
  stub?:              boolean | string | ((...args: any[]) => any);
  stubOptions?:       { [option: string]: any };
  footer?:            IFooterContent;
  footerOptions?:     { [option: string]: any };
  showDefaultFooter?: boolean;
  showAvatars?:       boolean;
};

export type IEntityOptions = {
  id:                 string;
  options?:           { [key: string]: any };
  itemOptions?:       { [key: string]: any };
  tagOptions?:        { [key: string]: any };
  searchable?:        boolean;
  searchCacheLimits?: [];
  dynamicLoad?:       boolean;
  dynamicSearch?:     boolean;
};

export type IPopupOptions = {
  overlay: any;
  bindOptions: any;
  targetContainer: any;
  zIndexOptions: any;
};

export type ISearchOptions = {
  allowCreateItem?: boolean;
  footerOptions?:   { [option: string]: any };
};

export type IDialogOptions = {
  targetNode?:            HTMLElement;
  id?:                    string;
  context?:               string;
  items?:                 IItemOptions[];
  selectedItems?:         IItemOptions[];
  preselectedItems?:      IItemId[];
  undeselectedItems?:     IItemId[];
  tabs?:                  ITabOptions[];
  entities?:              IEntityOptions[];
  popupOptions?:          IPopupOptions;
  multiple?:              boolean;
  preload?:               boolean;
  dropdownMode?:          boolean;
  enableSearch?:          boolean;
  searchOptions?:         ISearchOptions,
  searchTabOptions?:      ITabOptions,
  recentTabOptions?:      ITabOptions,
  tagSelectorOptions?:    any;
  events?:                { [eventName: string]: (event: any) => void };
  hideOnSelect?:          boolean;
  hideOnDeselect?:        boolean;
  clearSearchOnSelect?:   boolean;
  width?:                 number;
  height?:                number;
  autoHide?:              boolean;
  hideByEsc?:             boolean;
  offsetTop?:             number;
  offsetLeft?:            number;
  cacheable?:             boolean;
  focusOnFirst?:          boolean;
  footer?:                IFooterContent;
  footerOptions?:         { [option: string]: any };
  showAvatars?:           boolean;
  compactView?:           boolean;
  clearUnavailableItems?: boolean;
};

export type ITargetElement =
  | 'field'
  | {
  top:  number;
  left: number;
}
  | null;
