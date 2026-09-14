export interface IServiceButton {
  LABEL:    string;
  ICON:     string;
  URL:      string;
  OPTIONS?: {
    LABEL: string;
    HREF:  string;
  }[]
}

export interface IService {
  TITLE:    string;
  IMAGE:    string;
  DETAILS:  string;
  COLOR:    string;
  BUTTONS?: IServiceButton[];
  TITLE_SUFFIX?: string;
}

export type IServicesResponse = IService[];
