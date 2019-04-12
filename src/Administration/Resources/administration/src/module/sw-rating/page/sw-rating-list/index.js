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
                property: 'product',
                dataIndex: 'product',
                label: this.$tc('sw-rating.list.columnProduct'),
                routerLink: 'sw.rating.detail',
                primary: true
            },
            {
                property: 'points',
                dataIndex: 'points',
                label: this.$tc('sw-rating.list.columnPoints'),
                align: 'center'
            },
            {
                property: 'user',
                dataIndex: 'externalUser',
                label: this.$tc('sw-rating.list.columnUser')
            },
            {
                property: 'createdAt',
                dataIndex: 'createdAt',
                label: this.$tc('sw-rating.list.columnCreatedAt')
            },
            {
                property: 'status',
                dataIndex: 'status',
                label: this.$tc('sw-rating.list.columnStatus'),
                align: 'center'
            },
            {
                property: 'comment',
                dataIndex: 'comment',
                label: this.$tc('sw-rating.list.columnComment'),
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

            this.criteria.addSorting(Criteria.sort('createdAt', 'DESC'));
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
            this.criteria.setTerm(term);
            this.$route.query.term = term;
            this.$refs.listing.doSearch();
        }
    }
});
