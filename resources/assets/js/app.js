
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.events = new Vue({
});

window.flash = function (message, level = 'success') {
    let data  =  {message: message, level: level};
    window.events.$emit('flash', data);
};


Vue.component('flash', require('./components/Flash.vue'));
//Vue.component('reply', require('./components/Reply.vue'));
Vue.component('favorite', require('./components/Favorite.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));
Vue.component('user-notifications', require('./components/UserNotifications.vue'));
Vue.component('token-reset', require('./components/TokenReset.vue'));
Vue.component('set-avatar', require('./components/SetAvatar.vue'));
Vue.component('avatar-form', require('./components/AvatarForm.vue'));
Vue.component('avatar', require('./components/Avatar.vue'));
const app = new Vue({
    el: '#app'
});

