;(function (window, document) {

    BX.namespace('BX.Korus.Office');

    const SELECTORS = {
        container: '#personal-status',

        emojiStatusWrapper: '.js-kt-emoji-status-wrapper',
        emojiStatusTriggerBtn: '.js-kt-emoji-status-trigger-btn',
        emojiActive: '.js-emoji-active',
        emojiStatusList: '.js-emoji-list',
        emojiStatusCard: '.js-emoji-card'
    };

    class KtEmoji {
        constructor(params) {
            this.isOwnProfile = params.isOwnProfile;
            this.triggerMode = 'stub';
            this.listLoading = false;

            this.popup = null;
            this.detailsPopup = null;

            this.active = null;
            this.list = [];

            const containerReady = new Promise((resolve, reject) => {
                if (this.$container) {
                    resolve(true);
                } else {
                    document.addEventListener('PersonalCommonMounted', () => {
                        resolve(true);
                    });
                }
            });

            this.bindMethods();
            this.bindEvents();

            Promise.all([this.fetchActive(), containerReady]).then((values) => {
                const response = values[0];
                const data = response.data[0];

                this.triggerMode = !!data ? 'emoji' : 'stub';

                if (data) {
                    this.setActive(data);
                    this.renderActiveEmoji();
                }

                if (this.isOwnProfile) {
                    this.renderTrigger();
                    this.initLoader();
                }
            });
        }

        destructor() {
            this.unbindEvents();
        }

        get $container() {
            return document.querySelector(SELECTORS.container);
        }

        get $trigger() {
            return document.querySelector(SELECTORS.emojiStatusTriggerBtn);
        }

        get $emojiActive () {
            return document.querySelector(SELECTORS.emojiActive);
        }

        get $wrapper() {
            return document.querySelector(SELECTORS.emojiStatusWrapper);
        }

        get $list() {
            return document.querySelector(SELECTORS.emojiStatusList);
        }

        get $cards() {
            return document.querySelectorAll(SELECTORS.emojiStatusCard);
        }

        initPopup() {
            this.popup = new BX.PopupWindow('emoji', this.$trigger, {
                className: 'kt-emoji-status-popup',
                autoHide: true,
                lightShadow: true,
                closeIcon: true,
                closeByEsc: true,
                overlay: false,
                offsetTop: BX.KTeam.KPHelpers.isMobile() ? 15 : -32,
                offsetLeft: BX.KTeam.KPHelpers.isMobile() ? 0 : 44,
                events: {
                    onPopupClose: function () {
                        this.popup.destroy();
                        this.popup = null;
                    }.bind(this)
                }
            });
        }

        initDetailsPopup() {
            this.detailsPopup = new BX.PopupWindow('emoji-details', this.$emojiActive, {
                className: 'kt-emoji-status-details-popup',
                autoHide: true,
                lightShadow: true,
                closeByEsc: true,
                overlay: false,
                offsetTop: 16,
                offsetLeft: 4,
                events: {
                    onPopupClose: function () {
                        this.detailsPopup.destroy();
                        this.detailsPopup = null;
                    }.bind(this)
                }
            });
        }

        initScrollbar() {
            if (typeof BX.KTeam.Scrollbar !== 'undefined') {
                BX.KTeam.Scrollbar.init(this.$list);
            }
        }

        initLoader() {
            if (this.loader) {
                this.loader.destroy();
            }

            const offset = {
                left: '-1px'
            };

            if (this.triggerMode === 'stub') {
                offset.top = '5px';
            }

            this.loader = new BX.Loader({
                target: this.$wrapper,
                size: 40,
                mode: 'lk',
                offset
            });
        }

        showPopup() {
            if (!this.isOwnProfile || this.listLoading) {
                return;
            }

            if (this.popup) {
                this.popup.close();
                return;
            }

            this.loadList().then((data) => {
                this.setList(data);
                this.initPopup();
                this.popup.setContent(this.render(this.list));
                this.popup.show();
                this.initScrollbar();
            });
        }

        showDetailsPopup() {
            if (this.triggerMode === 'stub' || BX.KTeam.KPHelpers.isMobile() || this.popup) {
                return;
            }

            const content = BX.create('div', {
                children: [
                    this.renderEmojiCard(this.active, true)
                ]
            });

            this.initDetailsPopup();
            this.detailsPopup.setContent(content);
            this.detailsPopup.show();
        }

        hideDetailsPopup() {
            if (BX.KTeam.KPHelpers.isMobile() || !this.detailsPopup) {
                return;
            }

            this.detailsPopup.close();
        }

        setActive(emojiActive) {
            this.active = emojiActive;
            this.$cards.forEach(card => {
                card.dataset.id === this.active.ID
                    ? card.classList.add('is-active')
                    : card.classList.remove('is-active');
            });
        }

        setList(list) {
            this.list = list;
        }

        fetchActive() {
            return new Promise((resolve, reject) => {
                BX.ajax({
                    method: 'GET',
                    headers: [
                        { name: 'X-Bitrix-Csrf-Token', value: BX.bitrix_sessid() },
                        { name: 'X-Bitrix-Site-Id', value: BX.message('SITE_ID') }
                    ],
                    dataType: 'json',
                    url: `/api/v1/employees/${BX.message('LK_USER_ID')}/emoji`,
                    onsuccess: function (response) {
                        resolve(response);
                    }.bind(this),
                    onfailure: function (error) {
                        reject(error);
                    }.bind(this)
                });
            })
        }

        loadList() {
            return new Promise((resolve, reject) => {
                if (this.list.length > 0) {
                    return resolve(this.list);
                }

                this.listLoading = true;
                this.loader.show();

                BX.ajax({
                    method: 'GET',
                    headers: [
                        {name: 'X-Bitrix-Csrf-Token', value: BX.bitrix_sessid()},
                        {name: 'X-Bitrix-Site-Id', value: BX.message('SITE_ID')}
                    ],
                    dataType: 'json',
                    url: `/api/v1/employees/${BX.message('LK_USER_ID')}/emojiList`,
                    onsuccess: function (response) {
                        this.listLoading = false;
                        this.loader.hide();
                        resolve(response.data);
                    }.bind(this),
                    onfailure: function (error) {
                        this.loader.hide();
                        reject(error);
                    }.bind(this)
                });
            })
        }

        changeStatus(id) {
            const emojiOld = this.active;
            let emojiNext = id ? this.list.find(item => item.ID === id) : null;

            if (this.triggerMode === 'stub') {
                this.triggerMode = 'emoji';
                this.renderTrigger();
                this.initLoader();
            }

            if (!id) {
                this.triggerMode = 'stub';
                this.renderTrigger();
            }

            if (emojiNext) {
                this.setActive(emojiNext);
                this.renderActiveEmoji();
            }
            else {
                BX.remove(this.$emojiActive);
            }

            this.loader.show();

            BX.ajax({
                method: 'PUT',
                headers: [
                    { name: 'X-Bitrix-Csrf-Token', value: BX.bitrix_sessid() },
                    { name: 'X-Bitrix-Site-Id', value: BX.message('SITE_ID') }
                ],
                url: `/api/v1/employees/${BX.message('LK_USER_ID')}/emoji/${id ?? 0}`,
                dataType: 'json',
                onsuccess: function () {
                    this.loader.hide();
                }.bind(this),
                onfailure: function () {
                    if (!!emojiOld) {
                        this.setActive(emojiOld);
                        this.renderActiveEmoji();
                    } else {
                        this.triggerMode = 'stub';
                        this.renderTrigger();
                        this.initLoader();
                    }
                    this.loader.hide();
                }.bind(this)
            });
        }

        onWheelHandler() {
            if (this.popup) {
                this.popup.close();
            }

            if (this.detailsPopup) {
                this.detailsPopup.close();
            }
        }

        renderActiveEmoji () {
            BX.remove(this.$emojiActive);

            const selectedEmoji = BX.create('div', {
                props: { className: 'kt-emoji-icon js-emoji-active' },
                style: {
                    backgroundImage: `url('${this.active.IMAGE}')`
                },
                events: {
                    pointerenter: this.showDetailsPopup,
                    pointerleave: this.hideDetailsPopup
                }
            });

            BX.prepend(selectedEmoji, this.$container);
        }


        renderTrigger() {
            BX.remove(this.$trigger);
            BX.remove(this.$wrapper);

            const trigger = BX.create('div', {
                props: { className: 'kt-emoji-status-wrapper js-kt-emoji-status-wrapper' },
                children: [
                    BX.create('button', {
                        props: {
                            type: 'button',
                            className: 'ui-btn ui-btn-sm ui-btn-light-border js-kt-emoji-status-trigger-btn'
                        },
                        text: this.triggerMode === 'stub' ? 'Указать статус' : 'Изменить',
                        events: {
                            click: this.showPopup
                        }
                    })
                ]
            });

            BX.append(trigger, this.$container);
        }

        render(data) {
            if (data.find((emoji) => emoji.NAME === 'Без статуса') === undefined)
                data.push({
                    ID: 0,
                    IMAGE: null,
                    NAME: 'Без статуса'
                })
            return BX.create('div', {
                props: { className: 'kt-emoji' },
                children: [
                    BX.create('h3', {
                        props: { className: 'kt-emoji-title' },
                        text: 'Emoji-статус:'
                    }),
                    BX.create('div', {
                        props: { className: 'kt-emoji-list js-emoji-list' },
                        children: data.map(item => this.renderEmojiCard(item))
                    })
                ]
            });
        }

        renderEmojiCard(props, isDetails = false) {
            let className = 'kt-emoji-card js-emoji-card';

            if (!isDetails) {
                className += ' is-hoverable'
            }

            if (this.active && props.ID === this.active.ID && !isDetails) {
                className += ' is-active';
            }

            let children = [];

            if (props.IMAGE)
                children.push(
                    BX.create('span', {
                        props: { className: 'kt-emoji-card__icon kt-emoji-icon' },
                        style: { 'background-image': `url('${props.IMAGE}')` }
                    }))

            children.push(
                BX.create('span', {
                    props: { className: 'kt-emoji-card__text' },
                    text: props.NAME
                }))

            return BX.create('button', {
                props: { className },
                attrs: { 'data-id': props.ID },
                events: {
                    click: function (event) {
                        if (isDetails) {
                            return;
                        }

                        const card = event.target.closest(SELECTORS.emojiStatusCard);
                        const id = card.dataset.id;

                        this.changeStatus(id);
                        this.popup.close();
                    }.bind(this)
                },
                children: children
            });
        }

        bindMethods() {
            this.showPopup = this.showPopup.bind(this);
            this.changeStatus = this.changeStatus.bind(this);
            this.showDetailsPopup = this.showDetailsPopup.bind(this);
            this.hideDetailsPopup = this.hideDetailsPopup.bind(this);
            this.onWheelHandler = BX.debounce(this.onWheelHandler, 200, this);
            this.unbindEvents = this.unbindEvents.bind(this);
        }

        bindEvents() {
            window.addEventListener('beforeunload', this.unbindEvents);
        }

        unbindEvents() {
            if (this.loader) {
                this.loader.destroy();
            }

            window.removeEventListener('beforeunload', this.unbindEvents);
        }
    }

    bindEvents();

    function onLkPermissionsLoaded(event) {
        const permissions = event.detail.permissions
        const isOwnProfile = permissions.IS_OWN_PROFILE

        if (typeof permissions.DISABLE_EMOJI_STATUS !== 'undefined' && permissions.DISABLE_EMOJI_STATUS === true) {
            return;
        }

        BX.Korus.Office.Emoji = new KtEmoji({
            isOwnProfile
        });
    }

    function bindEvents() {
        window.addEventListener('Lk:PermissionsLoaded', onLkPermissionsLoaded);
        window.addEventListener('beforeunload', unbindEvents);
    }

    function unbindEvents() {
        if (BX.Korus.Office && BX.Korus.Office.Emoji) {
            BX.Korus.Office.Emoji.destructor();
        }

        window.removeEventListener('Lk:PermissionsLoaded', onLkPermissionsLoaded);
        window.removeEventListener('beforeunload', unbindEvents);
    }

    window.renderApiResponse = (text, status) => {
        const div = document.querySelector('div.page-header');
        if (!div) return false;
        const alert = document.createElement('div');
        alert.classList.add('ui-alert', `ui-alert-${status}`);
        alert.innerHTML = `
            <span class="ui-alert-message">${text}</span>
        `;
        div.insertBefore(alert, div.children[0]);
    }

    window.clearUrl = () => {
        const url = new URL(document.location);
        const searchParams = url.searchParams;
        searchParams.delete('question');
        searchParams.delete('contactId');
        window.history.pushState({}, '', url.toString());
    }

})(window, document);
