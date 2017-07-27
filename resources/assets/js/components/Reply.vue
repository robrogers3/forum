<template>
    <div :id="'reply-'+id" class="panel panel-default">
	<div class="panel-heading">
	    <div class="level">
		<h5 class="flex">
		    <a :href="'/profiles/'+data.owner.name"
		       v-text="data.owner.name">
		    </a> said <span v-text="ago"></span>
		</h5>

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

	<div class="panel-footer level" v-if="canUpdate">
	    <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
	    <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
	</div>
    </div>
</template>

<script>
 import Favorite from './Favorite.vue';
 import moment from 'moment';
 import atwho from '../mixins/mount.atwho.js';
 import errorHandler from '../mixins/axios.error.handler.js';

 export default {
     props: ['data'],
     mixins: [atwho, errorHandler],
     components: { Favorite },
     mounted () {
	 this.initAtWho();
     },
     data() {
         return {
             editing: false,
             id: this.data.id,
             body: this.data.body
         };
     },
     computed: {
         ago() {
             return moment(this.data.created_at).fromNow() + '...';
         },

         signedIn() {
             return window.Laravel.signedIn;
         },
         canUpdate() {
             return this.authorize(user => this.data.user_id == user.id);
         },
	 linkedBody() {
	     return this.body.replace(/@([A-Za-z\.]+)/g, (match, string) => {
		 return `<a href="/profiles/${string}">${match}</a>`;
	     });
	 }
     },
     methods: {
         update() {
             axios.patch(
                 '/repliesXX/' + this.data.id, {
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

	 }
     }
 }
</script>
