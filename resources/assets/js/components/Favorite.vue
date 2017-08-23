<template>
  <div>
    <button type="submit" :class="classes" @click="toggle">
      <span class="glyphicon glyphicon-heart"></span>
      <span v-text="favorites_count"></span>
    </button>
  </div>
</template>

<script>
 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     mixins: [errorHandler],
     props: ['reply'],
     data() {
	 return {
	     favorites_count: this.reply.favoritesCount,
	     isFavorited: this.reply.isFavorited
	 }
     },
     methods: {
	 toggle() {
	     return this.isFavorited ? this.unfavorite() : this.favorite();
	 },
	 favorite() {
	     axios.post(this.endpoint)
		  .then(({data}) => {console.log(data);
		      this.favorites_count++;
		      this.isFavorited = true;
		  })
	     	  .catch((error) => this.handle(error));
	 },
	 unfavorite() {
	     axios.delete(this.endpoint)
		  .then(({data}) => {
		      if (this.favorites_count) {
			  this.favorites_count--;
		      }
		      this.isFavorited = false;
		  })
	     	  .catch((error) => this.handle(error));
	 }
     },
     computed: {
	 endpoint() {
	     return `/reply/${this.reply.id}/favorites`;
	 },
	 classes() {
	     return ['btn btn-sm', this.isFavorited ? 'btn-primary' : 'btn-default'];
	 }
     }
 };
</script>
