<template>
    <div>
        <div class="form-group">
            <textarea class="form-control" name="body" placeholder="What have you got to say?" v-model="body" required>
            </textarea>
        </div>
        <button type="submit" class="btn btn-primary" @click="addReply">Submit</button>
    </div>
</template>
<script>
 export default {
     data() {
	 return {
	     body: ''
	 }
     },
     methods: {
	 addReply() {
	     if (!this.body.trim()) {
		 flash('sorry')
		 return;
	     }
	     let self = this;
	     axios.post(this.endpoint, {body: this.body})
	     	  .then(({data}) => {
		      this.body = '';
		      flash('reply added');
		      this.$emit('created', data);	     
		      console.log('adding reply');
		  })
		  .catch(function (error) {
			  if (error.response) {
			      // The request was made and the server responded with a status code
			      // that falls out of the range of 2xx
			      console.log(error.response);
			      flash(error.response.data.flash);
			      console.log(error.response.status);
//			      console.log(error.response.headers);
			  } else if (error.request) {
			      // The request was made but no response was received
			      // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
			      // http.ClientRequest in node.js
			      console.log(error.request);
			  } else {
			      // Something happened in setting up the request that triggered an Error
			      console.log('Error', error.message);
			  }
			  //console.log(error.config);
		      });
	 }
     },
     computed: {
	 endpoint() {
	     ///threads/{channel}/{thread}/replies
	     return window.location.pathname + '/replies';
	 }
     }
 };
</script>
