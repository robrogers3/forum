<template>
    <div>
	<div class="pswp">whatever goes here</div>
    </div>
</template>
<script>

 export default {
     import PhotoSwipe from 'photoswipe'; //if that doesn't work we need to inspect node_modules. 
     created() {
	 this.fetchImages();
     },
     data () {
	 return {
	     items: [],
	     gallery: null
	 };
     },
     methods: {
	 fetchImages() {
	     /**
		your controller could return somethnig like [image, image, image] where each image is an associative array with src, height, width
	      */
	     
	     axios.get('/pathtocontroller')
		  .then((response) => {
		      response.data.forEach((image) => {
			  let item = {src: image.src, height: image.height, width: image.width};
			  this.items.push(item);
			  this.gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
			  this.gallery.init();
		      })
		  })
		  .catch((error) {console.log(error.response.data)}
	 },
	 computed: {
	     count () {
		 return items.length;
	     }
	 }
		      
     }
		      
 };
</script>
