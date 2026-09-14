export type IAccount = {
  BALANCE: number;
}

export type IShopUrls = {
  SHOP: string;
}

export type IShopResponse = {
  URLS:    IShopUrls;
  ACCOUNT: IAccount;
}
