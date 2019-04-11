import { Component, Mixin, State } from 'src/core/shopware';
import { warn } from 'src/core/service/utils/debug.utils';

import template from './sw-rating-detail.html.twig';
import './sw-rating-detail.scss';

Component.register('sw-rating-detail', {
    template,

    mixins: [
        Mixin.getByName('placeholder'),
        Mixin.getByName('notification'),
        Mixin.getByName('discard-detail-page-changes')('rating')
    ],

    data() {
        return {
            ratingId: null,
            rating: { isLoading: true },
            mediaItem: null,
            attributeSets: []
        };
    },

    computed: {
        ratingStore() {
            return State.getStore('product_rating');
        }


    },

    created() {
        this.createdComponent();
    },

    watch: {
        '$route.params.id'() {
            this.createdComponent();
        }
    },

    methods: {
        createdComponent() {
            if (this.$route.params.id) {
                this.ratingId = this.$route.params.id;
                if (this.rating && this.rating.isLocal) {
                    return;
                }

                this.loadEntityData();
            }
        },
        loadEntityData() {
            this.ratingStore.getByIdAsync(this.ratingId).then((rating) => {
                this.rating = rating;
            });
        },
        abortOnLanguageChange() {
            return this.rating.hasChanges();
        },
        saveOnLanguageChange() {
            return this.onSave();
        },
        onChangeLanguage() {
            this.loadEntityData();
        },
        onSave() {
            const ratingName = this.rating.name || this.rating.translated.name;
            const titleSaveSuccess = this.$tc('sw-rating.detail.titleSaveSuccess');
            const messageSaveSuccess = this.$tc('sw-rating.detail.messageSaveSuccess', 0, { name: ratingName });
            const titleSaveError = this.$tc('global.notification.notificationSaveErrorTitle');
            const messageSaveError = this.$tc(
                'global.notification.notificationSaveErrorMessage', 0, { entityName: ratingName }
            );
            this.rating.save().then(() => {
                this.createNotificationSuccess({
                    title: titleSaveSuccess,
                    message: messageSaveSuccess
                });
            }).catch((exception) => {
                this.createNotificationError({
                    title: titleSaveError,
                    message: messageSaveError
                });
                warn(this._name, exception.message, exception.response);
                throw exception;
            });
        }
    }
});
