import { Component } from 'src/core/shopware';
import template from './sw-product-detail-base.html.twig';

Component.register('sw-product-detail-base', {
    template,

    props: {
        product: {
            type: Object,
            required: true,
            default: {}
        },
        manufacturerStore: {
            type: Object,
            required: true
        },
        taxes: {
            type: Array,
            required: true,
            default: []
        },
        currencies: {
            type: Array,
            required: true,
            default: []
        },
        customFieldSets: {
            type: Array,
            required: true,
            default: []
        },
        ratings: {
            type: Array,
            required: true
        }
    },

    computed: {
        columns() {
            return [
                {
                    property: 'title',
                    dataIndex: 'title',
                    label: 'title',
                    routerLink: 'sw.rating.detail'
                },
                {
                    property: 'points',
                    dataIndex: 'points',
                    label: this.$tc('sw-rating.list.columnPoints'),
                    align: 'center'
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

    methods: {
        priceIsCalculating(value) {
            this.$emit('calculating', value);
        }
    }
});
