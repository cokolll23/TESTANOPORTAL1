export type IUserFullName = {
  full:  string;
  short: string;
};

export type IUserBirthday = {
  value:     string;
  formatted: string;
};

export type IUserEmployment = {
  value:     string;
  formatted: string;
};

export type IUser = {
  id:           number;
  name:         string;
  lastName:     string;
  secondName:   string;
  fullName:     IUserFullName;
  email:        string;
  photo:        string;
  position:     string;
  departmentId: number[];
  isOnline:     boolean;
  birthday:     null | IUserBirthday;
  headId:       null | number;
  employment:   null | IUserEmployment;
  url:          string;
};
