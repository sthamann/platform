import { Module } from 'src/core/shopware';
import './page/sw-review-list';
import './page/sw-review-detail';

import deDE from './snippet/de_DE.json';
import enGB from './snippet/en_GB.json';

Module.register('sw-review', {
    type: 'core',
    name: 'Reviews',
    description: 'Manages the customer reviews of oroducts',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#57D9A3',
    icon: 'default-symbol-products',
    favicon: 'icon-module-products.png',
    entity: 'product_review',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            components: {
                default: 'sw-review-list'
            },
            path: 'index'
        },
        detail: {
            component: 'sw-review-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'sw.review.index'
            }
        }
    },

    navigation: [{
        path: 'sw.review.index',
        label: 'sw-review.general.mainMenuItemList',
        id: 'sw-review',
        parent: 'sw-product',
        color: '#57D9A3',
        position: 100
    }]
});
