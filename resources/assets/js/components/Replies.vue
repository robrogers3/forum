<template>
    <div>
	<paginator :dataSet="dataSet" @page="fetch"></paginator>
	<div v-for="(reply, index)  in this.items" :key="reply.id">
	    <reply :data="reply" @deleted="remove(index)"></reply>
	</div>
	<new-reply @created="add" v-if="!$parent.locked"></new-reply>
	<p v-else>Sorry thread is locked</p>
    </div>
</template>

<script>
 import NewReply from './NewReply.vue';
 import Reply from './Reply.vue';
 import Paginator from './Paginator.vue';
 import collection from '../mixins/collection';
 
 export default {
     components: {Reply, NewReply, Paginator},
     mixins:[collection],
     data() {
	 return {
	     dataSet: null
	 }
     },
     created() {
	 this.fetch();
	 window.events.$on('bestReplyMarked', (data) => this.unMarkOthers(data))
     },
     methods: {
	 fetch(page) {
	     axios.get(this.url(page))
		  .then(this.refresh);
	 },
	 refresh({data}) {
	     this.dataSet = data;
             this.items = data.data;
             window.scrollTo(0, 0);
	 },
	 url(page) {
	     if (!page) {
		 page = location.search.match(/page=(\d+)/) &&  location.search.match(/page=(\d+)/).length ? location.search.match(/page=(\d+)/)[1] : 1
	     }
	     return location.pathname + '/replies/?page=' + page;
	 },
	 unMarkOthers(id) {
	     window.events.$emit('unMarkAsBest', id)
	 }
     },
     computed: {
	 signedIn: function() {
	     return window.Laravel.signedIn;
	 }
     }
 }
</script>
