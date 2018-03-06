
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('graph-view', require('./components/Graph.vue'));

const app = new Vue({
    el: '#app',

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
        this.loadData();

        setInterval(function () {
            this.loadData();
        }.bind(this),5000); 
    },

    methods: {
        loadData: function () {
            $.get('/posts/75/snapshots/latest/all', function (response) {
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
