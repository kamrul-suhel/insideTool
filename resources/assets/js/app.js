
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Chart from 'chart.js';
import ChartAnnotation from 'chartjs-plugin-annotation';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('graph-view', require('./components/Graph.vue'));
Vue.component('graph-metric', require('./components/GraphMetric.vue'));
Vue.component('index-metrics', require('./components/IndexMetrics.vue'));

const app = new Vue({
    el: '#app',
    filters: {
        number_format(number) {
            return number.toLocaleString('en');
        }
    },
    data: {
        metrics: {
            likes: 0,
            shares: 0,
            comments: 0,
            loves: 0,
            wows: 0,
            hahas: 0,
            sads: 0,
            angrys: 0
        }
    },

    mounted: function () {
        if ($('#app').data('page') == 'show') {
            this.loadData();

            setInterval(function () {
                this.loadData();
            }.bind(this),5000);
        }
    },

    methods: {
        loadData: function () {
            $.get('/posts/' + $('#app').data('post-id') + '/snapshots/latest/all', function (response) {
                this.metrics = response;
            }.bind(this));
        }
    },

    events:{
        'userUnauthenticated': function(){
            $('#session-expired').modal();
        }
    }
});
