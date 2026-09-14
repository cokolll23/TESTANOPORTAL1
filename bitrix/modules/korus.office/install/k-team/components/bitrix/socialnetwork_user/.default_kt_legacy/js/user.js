BX.ready(() => {
  BX.namespace('BX.Bitrix24');
  const favoriteHelper = BX('favorite_user_helper');
  const star = BX('uiToolbarStar');
  if (BX.data(favoriteHelper, 'is-favorite') === 'true') {
    setStarActive();
  } else {
    setStarInactive();
  }
  BX.Bitrix24.LeftMenu.addStandardItem = function () {
    sendForm(BX.data(favoriteHelper, 'user-id'), 'add');
  };
  BX.Bitrix24.LeftMenu.deleteStandardItem = function () {
    sendForm(BX.data(favoriteHelper, 'user-id'), 'delete');
  };
  function setStarActive() {
    star.title = 'Удалить сотрудника из избранного в телефонном справочнике';
    BX.addClass(star, 'ui-toolbar-star-active');
    BX.Bitrix24.LeftMenu.isCurrentPageInLeftMenu = true;
  }
  function setStarInactive() {
    star.title = 'Добавить сотрудника в избранное в телефонном справочнике';
    BX.removeClass(star, 'ui-toolbar-star-active');
    BX.Bitrix24.LeftMenu.isCurrentPageInLeftMenu = false;
  }

  /**
   * Отправка формы
   * @param {int} oPopup
   * @param {string} oPopupSuccess
   * @returns {undefined}
   */
  function sendForm(userId, action) {
    const bxFormData = new BX.ajax.FormData();
    bxFormData.append('userId', userId);
    bxFormData.append('action', action);
    bxFormData.send('/bitrix/components/korus/phonedirectory.favourites/ajax.php', function (data) {
      let dataOb = BX.parseJSON(data);
      if (dataOb.data.componentResult.status === 'error') {
        console.error(dataOb.data.componentResult.errorMessage);
      } else {
        if (action === 'add') {
          setStarActive();
        } else {
          setStarInactive();
        }
      }
    }, null,
    //тут может быть колбэк для прогресс-бара
    function (error) {
      console.log(`error: ${error}`); //колбэк при ошибке
    });
  }
});