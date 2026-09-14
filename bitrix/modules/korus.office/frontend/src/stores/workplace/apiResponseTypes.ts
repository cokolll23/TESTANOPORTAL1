export interface IWorkplace {
  URL:    string;
  TITLE:  string;
}

export interface IBooking {
  URL:    string;
  TITLE:  string;
  DATE:   string;
}

export interface IUserWorkplace{
  WORKPLACE: IWorkplace,
  BOOKING:   IBooking[]
}

export type IWorkplaceResponse = IUserWorkplace;
