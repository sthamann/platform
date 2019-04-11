import { Component, Mixin, State } from 'src/core/shopware';
import template from './sw-rating-list.html.twig';

Component.register('sw-rating-list', {
    template,

    mixins: [
        Mixin.getByName('listing')
    ],

    data() {
        return {
            ratings: [],
            showDeleteModal: false,
            isLoading: false,
            sortBy: 'createdAt',
            sortDirection: 'DESC'
        };
    },

    computed: {
        ratingStore() {
            return State.getStore('product_rating');
        }
    },

    methods: {
        onInlineEditSave(rating) {
            this.isLoading = true;

            return rating.save().then(() => {
                this.isLoading = false;
            }).catch(() => {
                this.isLoading = false;
            });
        },

        onChangeLanguage(languageId) {
            this.getList(languageId);
        },

        onDeleteRating(id) {
            this.showDeleteModal = id;
        },

        onCloseDeleteModal() {
            this.showDeleteModal = false;
        },

        onConfirmDelete(id) {
            this.showDeleteModal = false;

            return this.ratingStore.store[id].delete(true).then(() => {
                this.getList();
            });
        },

        getList() {
            this.isLoading = true;
            const params = this.getListingParams();

            // Default sorting
            if (!params.sortBy && !params.sortDirection) {
                params.sortBy = 'createdAt';
                params.sortDirection = 'DESC';
            }

            this.ratings = [];

            return this.ratingStore.getList(params).then((response) => {
                this.total = response.total;
                this.ratings = response.items;
                this.isLoading = false;

                return this.ratings;
            });
        }
    }
});
