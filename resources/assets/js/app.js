
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
require('./fontawesome-all');
window.moment = require('moment');
//                                     Retarded v
window.TurndownService = require('turndown').default;
$(document).ready(function () {
    require('./sections/home/shoutbox');
});