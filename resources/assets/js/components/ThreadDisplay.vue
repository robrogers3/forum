<template>
    <div>
	<div v-if="!editing" class="panel panel-info">
	    <div class="panel-heading">
		<div class="level btw">
		    <avatar
			class="mr-1"
			       :src="creator.avatar_path"
		    ></avatar>

		    <h4 class="flex" v-text="title">title??</h4>
		    <div v-if="canUpdate()">
			<button @click="editing = !editing" type="submit" class="btn btn-sm btn-primary">Edit</button>	
			<button @click="deleteThread" type="submit" class="btn btn-sm btn-danger">Delete</button>
		    </div>
		</div>
		<div>Posted By {{creator.name}} {{created_at}} ago in channel {{channelName}}</div>
	    </div>
	    <div class="panel-body">
		<div v-text="body"></div>
	    </div>
	</div>
	<div v-if="editing" class="panel panel-info">
	    <div class="panel-heading level">
		<avatar
		    class="mr-1"
		    :src="creator.avatar_path"
		></avatar>
		<h4>Edit your post</h4>
	    </div>
	    <div class="panel-body">
		<form v-if="editing" @submit.prevent="updateThread" class="thread-form">
		    <channel-select @changed="updateChannelName" :channels="channels" :old="channelName"></channel-select>
		    <div class="form-group">
			<label for="title">Title</label>
			<input class="form-control" id="title" v-model="title" name="title" value="" type="text">
		    </div>
		    <div class="form-group">
			<label for="title">Body</label>
			<textarea class="form-control" name="body" v-model="body">nothing here?</textarea>
		    </div>
		    <button class="btn btn-primary">Update</button>
		</form>
	    </div>
	</div>
    </div>
</template>

<script>
 import errorHandler from '../mixins/axios.error.handler.js';
 import moment from 'moment';
 import ChannelSelect from './ChannelSelect.vue';
 export default {
     props: ['attributes', 'channels'],
     mixins: [errorHandler],
     components: {ChannelSelect},
     data () {
	 return {
	     body: this.attributes.body,
	     title: this.attributes.title,
	     channel: this.attributes.channel,
	     channelName: this.attributes.channel.name,
	     metaData: this.attributes,
	     creator: this.attributes.creator,
	     editing: false
	 };
     },
     methods: {
	 updateChannelName (name) {
	     this.channelName = name;
	 },
	 deleteThread () {
	     axios.delete(this.metaData.path)
		  .then(response => {
		      window.location.href = response.data.path;
		  })
		  .catch(error => this.handle(error));
	 },
	 updateThread() {
	     axios.patch(this.metaData.path, {
		 title: this.title,
		 body: this.body,
		 channel: this.channelName
	     })
		  .then(response => {
		      this.editing = false;
		      if (this.channeName != this.channel.name) {
			  window.history.pushState({change: 'channel'}, 'not used', window.location.pathname.replace(this.channel.name, this.channelName));
		      }
		  })
		  .catch(error => this.handle(error));
	     
	 },
         signedIn() {
             return window.Laravel.signedIn;
         },
         canUpdate() {
	     return this.authorize(user => this.creator.id == user.id);
         }
     },
     computed: {
	 created_at () {
	     let now = moment();
	     let then = moment(this.metaData.created_at);
	     let diff = now - then;
	     return moment.duration(diff).humanize();
	 }
     }
 }
</script>
<style scoped>
 .thread-form {
     margin: .5em;
     padding: .5em;
 }
</style>
