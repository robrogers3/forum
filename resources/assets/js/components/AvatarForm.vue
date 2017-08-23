<template>
    <div>
	<a href="#" v-if="!update" @click="update = !update">Set Avatar</a>
	<form v-if="update" class="form-inline" id="_avatar-form" method="POST" enctype="multipart/form-data" @submit.prevent="">
	    <span v-if="avatar"><img class="avatar" :src="avatar"></span>
	    <label class="btn btn-default btn-file" for="avatar">Browse</label>
	    <image-upload name="avatar" @loaded="onLoad"></image-upload>
	</form>
    </div>

</template>
<script>
 //    route="{{route('avatar', $profileUser)}}"
 //    :user="{{$profileUser}}"
 import ImageUpload from './ImageUpload.vue';
 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     mixins: [errorHandler],
     props: ['user'],
     components: {
	 ImageUpload
     },
     data() {
	 return {
	     source: '',
	     update: false,
	     avatar: this.user.avatar_path
	 };
     },
     methods: {
	 onLoad(avatar) {
	     this.persist(avatar.file);
	     this.avatar = avatar.src;
	 },
	 persist (avatar) {
	     let data = new FormData();

	     data.append('avatar', avatar);

	     axios.post(`/api/users/${this.user.name}/avatar`, data)
	     	  .then((data) => {
		      this.show = false;
		      window.events.$emit('updateAvatar', this.avatar);
		      this.update = false;
		      flash('avatar uploaded!');
		  })
		  .catch((e) => this.handle(e));
	 }
     },
     computed: {
	 canUpdate() {
	     return this.authorize(user => user.id == this.user.id);
	 }
     }
 }
</script>
