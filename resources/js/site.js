/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Clipboard from "clipboard";
import '@claviska/jquery-minicolors';

if ($('#player_builder').length > 0) {
    new Vue({
        el: "#player_builder",
        components: {
            PlayerBuilder: require('./components/PlayerBuilder.vue')
        }
    });
}