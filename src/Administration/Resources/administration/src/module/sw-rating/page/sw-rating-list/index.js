import { Component } from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';
import template from './sw-rating-list.html.twig';
import './sw-rating-listing.scss';

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
                property: 'product',
                dataIndex: 'product',
                label: 'Product',
                routerLink: 'sw.rating.detail',
                allowResize: true,
                primary: true
            },
            {
                property: 'points',
                dataIndex: 'points',
                label: 'Bewertung',
                align: 'center'
            },
            {
                property: 'user',
                dataIndex: 'user',
                label: 'User',
                allowResize: true
            },
            {
                property: 'createdAt',
                dataIndex: 'createdAt',
                label: 'Created at'
            },
            {
                property: 'status',
                dataIndex: 'status',
                label: 'Status',
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

            this.criteria.addAssociation('customer');
            this.criteria.addAssociation('product');

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
            console.log('search for : ', term);
            this.criteria.setTerm(term);
            this.$route.query.term = term;
            this.$refs.listing.doSearch();
        }
    }
});
