<template>
    <button v-text="getText()" class="btn" :class="Classes" @click="toggleSubscription">???</button>
</template>

<script>
 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     mixins: [errorHandler],
     props: ['subscribed'],
     data () {
	 return {
	 }
     },
     methods: {
	 toggleSubscription () {
	     let method = this.subscribed ? 'delete' : 'post';
	     axios[method](window.location.pathname + '/subscribe')
		 .then((data) => {
		     this.$emit('togglesubscription');
		 })
		 .catch(error => {
		     this.handle(error);
		 })
	 },
	 getText () {
	     return this.subscribed  ? 'Unsubscribe'  : 'Subscribe';
	 }
     },
     computed: {
	 Classes () {
	     return this.subscribed  ? 'btn-default'  : 'btn-primary';
	 }
     }
 }
</script>
<style scoped>
 button {
     margin-top: 10px;
 }
</style>
