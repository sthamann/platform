import { Component } from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';
import template from './sw-rating-list.html.twig';

Component.register('sw-rating-list', {
    template,

    inject: ['repositoryFactory', 'context'],

    data() {
        return {
            isLoading: false,
            criteria: null,
            repository: null,
            items: null,
            term: this.$route.query ? this.$route.query.term : null
        };
    },

    computed: {
        columns() {
            return [{
                property: 'title',
                dataIndex: 'title',
                label: 'Title',
                routerLink: 'sw.rating.detail',
                allowResize: true,
                primary: true
            },
            {
                property: 'externalUser',
                dataIndex: 'externalUser',
                label: 'External User',
                allowResize: true
            },
            {
                property: 'status',
                dataIndex: 'status',
                label: 'Freigegeben',
                align: 'center'
            }];
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory.create('product_rating');

            this.criteria = new Criteria();

            if (this.term) {
                this.criteria.setTerm(this.term);
            }

            this.isLoading = true;

            this.repository
                .search(this.criteria, this.context)
                .then((result) => {
                    this.items = result;
                    this.isLoading = false;
                });
        },
        onSearch(term) {
            this.criteria.setTerm(term);
            this.$route.query.term = term;
        }
    }
});
