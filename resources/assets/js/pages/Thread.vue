<script>
 import Replies from '../components/Replies.vue';
 import SubscribeButton from '../components/SubscribeButton.vue';
 
 export default {
     props: ['data'],
     components: {
	 Replies,
	 SubscribeButton
     },
     data() {
	 return {
	     repliesCount: this.data.replies_count,
	     subscribed: this.data.isSubscribedTo
	 };
     },
     methods: {
	 toggleSubscription () {
	     this.subscribed = !this.subscribed;
	 }
     },
 }
</script>
