import { IPersonalResponse } from 'stores/personal'
import { IPersonalFieldsResponse } from 'stores/personal-fields'
import { IPermissionsResponse } from 'stores/permissions'
import { IVacationsResponse } from 'stores/current-vacation'
import { IStructureResponse } from 'stores/person-org-structure'
import { IShopResponse } from 'stores/shop'
import { ICompetenciesResponse } from 'stores/competencies'
import { IGratitudesResponse } from 'stores/gratitudes'
import { IServicesResponse } from 'stores/services'
import { IRequestsResponse } from 'stores/requests'
import { IAboutResponse } from 'stores/about-me'
import { IInterestsResponse } from 'stores/interests'
import { IBadgesApiResponse } from 'stores/badges'

export type InitializeResponse = {
  PERSONAL:     IPersonalResponse;
  FIELDS:       IPersonalFieldsResponse;
  PERMISSIONS:  IPermissionsResponse;
  VACATIONS:    IVacationsResponse;
  STRUCTURE:    IStructureResponse;
  SHOP:         IShopResponse;
  COMPETENCIES: ICompetenciesResponse;
  GRATITUDES:   IGratitudesResponse;
  SERVICES:     IServicesResponse;
  REQUESTS:     IRequestsResponse;
  ABOUT:        IAboutResponse;
  INTERESTS:    IInterestsResponse;
  BADGES:       IBadgesApiResponse;
}
