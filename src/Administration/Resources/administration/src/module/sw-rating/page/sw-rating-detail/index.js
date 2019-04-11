import { Component, Mixin } from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

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
            repository: null,
            productRepository: null,
            product: null
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
            this.productRepository = this.repositoryFactory.create('product');

            if (this.$route.params.id) {
                this.ratingId = this.$route.params.id;

                this.loadEntityData();
            }
        },
        loadEntityData() {
            this.isLoading = true;
            const criteria = new Criteria();
            criteria.addAssociation('customer');
            criteria.addAssociation('sales_channel');
            // criteria.addAssociation('product');
            criteria.addAssociation('language');

            this.repository.get(this.ratingId, this.context, criteria).then((rating) => {
                this.rating = rating;
                this.productRepository.get(this.rating.productId, this.context).then(product => {
                    this.product = product;
                });
                this.isLoading = false;
            });
        },

        onSave() {
            const ratingName = this.rating.title;
            const titleSaveSuccess = this.$tc('sw-rating.detail.titleSaveSuccess');
            const messageSaveSuccess = this.$tc('sw-rating.detail.messageSaveSuccess', 0, { name: ratingName });
            const titleSaveError = this.$tc('global.notification.notificationSaveErrorTitle');
            const messageSaveError = this.$tc(
                'global.notification.notificationSaveErrorMessage', 0, { entityName: ratingName }
            );
            this.repository.save(this.rating, this.context).then(() => {
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
