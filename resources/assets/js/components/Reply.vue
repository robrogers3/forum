<script>
 import Favorite from './Favorite.vue';
 export default {
     props: ['attributes'],
     created() {
     },
     data() {
	 return {
	     body: this.attributes.body,
	     id: this.attributes.id,
	     favorites: this.attributes.favorites,
	     editing: false
	 }
     },
     methods: {
	 update() {
	     axios.patch(`/reply/${this.id}`, {body: this.body});
	     this.editing = false
	 },
	 destroy() {
	     axios.delete(`/reply/${this.id}`);
	     $(this.$el).fadeOut(300, () => {flash('reply deleted');});
	 }
     }
 }
</script>
