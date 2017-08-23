<template>
    <div>
	<a href="#" style="cursor: pointer;" v-if="!show" @click.prevent="show = true">Set Your Avatar</a>
	<form v-if="show" class="form-inline" id="avatar-form" method="POST" :action="route" enctype="multipart/form-data" @submit.prevent="upload">
	    <span v-if="avatar"><img class="avatar" :src="avatar"></span>
	    <label class="btn btn-default btn-file" for="avatar">Browse</label>
	    <input type="file" id="avatar" multiple accept="image/*" name="avatar" style="display:none" _click="prehandle" @change="handleChange">
	    <button v-show="avatar" class="btn btn-primary">Set</button>
	</form>
    </div>
</template>
<script>
 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     props: ['user', 'route'],
     mixins: [errorHandler],
     data () {
	 return {
	     show: false,
	     avatar: '',
	     avatarFile: ''
	 };
     },
     methods: {
	 handleChange (e) {
	     window.E = e;
	     if (e.target.files.length) {
		 this.createAvatar(e.target.files[0]);
	     }
	 },
	 createAvatar (file) {
	     this.avatarFile = file;

	     let  reader = new FileReader();
	     reader.onload = (e) => {
		 this.avatar = reader.result;
		 window.e = e;
	     };
	     reader.readAsDataURL(file);
	 },
	 upload () {
	     var data = new FormData();
             data.append('avatar', this.avatarFile);
	     axios.post(this.route,
			data
	     )
	     	  .then((data) => {
		      this.show = false;
		      window.events.$emit('updateAvatar', this.avatar);
		      flash('avatar uploaded!');
		  })
		  .catch((e) => this.handle(e));
	 }
     },
     computed: {
	 _avatarfile () {
	     return this.avatar;
	 }
     }
     
 };
</script>
