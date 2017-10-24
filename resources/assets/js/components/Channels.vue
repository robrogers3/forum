<template>
    <ul class="dropdown-menu">
	<channel-input @channelEntered="addChannel" v-if="signedIn"></channel-input>
        <li v-for="channel in items">
            <a :href="channelPath(channel)" v-text="channel">foo</a>
        </li>
    </ul>
</template>
<script>
 import ChannelInput from './ChannelInput.vue';
 import errorHandler from '../mixins/axios.error.handler';
 import collection from '../mixins/collection';
 export default {
     props: ['channels'],
     mixins: [errorHandler, collection],
     components: {
	 ChannelInput
     },
     mounted () {
	 this.channels.forEach(channel => this.add(channel.name));
     },
     data () {
	 return {
	     items: []
	 };
     },
     methods: {
	 channelPath (channel) {
	     return `/threads/${channel}`;
	 },
	 addChannel (channelName)  {
	     if (this.items.includes(channelName)) {
		 return flash('Sorry we have already got that channel','warning');
	     }
	     this.persist(channelName);
	 },
	 persist (channelName) {
	     axios.post('/channels', {name: channelName})
		  .then(response => {this.add(channelName);this.sort();})
	     	  .catch(error => this.handle(error));
	 }
     },
     computed: {
	 signedIn: function() {
	     return window.Laravel.signedIn;
	 }
     }
     
 }
</script>
<style scoped>
</style>
