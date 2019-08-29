/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.convertTimeHHMMSS = (val) => {
    let hhmmss = new Date(val * 1000).toISOString().substr(11, 8);
    return hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss;
};

new Vue({
    el: "#audio",
    components: {
        Player: require('./components/Player.vue')
    }
});
