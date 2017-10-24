<script>
 import Replies from '../components/Replies.vue';
 import SubscribeButton from '../components/SubscribeButton.vue';
 import ThreadDisplay from '../components/ThreadDisplay.vue';
 import errorHandler from '../mixins/axios.error.handler.js'; 
 export default {
     props: ['data'],
     mixins: [errorHandler],
     components: {
	 Replies,
	 SubscribeButton,
	 ThreadDisplay,
     },
     created () {
     },
     data() {
	 return {
	     repliesCount: this.data.replies_count,
	     subscribed: this.data.isSubscribedTo,
	     locked: this.data.locked
	 };
     },
     methods: {
	 toggleSubscription () {
	     this.subscribed = !this.subscribed;
	 },

	 toggleLock() {
	     axios[this.locked ? 'delete' : 'post']('/locked-threads/' + this.data.slug)
		  .then(data => {
		      this.locked = !this.locked;
		  })
		  .catch(error => this.handle(error));
	 }
     },
 }
</script>
