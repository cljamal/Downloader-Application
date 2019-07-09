/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue'

try {
    window._                = require('lodash');
    window.$                = window.jQuery = require('jquery');
    window.axios            = require('axios');
    window.mixins           = [];
    window.Vue              = Vue;
} catch (e) {}

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if ( token )
{
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
else
{
    console.error('CSRF token not found');
}
