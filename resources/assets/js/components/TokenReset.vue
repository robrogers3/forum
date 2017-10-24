<template>
    <form v-if="show" @submit.prevent="resetToken" class="form-inline" v-cloak style="margin-bottom: 20px;">
    <legend>Login Form</legend>
    <input class="form-control" placeholder="email" v-model="email">
    <input type="password" class="form-control" placeholder="password" v-model="password">
    <button class="btn btn-default">Login</button>
</form>
</template>
<script>
 import handler from '../mixins/axios.error.handler.js';
 export default {
     mixins: [handler],
     created: function() {
	 events.$on('resetToken', this.showForm);
     },
     data () {
	 return {
	     email: '',
	     password: '',
	     show: false,
	 };
     },
     methods: {
	 showForm () {
	     flash('Please login to continue.', 'warning');
	     this.show = true;
	 },
	 resetToken () {

	     axios.post('/resetToken', {email: this.email, password: this.password})
		  .then(({data}) => {
		      console.log('reset toke', data);
		      this.show = false;
		      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = data;
		  })
		  .catch((error) => {
		      this.handle(error)
		  });
	 }
     }
 }
</script>
