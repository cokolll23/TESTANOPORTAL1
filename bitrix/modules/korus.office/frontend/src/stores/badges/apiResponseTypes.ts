export type IBadge = {
  id:          number;
  name:        string;
  description: string;
  image:       string;
  date:        string;
  color:       string;
}

export type IBadgesApiResponse = IBadge[];
