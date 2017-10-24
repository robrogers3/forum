<template>
    <span class="flex">
	<button @click="lockThread" v-if="lockable" class="btn btn-danager btn-small">Lock me</button>
	<span class="locked-thread" v-if="this.locked">I'm locked</span>
    </span>
</template>
<script>

 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     mixins: [errorHandler],
     props: ['thread'],
     data() {
	 return {
	     locked: !!this.thread.locked
	 }
     },
     methods: {
	 isAdmin() {
	     if (this.authorize('lockThread')) return true;
	 },
	 lockThread() {
	     return this.locked = true;
	     axios.post('/locked-threads/' + this.thread.slug)
		  .then(data => {
		      this.locked = true;
		      this.$emit('thread-locked');
		  })
		  .catch(error => this.handle(error));
		      
	 }
     },
     computed: {
	 lockable () {
	     return this.isAdmin() && !this.locked;
	 }
     }
 }
</script>
<style scoped>
 button, span {
     margin-top: 10px;
 }
 .locked-thread {
     font-weight: bold;
     color: red;
 }
</style>
