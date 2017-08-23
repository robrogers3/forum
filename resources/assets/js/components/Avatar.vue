<template>
    <span>
	<img height="40px" :src="image_path">
    </span>
</template>
<script>
 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     props: ['src'],
     mixins: [errorHandler],
     created() {
	 window.events.$on('updateAvatar', (data) => this.update(data))
     },
     data () {
	 return {
	     image_path: this.src,
	 };
     },
     methods: {
	 update(data) {
	     console.log('fetch avatar');
	     this.image_path = data;
	 }
     },
     computed: {
	 _avatarfile () {
	     return this.avatar;
	 }
     }
     
 };
</script>
