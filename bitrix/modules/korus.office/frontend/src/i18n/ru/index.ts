// This is just an example,
// so you can safely delete all default props below

export default {
  common: {
    btn: {
      add: 'Добавить',
      save: 'Сохранить',
      cancel: 'Отменить',
      addMore: 'Добавить ещё',
      restore: 'Восстановить по умолчанию'
    },
    pagination: {
      pageSize: 'На странице'
    }
  },
  tagSelect: {
    placeholder: 'Введите текст'
  },
  adminSet: {
    on: 'Вы находитесь в режиме администратора соцсети и можете видеть и изменять данные, доступ к которым ограничен.',
    off: 'В режиме администратора вы можете видеть и изменять данные, доступ к которым ограничен.',
    set: 'Перейти в режим администратора',
    unset: 'Выйти из режима администратора'
  },
  badges: {
    popup: {
      title: 'Награды'
    }
  },
  contactSettings: {
    title: 'Сохранение изменений',
    text: 'Вы можете установить данный шаблон по умолчанию и/или запретить пользователям сортировку полей в профиле',
    disableSort: 'Запретить пользователям сортировку полей',
    updateDefault: 'Установить для всех'
  },
  personalPhoto: {
    currentVacationLabel: 'Отпуск',
    replacementStaff: 'Замещающий на период отпуска',
    replacementStaffNone: 'отсутствует',
    replacementStaffAdd: 'Добавить заместителя',
    chatMessage: 'Чат',
    videoCall: 'Видеозвонок',
    menu: {
      adminMode: 'Режим администратора',
      quitAdminMode: 'Выйти из режима администратора',

      setAdminRights: 'Дать права администратора',
      removeAdminRights: 'Забрать права администратора',

      moveAdminRightsConfirm: `
        На вашем Битрикс24 назначено максимальное допустимое<br/> количество администраторов.
        При назначении данного<br/>пользователя вы лишитесь права администрировать портал.
        Вы уверены, что хотите передать права администратора?
      `,
      moveToIntranet: 'Перевести в интранет',

      fireUser: 'Уволить',
      fireUserConfirm: 'Сотрудник больше не сможет зайти на портал, не появится в структуре компании, но все его данные (файлы, сообщения, задачи и т.п.) сохранятся.',
      fireInvitedUser: `
        Вы не можете удалить приглашенного пользователя, так как он связан с другими инструментами.
        Деактивируйте (увольте) пользователя, чтобы закрыть ему доступ к этому Битрикс24.
        <br><br>Хотите уволить?
      `,

      hireUser: 'Принять на работу',
      hireUserConfirm: 'Сотрудник сможет зайти на портал, появится в структуре компании.',

      reinviteUser: 'Пригласить ещё раз',

      deleteUser: 'Удалить',
      deleteUserConfirm: 'Вы уверены, что хотите удалить приглашенного пользователя?',

      setIntegratorRights: 'Сделать интегратором',
      setIntegratorRightsConfirm: `
        Интегратор – это сертифицированный партнер Битрикс24. Интегратор поможет настроить ваш Битрикс24 под рабочие процессы именно вашей компании: CRM, открытые линии, документооборот, телефонию, отчеты и многое другое.
        '#LINK_START#Подробнее#LINK_END#
      `
    },
    status: {
      admin: 'Администратор',
      integrator: 'Интегратор',
      extranet: 'Экстранет',
      fired: 'Уволен',
      invited: 'Приглашен',
      email: 'Почтовый пользователь',
      shop: 'Пользователь магазина',
      visitor: 'Посетитель',
      employee: 'Действия'
    }
  },
  personalContacts: {
    title: 'Контактная информация',
    details: {
      expandText: 'Подробная информация',
      collapseText: 'Подробная информация'
    }
  },
  personalInfo: {
    city: 'Город',
    email: 'E-mail',
    workPhone: 'Телефон',
    personalPhone: 'Телефон личный',
    personalBirthday: 'День рождения',
    messengers: 'Мессенджеры',
    workplace: 'Рабочее место',
    workplaceMapOffice: 'на карте офиса',
    companyExperience: 'В компании',
    employmentDate: 'Дата приема на работу',
    personnelNumber: 'Табельный номер',
    director: 'Руководитель',
    subordinates: 'Подчиненные'
  },
  personalInfoEdit: {
    btnSave: 'Сохранить',
    btnCancel: 'Отмена',
    checkboxes: {
      disableSort: 'Запретить пользователям менять порядок сортировки полей',
      setFromDefault: 'Вернуть сортировку по умолчанию',
      updateDefault: 'Установить настройки для всех'
    }
  },
  avatarUploader: {
    limitText: 'Можно загрузить картинку в формате png и jpg. Для корректного отображения рекомендуется загружать файл размером не меньше 300 × 300 пикселей.',
    createPhotoBtn: 'Сделать фото',
    uploadPhotoBtn: 'Загрузить фото'
  },
  structure: {
    title: 'Организационная структура',
    division: 'Подразделение',
    details: {
      expandText: 'Показать команду',
      collapseText: 'Показать команду'
    }
  },
  contactWorkplace: {
    office: 'Офис',
    floor: 'Этаж',
    number: 'Номер рабочего места'
  },
  deputy: {
    title: 'Замещающие',
    stubText: 'У вас пока нет замещающих. Добавьте замещающих на необходимый период и эта информация будет доступна вашим коллегам.',
    btn: {
      add: 'Добавить'
    }
  },
  shop: {
    title: 'Мой счет',
    text: 'Получайте бонусы за активности и используйте их в нашем корпоративном магазине!',
    stubText: 'У Вас пока нет монет, заработайте их и тратьте в магазине призов',
    btn: 'В магазин'
  },
  competencies: {
    title: 'Компетенции',
    edit: 'Изменить',
    add: 'Сохранить',
    cancel: 'Отменить',
    stub: {
      description: 'Тут пока пусто, возможно, следует указать Вашу компетенцию?',
      btn: 'Указать'
    },
    popup: {
      description: 'Чтобы добавить компетенции, просто напишите Ваши навыки через пробел и нажмите Enter.'
    }
  },
  gratitudes: {
    title: 'Благодарности',
    showMoreBtn: 'Следующие 5',
    showPreviousBtn: 'Предыдущие 5',
    thanksBtn: 'Поблагодарить'
  },
  favorites: {
    title: 'Избранное'
  },
  services: {
    title: 'Мои сервисы',
    allServicesLink: 'Перейти в раздел',
    tabs: {
      main: 'Основное',
      favorites: 'Избранное'
    },
    stub: {
      mainDescription: 'Пока нет доступных сервисов',
      favoritesDescription: 'У вас пока нет избранных сервисов'
    }
  },
  vacation: {
    title: 'Отпуск',
    balance: 'Остаток',
    days: 'д.',
    periodLabel: 'Запланирован',
    takeVacationButton: 'Оформить отпуск'
  },
  policy: {
    title: 'Полис ДМС',
    company: 'Компания',
    myClinicsButton: 'Мои клиники',
    servicesButton: 'Услуги',
    phoneAppButton: 'Приложение'
  },
  mobileConnection: {
    title: 'Сотовая связь',
    operator: 'Оператор'
  },
  requests: {
    title: 'Мои заявки',
    tabs: {
      active: 'Активные',
      waiting: 'Ожидают реакции',
      closed: 'Закрыты'
    },
    table: {
      id: '№',
      title: 'Заявка',
      process: 'Процесс',
      status: 'Статус',
      deadline: 'Срок',
      dateCreate: 'Дата создания',
      dateEnd: 'Плановая дата',
      executor: 'Исполнитель',
      comment: 'Комментарий',
      noData: 'Заявки не найдены',
      manyUser: 'Исполнители'
    }
  },
  aboutMe: {
    title: 'Обо мне',
    description: 'Делитесь интересными историями из жизни, или просто расскажите о себе, загружайте фотографии памятных моментов.',
    btn: 'Рассказать о себе',
    btnChange: 'Изменить',
    btnSave: 'Сохранить',
    btnCancel: 'Отменить'
  },
  interests: {
    title: 'Мои интересы',
    edit: 'Изменить',
    save: 'Сохранить',
    cancel: 'Отменить',
    stub: {
      description: 'Создавайте интересы или присоединяйтесь к уже существующим. Найдите друзей, разделяющих ваши увлечения.',
      btn: 'Выбрать интересы'
    },
    popup: {
      title: 'Популярные теги',
      description: 'Чтобы добавить интересы, просто напишите интересующие вас занятия через пробел и нажмите Enter.'
    }
  },
  pagination: {
    perPage: 'На странице'
  },
  datepicker: {
    closePopup: 'Закрыть'
  },
  workplace: {
    workplace: 'Рабочее место',
    booking: 'Сегодня я здесь'
  }
}
