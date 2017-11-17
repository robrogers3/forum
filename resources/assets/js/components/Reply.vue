<template>
    <div :id="'reply-'+id" class="panel" :class="panelHeaderClass">
	<div class="panel-heading">
	    <div class="level">
		<h5 class="flex">
		    <a :href="'/profiles/'+data.owner.name"
		       v-text="data.owner.name">
		    </a> said <span v-text="ago"></span>
		</h5>
		<best-reply :reply="data"></best-reply>

		<div v-if="signedIn">
		    <favorite :reply="data"></favorite>
		</div>
	    </div>
	</div>

	<div class="panel-body">
	    <div v-show="editing">
		<form @submit.prevent="update">
		    <div class="form-group">
			<textarea :id="'body-'+id" class="form-control" v-model="body" required></textarea>
		    </div>

		    <button class="btn btn-xs btn-primary">Update</button>
		    <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
		</form>
	    </div>

	    <div v-show="!editing" v-html="linkedBody"></div>
	</div>

	<div class="panel-footer level" v-if="authorize('updateReply', reply)">
	    <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
	    <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
	</div>
    </div>
</template>

<script>
 import Favorite from './Favorite.vue';
 import moment from 'moment';
 import atwho from '../mixins/mount.atwho.js';
 import BestReply from './BestReply.vue';
 import errorHandler from '../mixins/axios.error.handler.js';

 export default {
     props: ['data'],
     mixins: [atwho, errorHandler],
     components: { Favorite, BestReply },
     created () {
	 window.events.$on('bestReplyMarked', id => this.unMarkAsBest(id)); 
     },
     mounted () {
	 this.initAtWho();
     },
     data() {
         return {
             editing: false,
             id: this.data.id,
             body: this.data.body,
	     isBest: this.data.isBest,
	     reply: this.data
         };
     },
     computed: {
	 canMarkasBest () {
	     return this.data.isBest == false;
	 },
         ago() {
             return moment(this.data.created_at).fromNow() + '...';
         },
	 linkedBody() {
	     return this.body.replace(/@([A-Za-z\.]+)/g, (match, string) => {
		 return `<a href="/profiles/${string}">${match}</a>`;
	     });
	 },
	 panelHeaderClass() {
	     return this.isBest ? 'panel-success' : 'panel-default';
	 }
     },
     methods: {
         update() {
             axios.patch(
                 '/replies/' + this.data.id, {
                     body: this.body
                 }).then((data) => {
		     this.editing = false;
		     flash('Reply Updated!');
		 })
		  .catch((error) => this.handle(error));
	     
	 },
	 destroy() { //url, {}, () => {}, () => {}
	     axios.delete('/replies/' + this.data.id)
		  .then(response => {
		      this.$emit('deleted', this.data.id);
		  })
		  .catch((error) => this.handle(error));

	 },
	 unMarkAsBest (id) {

	     if (this.data.id == id) {
		 this.isBest = true;
		 this.canMarkAsBest = false;
		 return true;
	     } 
	     this.isBest = false
	     this.canMarkAsBest = true;

	     return true;
	 }
     }
 }
</script>
