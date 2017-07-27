<template>
    <li class="dropdown" v-if="notifications.length">
	<a _click.prevent="fetch" href="#" class="dropdown-toggle" data-toggle="dropdown">
	    <span class="glyphicon glyphicon-bell"></span>
	</a>
	<ul class="dropdown-menu">
	    <li v-for="notification in notifications">
		<a :href="notification.data.link"
		   v-text="notification.data.message"
		   @click="markAsRead(notification)"
		>
		    Nope
		</a>
	    </li>
	</ul>
    </li>
    
</template>
<script>
 export default {
     props: [],
     data()  {
	 return {
	     notifications: false
	 }
     },
     methods: {
	 markAsRead (notification) {
	     axios.delete("/profiles/" + window.Laravel.user.name + "/notifications/" + notification.id)
	 }
     },
     created () {
	 axios.get("/profiles/" + window.Laravel.user.name + "/notifications")
	      .then(response => {
		  this.notifications = response.data;
	      })
	      .catch((error) => {console.log(error.response)})
     }
 }
</script>
<style scoped>
</style>
