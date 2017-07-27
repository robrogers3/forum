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
 import collection from '../mixins/collection';
 
 export default {
     components: {Reply, NewReply},
     mixins:[collection],
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
	     console.log('fetch');
	     axios.get(this.url())
		  .then(this.refresh);
	 },
	 refresh({data}) {
	     this.dataSet = data;
             this.items = data.data;
             window.scrollTo(0, 0);
	 },
	 url() {
	     return window.location.pathname + '/replies';
	 },
     },
     computed: {
	 signedIn: function() {
	     return window.Laravel.signedIn;
	 }
     }
 }
</script>
