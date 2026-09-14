import { IPersonalState } from 'stores/personal'
import { IPersonalFieldsState } from 'stores/personal-fields'
import { IPermissionsState } from 'stores/permissions'
import { ICurrentVacationsState } from 'stores/current-vacation'
import { IPersonOrgStructureState } from 'stores/person-org-structure'
import { IShopState } from 'stores/shop'
import { ICompetenciesState } from 'stores/competencies'
import { IGratitudesState } from 'stores/gratitudes'
import { IServicesState } from 'stores/services'
import { IRequestsState } from 'stores/requests'
import { IAboutMeState } from 'stores/about-me'
import { IInterestsState } from 'stores/interests'
import { IWorkplaceState } from 'stores/workplace'

export type IRootState = {
  isAppLoading: boolean;
  modules: {
    PERSONAL:     IPersonalState;
    FIELDS:       IPersonalFieldsState;
    PERMISSIONS:  IPermissionsState;
    VACATIONS:    ICurrentVacationsState;
    STRUCTURE:    IPersonOrgStructureState;
    SHOP:         IShopState;
    COMPETENCIES: ICompetenciesState;
    GRATITUDES:   IGratitudesState;
    SERVICES:     IServicesState;
    REQUESTS:     IRequestsState;
    ABOUT:        IAboutMeState;
    INTERESTS:    IInterestsState;
    WORKPLACE:    IWorkplaceState;
  }
}
