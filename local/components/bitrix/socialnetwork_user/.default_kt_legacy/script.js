;(function (window, document) {

    BX.namespace('BX.Korus.Office');

    const SELECTORS = {
        container: '#lk-pagetitle-menu',

        emojiStatusWrapper: '.js-kt-emoji-status-wrapper',
        emojiStatusTriggerBtn: '.js-kt-emoji-status-trigger-btn',
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
                    document.addEventListener('PersonalInfo:onMounted', () => {
                        resolve(true);
                    });
                }
            });

            this.bindMethods();
            this.bindEvents();

            Promise.all([this.fetchActive(), containerReady]).then((values) => {
                const response = values[0];
                const data = response.data[0];

                if (!this.isOwnProfile && !data) {
                    return;
                }

                this.triggerMode = !!data ? 'emoji' : 'stub';

                this.renderTrigger();
                this.initLoader();

                if (!!data) {
                    this.setActive(data);
                }
            })
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
            const screenSize = getScreenSize();

            this.popup = new BX.PopupWindow('emoji', this.$trigger, {
                className: 'kt-emoji-status-popup',
                autoHide: true,
                lightShadow: true,
                closeByEsc: true,
                overlay: false,
                offsetTop: screenSize <= 768 ? 15 : -32,
                offsetLeft: screenSize <= 768 ? 0 : 44,
                events: {
                    onPopupClose: function () {
                        this.popup.destroy();
                        this.popup = null;
                    }.bind(this)
                }
            });
        }

        initDetailsPopup() {
            this.detailsPopup = new BX.PopupWindow('emoji-details', this.$trigger, {
                className: 'kt-emoji-status-details-popup',
                autoHide: true,
                lightShadow: true,
                closeByEsc: true,
                overlay: false,
                offsetTop: -32,
                offsetLeft: 44,
                events: {
                    onPopupClose: function () {
                        this.detailsPopup.destroy();
                        this.detailsPopup = null;
                    }.bind(this)
                }
            });
        }

        initScrollbar() {
            console.log(this.$list)
            $(this.$list).scrollbar('resize');
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
            if (this.triggerMode === 'stub') {
                return;
            }

            const screenSize = getScreenSize();

            if (screenSize <= 768 || this.popup) {
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
            const screenSize = getScreenSize();

            if (screenSize <= 768 || !this.detailsPopup) {
                return;
            }

            this.detailsPopup.close();
        }

        setActive(emojiActive) {
            this.active = emojiActive;
            this.$trigger.style.backgroundImage = `url('${this.active.IMAGE}')`;
            this.$trigger.className = 'kt-emoji-icon kt-emoji-status-trigger-btn js-kt-emoji-status-trigger-btn';
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
                        {name: 'X-Bitrix-Csrf-Token', value: BX.bitrix_sessid()},
                        {name: 'X-Bitrix-Site-Id', value: BX.message('SITE_ID')}
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
                this.toggleTriggerOpacity(true);

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
                        this.toggleTriggerOpacity(false);
                        resolve(response.data);
                    }.bind(this),
                    onfailure: function (error) {
                        this.loader.hide();
                        this.toggleTriggerOpacity(false);
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

            if (emojiNext)
                this.setActive(emojiNext);

            this.loader.show();
            this.toggleTriggerOpacity(true);

            BX.ajax({
                method: 'PUT',
                headers: [
                    {name: 'X-Bitrix-Csrf-Token', value: BX.bitrix_sessid()},
                    {name: 'X-Bitrix-Site-Id', value: BX.message('SITE_ID')}
                ],
                url: `/api/v1/employees/${BX.message('LK_USER_ID')}/emoji/${id ?? 0}`,
                dataType: 'json',
                onsuccess: function () {
                    this.loader.hide();
                    this.toggleTriggerOpacity(false);
                }.bind(this),
                onfailure: function () {
                    if (!!emojiOld) {
                        this.setActive(emojiOld);
                    } else {
                        this.triggerMode = 'stub';
                        this.renderTrigger();
                        this.initLoader();
                    }
                    this.loader.hide();
                    this.toggleTriggerOpacity(false);
                }.bind(this)
            });
        }

        toggleTriggerOpacity(isTransparent) {
            this.$trigger.style.opacity = isTransparent ? '0.3' : '';
        }

        onWheelHandler() {
            if (this.popup) {
                this.popup.close();
            }

            if (this.detailsPopup) {
                this.detailsPopup.close();
            }
        }


        renderTrigger() {
            BX.remove(this.$trigger);

            const trigger = this.triggerMode === 'stub'
                ? this.renderStubTrigger()
                : this.renderEmojiTrigger();

            BX.prepend(trigger, this.$container);
        }

        render(data) {
            if (data.find((emoji) => emoji.NAME === 'Без статуса') === undefined)
                data.push({
                    ID: 0,
                    IMAGE: null,
                    NAME: 'Без статуса'
                })
            return BX.create('div', {
                props: {className: 'kt-emoji'},
                children: [
                    BX.create('h3', {
                        props: {className: 'kt-emoji-title'},
                        text: 'Emoji-статус:'
                    }),
                    BX.create('div', {
                        props: {className: 'kt-emoji-list js-emoji-list'},
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
                        props: {className: 'kt-emoji-card__icon kt-emoji-icon'},
                        style: {'background-image': `url('${props.IMAGE}')`}
                    }))

            children.push(
                BX.create('span', {
                    props: {className: 'kt-emoji-card__text'},
                    text: props.NAME
                }))

            return BX.create('button', {
                props: {className},
                attrs: {'data-id': props.ID},
                events: {
                    click: function (event) {
                        if (isDetails) {
                            return;
                        }

                        const card = event.target.closest('.js-emoji-card');
                        const id = card.dataset.id;

                        this.changeStatus(id);
                        this.popup.close();
                    }.bind(this)
                },
                children: children
            });
        }

        renderStubTrigger() {
//            document.querySelector('.pagetitle-inner-container .pagetitle-last-item-in-a-row').classList.add('button-add');
            document.querySelector('.kt-emoji-status-wrapper.js-kt-emoji-status-wrapper')?.remove()

            return BX.create('div', {
                props: {className: 'kt-emoji-status-wrapper js-kt-emoji-status-wrapper'},
                children: [
                    BX.create('button', {
                        props: {
                            type: 'button',
                            className: 'q-btn q-btn-item q-btn--no-uppercase non-selectable no-outline q-btn--rectangle q-btn--actionable q-focusable q-hoverable kt-button kt-button--primary-theme kt-button--md flex-shrink-0 js-kt-emoji-status-trigger-btn'
                        },
                        attrs: {
                            tabindex: '0'
                        },
                        events: {
                            click: this.showPopup,
                            mouseenter: this.showDetailsPopup,
                            mouseleave: this.hideDetailsPopup
                        },
                        children: [
                            BX.create('span', {
                                props: {className: 'q-focus-helper'}
                            }),
                            BX.create('span', {
                                props: {className: 'q-btn__content text-center col items-center q-anchor--skip justify-center row'},
                                children: [
                                    BX.create('span', {
                                        props: {className: 'block'},
                                        text: 'Указать статус'
                                    })
                                ]
                            })
                        ]
                    })
                ]
            });
        }

        renderEmojiTrigger() {
            document.querySelector('.kt-emoji-status-wrapper.js-kt-emoji-status-wrapper')?.remove()
            return BX.create('div', {
                props: {className: 'kt-emoji-status-wrapper js-kt-emoji-status-wrapper'},
                children: [
                    BX.create('button', {
                        props: {
                            type: 'button',
                            className: 'q-btn q-btn-item q-btn--no-uppercase non-selectable no-outline q-btn--outline q-btn--rectangle q-btn--actionable q-focusable q-hoverable kt-button kt-button--primary-theme kt-button--md flex-shrink-0 kt-emoji-icon kt-emoji-status-trigger-btn js-kt-emoji-status-trigger-btn'
                        },
                        events: {
                            click: this.showPopup,
                            mouseenter: this.showDetailsPopup,
                            mouseleave: this.hideDetailsPopup
                        }
                    })
                ]
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
            this.loader.destroy();
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

    function getScreenSize() {
        return document.documentElement.clientWidth || document.body.clientWidth;
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
