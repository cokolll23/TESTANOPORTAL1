export interface IApiResponse<T = null> {
  status: 'success' | 'error';
  data: T;
  errors: {
    code:    number;
    message: string;
  }[];
}

export interface INav {
  currentPage: number;
  pageSize:    number;
  recordCount: number;
}

export interface IUfMessenger {
  value:     string;
  messenger: string;
}

export type IAssets = {
  css:    string[];
  js:     string[];
  string: string[]
}

export type IComponentContent = {
  html:              string;
  assets:            IAssets;
  additionalParams?: any;
}
