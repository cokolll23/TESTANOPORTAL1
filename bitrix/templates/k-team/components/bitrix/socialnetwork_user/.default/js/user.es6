class SocialNetworkUser {

  constructor() {
    this.star = BX('uiToolbarStarCustom');
    if (this.star) {
      BX.unbindAll(this.star);
      BX.bind(this.star, 'click', BX.delegate(this.handlerClick, this))
    }
  }

  handlerClick() {
    if (this.star.classList.contains('ui-toolbar-star-active')) {
      this.sendForm(BX.data(this.favoriteHelper, 'user-id'), 'delete');
      this.star.title = 'Добавить сотрудника в избранное в телефонном справочнике';
    } else {
      this.sendForm(BX.data(this.favoriteHelper, 'user-id'), 'add');
      this.star.title = 'Удалить сотрудника из избранного в телефонном справочнике';
    }
    this.star.classList.toggle('ui-toolbar-star-active')
  }

  /**
   * Отправка формы
   * @param {int} userId
   * @param {string} action
   * @returns {undefined}
   */
  sendForm(userId, action) {

    const bxFormData = new BX.ajax.FormData();

    bxFormData.append('userId', userId);
    bxFormData.append('action', action);
    let self = this;
    bxFormData.send('/bitrix/components/korus/phonedirectory.favourites/ajax.php', function (data) {
        let dataOb = BX.parseJSON(data);
        if (dataOb.data.componentResult.status === 'error') {
          console.error(dataOb.data.componentResult.errorMessage);
        } else {
          if (action === 'add') {
            self.setStarActive();
          } else {
            self.setStarInactive();
          }
        }
      }, null, //тут может быть колбэк для прогресс-бара
      function (error) {
        console.log(`error: ${error}`); //колбэк при ошибке
      });
  }
}
