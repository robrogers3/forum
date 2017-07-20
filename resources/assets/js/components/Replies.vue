<template>
    <div>
	<div v-for="(reply, index)  in this.items" :key="reply.id">
	    <reply :data="reply" @deleted="remove(index)"></reply>
	</div>
	
	<new-reply @created="add" v-if="signedIn"></new-reply>
    </div>
</template>

<script>
 import NewReply from './NewReply.vue';
 import Reply from './Reply.vue';
 export default {
     components: {Reply, NewReply},
     data() {
	 return {
	     dataSet: null,
	     items: []
	 }
     },
     created() {
	 this.fetch();
     },
     methods: {
	 fetch() {
	     axios.get(this.url())
		  .then(this.refresh);
	 },
	 refresh({data}) {
	     window.d = data;
	     this.dataSet = data;
	     this.items = data.data;
//	     this.items.push(data.data);
	 },
	 url() {
	     return window.location.pathname + '/replies';
	 },
	 remove(index) {
	     flash('awesome')
	     this.items.splice(index, 1);
	     this.$emit('remove')
	 },
	 add(reply) {
	     console.log('gees');
	     this.items.push(reply);
	     this.$emit('added')
	 }
     },
     computed: {
	 signedIn: function() {
	     return window.Laravel.signedIn;
	 }
     }
 }
</script>
