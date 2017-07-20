<template>
    <div :id="`reply-${id}`" class="panel panel-default">
	<div class="panel-heading">
            <div class="level">
		<h5 class="flex">
		    <a :href="`/profiles/${reply.owner.name}`" v-text="reply.owner.name"></a>
		    <span v-text="ago"></span>
		</h5>
		<div v-show="signedIn">
		<favorite :reply="data"></favorite>
		</div>
            </div>
	</div>
	<div class="panel-body">
	    <div v-show="editing">
		<div class="form-group">
		    <textarea class="form-control" v-model="body"></textarea>
		</div>
		<div v-if="canUpdate">
		    <button class="btn btn-xs btn-primary mr-1" @click="update">Update</button>
		    <button class="btn btn-xs btn-default" @click="editing=false">Cancel</button>
		</div>
	    </div>
	    <div v-text="body" v-show="!editing">
	    </div>
	</div>
	<div class="panel-footer level" v-if="canUpdate">
	    <button class="btn btn-xs btn-primary mr-1" @click="editing=true">Edit</button>
	    <button class="btn btn-xs btn-danger" @click="destroy">Delete</button>
	</div>
    </div>
</template>
<script>
 import Favorite from './Favorite.vue';
 import moment from 'moment';
 export default {
     props: ['data'],
     created() {
     },
     data() {
	 return {
	     reply: this.data,
	     body: this.data.body,
	     id: this.data.id,
	     favorites: this.data.favorites,
	     editing: false,
	     signedIn: window.Laravel.signedIn
	 }
     },
     methods: {
	 update() {
	     axios.patch(`/reply/${this.id}`, {body: this.body})
		  .then(function(data) {
		      console.log('then');
		  })
		  .catch(function(data) {
		      console.log('catech');
		  });
	     this.editing = false
	 },
	 destroy() {
	     let id = this.id;
	     let self = this;
	     axios.delete(`/reply/${this.id}`).then(function(data) {
		 console.log('id is ', id);
		 self.$emit('deleted', self.id);
	     }).catch(function(data) {
		 console.log('catch destroy');
		 //console.log(data);
	     });
	 }
     },
     computed: {
	 canUpdate() {
	     return this.authorize(user => this.data.user_id == user.id);
	 },
	 ago() {
	     return moment(new Date(this.data.created_at)).fromNow() + ' ago';
	 }
     }
 }
</script>
