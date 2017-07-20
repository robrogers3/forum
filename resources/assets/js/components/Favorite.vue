<template>
  <div>
    <button type="submit" :class="classes" @click="toggle">
      <span class="glyphicon glyphicon-heart"></span>
      <span v-text="favorites_count"></span>
    </button>
  </div>
</template>

<script>
export default {
    props: ['reply'],
    data() {
	return {
	    favorites_count: this.reply.favorites_count,
	    isFavorited: this.reply.isFavorited
	}
    },
    methods: {
	toggle() {
	    return this.isFavorited ? this.unfavorite() : this.favorite();
	},
	favorite() {
	    axios.post(this.endpoint);
	    this.favorites_count++;
	    this.isFavorited = true;
	},
	unfavorite() {
	    axios.delete(this.endpoint);
	    this.favorites_count--;
	    this.isFavorited = false;
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
