export default {
  adminSet: {
    on: 'You are currently in social network administrator mode and can view and edit restricted data.',
    off: 'In Administrator mode you can view and edit restricted data.',
    set: 'Enter Administrator mode',
    unset: 'Exit Administrator mode'
  },
  badges: {
    popup: {
      title: 'Awards'
    }
  },
  personalPhoto: {
    currentVacationLabel: 'Vacation',
    replacementStaff: 'Replacement staff',
    chatMessage: 'Chat',
    videoCall: 'Video call',
    menu: {
      adminMode: 'Admin mode',
      quitAdminMode: 'Exit Admin mode',

      setAdminRights: 'Assign admin permissions',
      removeAdminRights: 'Revoke admin permissions',

      moveAdminRightsConfirm: `
        Your Bitrix24 has reached maximum possible number of administrators.<br/>
        Your administrator privileges will be revoked if you set this user as administrator.
        Are you sure you want to transfer admin permissions to this user?
      `,
      moveToIntranet: 'Transfer to intranet',

      fireUser: 'Dismiss',
      fireUserConfirm: 'The employee will not be able to log on to Bitrix24, and will not be seen in the company structure. All of their data (files, messages, tasks etc.) will remain intact.',
      fireInvitedUser: `
        You cannot delete the invited user from Bitrix24 because there are bindings to other entities.
        Deactivate (dismiss) the user to close their access to this Bitrix24.
        <br><br>Do you want to dismiss the user?
      `,

      hireUser: 'Hire',
      hireUserConfirm: 'The employee will be able to log on to Bitrix24 and will be seen in the company structure.',

      reinviteUser: 'Invite again',

      deleteUser: 'Delete',
      deleteUserConfirm: 'Are you sure you want to delete the invited user?',

      setIntegratorRights: 'Grant Bitrix24 partner\'s rights',
      setIntegratorRightsConfirm: `
        Certified Bitrix24 partners can help you with the setup or fine-tune your Bitrix24 to your company's workflows: the CRM, Open Channels, documentation, telephony, reports and other business tools.
        #LINK_START#Learn more#LINK_END#
      `
    },
    status: {
      admin: 'Administrator',
      integrator: 'Bitrix24 partner',
      extranet: 'Extranet',
      fired: 'Dismissed',
      invited: 'Invited',
      email: 'Email user',
      shop: 'Web store user',
      visitor: 'Guest',
      employee: 'Actions'
    }
  },
  personalInfo: {
    city: 'City',
    email: 'E-mail',
    workPhone: 'Phone',
    personalPhone: 'Personal phone',
    messengers: 'Messengers',
    workplace: 'Workplace',
    workplaceMapOffice: 'on office mao',
    companyExperience: 'Company experience',
    employmentDate: 'Date of employment',
    personnelNumber: 'Personnel number',
    director: 'Director'
  },
  personalInfoEdit: {
    btnSave: 'Save',
    btnCancel: 'Cancel'
  },
  avatarUploader: {
    limitText: 'Можно загрузить картинку в формате png и jpg. Для корректного отображения рекомендуется загружать файл размером не меньше 300 × 300 пикселей.',
    createPhotoBtn: 'Create photo',
    uploadPhotoBtn: 'Upload photo'
  },
  structure: {
    division: 'Division'
  },
  shop: {
    title: 'My score',
    text: 'Get bonuses for activities and use them in our corporate store',
    btn: 'To the store'
  },
  competencies: {
    title: 'Competencies',
    edit: 'Edit',
    add: 'Save',
    cancel: 'Cancel',
    stub: {
      description: 'It is empty. Write our skills',
      btn: 'Write'
    },
    popup: {
      description: 'Чтобы добавить компетенции, просто напишите Ваши навыки через пробел и нажмите Enter.'
    }
  },
  gratitudes: {
    title: 'Gratitudes',
    showMoreBtn: 'Show next 5',
    showPreviousBtn: 'Show previous 5',
    thanksBtn: 'Give thanks'
  },
  favorites: {
    title: 'Favorites'
  },
  services: {
    title: 'My services',
    allServicesLink: 'All services',
    tabs: {
      main: 'Main',
      favorites: 'Favorites'
    }
  },
  vacation: {
    title: 'Vacation',
    balance: 'Balance',
    days: 'd.',
    periodLabel: 'Plans',
    takeVacationButton: 'Take a vacation'
  },
  policy: {
    title: 'DMS policy',
    company: 'Company',
    myClinicsButton: 'My clinics',
    servicesButton: 'Services',
    phoneAppButton: 'Phone app'
  },
  mobileConnection: {
    title: 'Mobile connection',
    operator: 'Operator'
  },
  requests: {
    title: 'My requests',
    table: {
      titleLabel: 'Request',
      dateCreateLabel: 'Creation date',
      dateEndLabel: 'Plan date',
      statusLabel: 'Status',
      executorLabel: 'Executor',
      commentLabel: 'Com...'
    }
  },
  aboutMe: {
    title: 'About me',
    description: '',
    btn: '',
    btnChange: 'Change',
    btnSave: 'Save',
    btnCancel: 'Cancel'
  },
  interests: {
    title: 'My interests',
    edit: 'Edit',
    save: 'Save',
    cancel: 'Cancel',
    stub: {
      description: '',
      btn: ''
    },
    popup: {
      title: 'Popular tags',
      description: ''
    }
  },
  pagination: {
    perPage: 'Per Page'
  },
  datepicker: {
    closePopup: 'Close'
  },
  workplace: {
    workplace: 'Work place',
    booking: 'Today i`m here'
  }
}
