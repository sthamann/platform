import { Component, Mixin, State } from 'src/core/shopware';
import { cloneDeep, merge } from 'src/core/service/utils/object.utils';
import { warn } from 'src/core/service/utils/debug.utils';
import type from 'src/core/service/utils/types.utils';
import EntityProxy from 'src/core/data/EntityProxy';
import CriteriaFactory from 'src/core/factory/criteria.factory';
import template from './sw-category-detail.html.twig';
import './sw-category-detail.scss';

Component.register('sw-category-detail', {
    template,

    inject: ['cmsPageService'],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    data() {
        return {
            category: null,
            cmsPage: null,
            categories: [],
            isLoading: false,
            mediaItem: null,
            isMobileViewport: null,
            splitBreakpoint: 1024,
            isDisplayingLeavePageWarning: false,
            nextRoute: null,
            disableContextMenu: false,
            term: '',
            isSaveSuccessful: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    computed: {
        identifier() {
            return this.category ? this.placeholder(this.category, 'name') : '';
        },

        categoryStore() {
            return State.getStore('category');
        },

        cmsPageStore() {
            return State.getStore('cms_page');
        },

        mediaStore() {
            return State.getStore('media');
        },

        uploadStore() {
            return State.getStore('upload');
        },

        languageStore() {
            return State.getStore('language');
        },

        pageClasses() {
            return {
                'has--category': !!this.category,
                'is--mobile': !!this.isMobileViewport
            };
        }
    },

    watch: {
        '$route.params.id'() {
            this.setCategory();
            this.$refs.searchBar.clearSearchTerm();
        }
    },

    created() {
        this.createdComponent();
    },

    beforeRouteLeave(to, from, next) {
        if (this.category && this.category.hasChanges()) {
            this.isDisplayingLeavePageWarning = true;
            this.nextRoute = to;
            next(false);
        } else {
            next();
        }
    },

    methods: {
        createdComponent() {
            this.isLoading = true;
            this.checkViewport();
            this.registerListener();

            this.disableContextMenu = this.languageStore.getCurrentId() !== this.languageStore.systemLanguageId;

            this.getCategories().then(() => {
                this.setCategory();
            });
        },

        registerListener() {
            this.$device.onResize({
                listener: this.checkViewport.bind(this)
            });
        },

        onSearch(value) {
            if (value.length === 0) {
                value = undefined;
            }
            this.term = value;
        },

        checkViewport() {
            this.isMobileViewport = this.$device.getViewportWidth() < this.splitBreakpoint;
        },

        getCategories(parentId = null) {
            this.isLoading = true;

            const params = {
                page: 1,
                limit: 500,
                criteria: CriteriaFactory.equals('category.parentId', parentId),
                associations: {
                    navigationSalesChannels: {},
                    serviceSalesChannels: {},
                    footerSalesChannels: {}
                }
            };
            return this.categoryStore.getList(params, true).then((response) => {
                this.categories = Object.values(this.categoryStore.store);
                this.isLoading = false;
                return response.items;
            });
        },

        getAssignedCmsPage(cmsPageId) {
            if (cmsPageId === null) {
                this.cmsPage = null;
                return false;
            }

            const params = {
                criteria: CriteriaFactory.equals('cms_page.id', cmsPageId),
                associations: {
                    blocks: {
                        sort: 'position',
                        associations: {
                            slots: {}
                        }
                    }
                }
            };

            return this.cmsPageStore.getList(params, true).then((response) => {
                const cmsPage = new EntityProxy('cms_page', this.cmsPageService, response.items[0].id, null);
                cmsPage.setData(response.items[0], false, true, false);

                if (this.category.slotConfig !== null) {
                    cmsPage.getAssociation('blocks').forEach((block) => {
                        block.getAssociation('slots').forEach((slot) => {
                            if (this.category.slotConfig[slot.id]) {
                                merge(slot.config, cloneDeep(this.category.slotConfig[slot.id]));
                            }
                        });
                    });
                }

                this.cmsPage = cmsPage;
                return this.cmsPage;
            });
        },

        setCategory() {
            const categoryId = this.$route.params.id;
            this.isLoading = true;

            if (this.category) {
                this.category.discardChanges();
            }

            if (this.$route.params.id) {
                this.getCategory(categoryId).then(response => {
                    this.category = response;
                    this.getAssignedCmsPage(this.category.cmsPageId);

                    this.mediaItem = this.category.mediaId
                        ? this.mediaStore.getById(this.category.mediaId) : null;
                    this.isLoading = false;
                });
            } else {
                this.isLoading = false;
                this.category = null;
                this.mediaItem = null;
            }
        },

        onRefreshCategories() {
            this.getCategories();
        },

        onSaveCategories() {
            return this.categoryStore.sync();
        },

        openChangeModal(destination) {
            this.nextRoute = destination;
            this.isDisplayingLeavePageWarning = true;
        },

        onLeaveModalClose() {
            this.nextRoute = null;
            this.isDisplayingLeavePageWarning = false;
        },

        onLeaveModalConfirm(destination) {
            this.isDisplayingLeavePageWarning = false;
            this.category.discardChanges();
            this.$nextTick(() => {
                this.$router.push({ name: destination.name, params: destination.params });
            });
        },

        cancelEdit() {
            this.category.discardChanges();
            this.resetCategory();
        },

        resetCategory() {
            this.$router.push({ name: 'sw.category.index' });
            this.isLoading = true;
            this.category = null;
            this.mediaItem = null;
        },

        getCategory(categoryId) {
            return this.categoryStore.getByIdAsync(categoryId);
        },

        onUploadAdded(uploadTag) {
            this.isLoading = true;
            this.mediaStore.sync().then(() => {
                return this.uploadStore.runUploads(uploadTag);
            }).finally(() => {
                this.isLoading = false;
            });
        },

        openSidebar() {
            this.$refs.mediaSidebarItem.openContent();
        },

        setMediaItem(media) {
            this.mediaItem = media;
            this.category.mediaId = media.id;
        },

        removeMediaItem() {
            this.category.mediaId = null;
            this.mediaItem = null;
        },

        onUnlinkLogo() {
            this.mediaItem = null;
            this.category.mediaId = null;
        },

        onChangeLanguage() {
            this.disableContextMenu = this.languageStore.getCurrentId() !== this.languageStore.systemLanguageId;
            this.getCategories().then(() => {
                this.setCategory();
            });
        },

        abortOnLanguageChange() {
            return this.category ? this.category.hasChanges() : false;
        },

        saveOnLanguageChange() {
            return this.onSave();
        },


        saveFinish() {
            this.isSaveSuccessful = false;
        },

        onSave() {
            const categoryName = this.category.name || this.category.translated.name;
            const titleSaveError = this.$tc('global.notification.notificationSaveErrorTitle');
            const messageSaveError = this.$tc('global.notification.notificationSaveErrorMessage',
                0, { entityName: categoryName });
            this.isSaveSuccessful = false;

            const pageOverrides = this.getCmsPageOverrides();

            if (type.isPlainObject(pageOverrides) && Object.keys(pageOverrides).length > 0) {
                this.category.slotConfig = cloneDeep(pageOverrides);
            }

            this.isLoading = true;
            return this.category.save().then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
            }).catch(exception => {
                this.isLoading = false;
                this.createNotificationError({
                    title: titleSaveError,
                    message: messageSaveError
                });
                warn(this._name, exception.message, exception.response);
            });
        },

        getCmsPageOverrides(page = this.cmsPage) {
            if (page === null) {
                return null;
            }

            const slotOverrides = {};
            const changedBlocks = page.getChangedAssociations().blocks;

            if (type.isArray(changedBlocks)) {
                changedBlocks.forEach((block) => {
                    if (block.slots && block.slots.length > 0) {
                        block.slots.forEach((slot) => {
                            if (type.isPlainObject(slot.config)) {
                                slotOverrides[slot.id] = slot.config;
                            }
                        });
                    }
                });
            }

            return slotOverrides;
        }
    }
});
