import { Module } from 'src/core/shopware';
import './page/sw-rating-list';
import './page/sw-rating-detail';

import deDE from './snippet/de_DE.json';
import enGB from './snippet/en_GB.json';

Module.register('sw-rating', {
    type: 'core',
    name: 'Rating',
    description: 'Manages the ratings of the oroducts',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#57D9A3',
    icon: 'default-symbol-products',
    favicon: 'icon-module-products.png',
    entity: 'product_rating',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            components: {
                default: 'sw-rating-list'
            },
            path: 'index'
        },
        detail: {
            component: 'sw-rating-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'sw.rating.index'
            }
        }
    },

    navigation: [{
        path: 'sw.rating.index',
        label: 'sw-rating.general.mainMenuItemList',
        id: 'sw-rating',
        parent: 'sw-product',
        color: '#57D9A3'
    }]
});
