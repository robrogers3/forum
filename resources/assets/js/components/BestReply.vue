<template>
    <div class="mr-1">
	<i class="fa fa-thumbs-up" aria-hidden="true"  @click="toggleBestPersist" :class="marked_classes"></i>
    </div>
</template>
<script>
 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     mixins: [errorHandler],
     props: ['reply'],
     created () {
	 window.events.$on('unMarkAsBest', id => this.unMarkAsBest(id)); 
     },
     data () {
	 return {
	     is_best: this.reply.isBest
	 }
     },
     computed: {
	 marked_classes () {
	     return this.is_best ? 'is_best' : '';
	 }
     },
     methods: {
	 unMarkAsBest(id) {
	     console.log('equals', id, this.reply.id, id == this.reply.id);
	     if (this.reply.id != id) {
		 console.log('unmarking', id, this.reply.id);
		 this.is_best = false;
	     }
	 },
	 axiosUrl() {
	     return this.is_best ? `/replies/${this.reply.id}/unbest` : `/replies/${this.reply.id}/best`;
	 },
	 toggleBestPersist () {
	     if (!this.authorize('updateReply', this.reply)) return;
	     if (this.is_best) {return};
	     axios.post(this.axiosUrl())
		  .then(response => { this.toggleBest();})
		  .catch(error => this.handle(error));
	 },
	 toggleBest ()  {
	     this.is_best = this.is_best ? false : true;
	     if (this.is_best) {
		 window.events.$emit('bestReplyMarked', this.reply.id); 
	     }
	 }
     }
 }
</script>
<style scoped>
 .fa {
     cursor: pointer;
     font-size: 1.5em;
     color: green;
     opacity: .5;
 }
 .is_best {
     cursor: default;
     opacity: 1;
 }
</style>
