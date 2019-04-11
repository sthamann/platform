import { Component, State } from 'src/core/shopware';
import utils from 'src/core/service/util.service';
import template from './sw-rating-create.html.twig';

Component.extend('sw-rating-create', 'sw-rating-detail', {
    template,

    beforeRouteEnter(to, from, next) {
        if (to.name.includes('sw.rating.create') && !to.params.id) {
            to.params.id = utils.createId();
        }

        next();
    },

    computed: {
        languageStore() {
            return State.getStore('language');
        }
    },

    methods: {
        createdComponent() {
            if (this.languageStore.getCurrentId() !== this.languageStore.systemLanguageId) {
                this.languageStore.setCurrentId(this.languageStore.systemLanguageId);
            }

            if (this.$route.params.id) {
                this.rating = this.ratingStore.create(this.$route.params.id);
            }

            this.$super.createdComponent();
        },

        onSave() {
            const ratingName = this.rating.name;
            const titleSaveSuccess = this.$tc('sw-rating.detail.titleSaveSuccess');
            const messageSaveSuccess = this.$tc('sw-rating.detail.messageSaveSuccess', 0, { name: ratingName });

            this.rating.save().then((rating) => {
                this.createNotificationSuccess({
                    title: titleSaveSuccess,
                    message: messageSaveSuccess
                });

                this.$router.push({ name: 'sw.rating.detail', params: { id: rating.id } });
            });
        }
    }
});
