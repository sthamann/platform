import { Component, Mixin } from 'src/core/shopware';
import { warn } from 'src/core/service/utils/debug.utils';

import template from './sw-rating-detail.html.twig';
import './sw-rating-detail.scss';

Component.register('sw-rating-detail', {
    template,

    inject: ['repositoryFactory', 'context'],

    mixins: [
        Mixin.getByName('placeholder'),
        Mixin.getByName('notification')
    ],

    data() {
        return {
            isLoading: null,
            ratingId: null,
            rating: {},
            repository: null
        };
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
            this.repository = this.repositoryFactory.create('product_rating');

            if (this.$route.params.id) {
                this.ratingId = this.$route.params.id;

                this.loadEntityData();
            }
        },
        loadEntityData() {
            this.isLoading = true;
            this.repository.get(this.ratingId, this.context).then((rating) => {
                console.log('rating : ', rating);
                this.rating = rating;
                this.isLoading = false;
            });
        },

        onSave() {
            const ratingName = this.rating.name || this.rating.translated.name;
            const titleSaveSuccess = this.$tc('sw-rating.detail.titleSaveSuccess');
            const messageSaveSuccess = this.$tc('sw-rating.detail.messageSaveSuccess', 0, { name: ratingName });
            const titleSaveError = this.$tc('global.notification.notificationSaveErrorTitle');
            const messageSaveError = this.$tc(
                'global.notification.notificationSaveErrorMessage', 0, { entityName: ratingName }
            );
            this.repository.save(this.rating).then(() => {
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
