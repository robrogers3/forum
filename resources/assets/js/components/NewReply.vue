<template>
    <div>
        <div v-if="signedIn">
	    <form @submit.prevent="addReply">
            <div class="form-group">
                <textarea name="body"
                          id="body"
                          class="form-control"
                          placeholder="Have something to say?"
                          rows="5"
                          _required
                          v-model="body"></textarea>
            </div>

            <button type="submit"
                    class="btn btn-default"
                    click="addReply">Post</button>
	    </form>
        </div>

        <p class="text-center" v-else>
            Please <a href="/login">sign in</a> to participate in this
            discussion.
        </p>
    </div>
</template>

<script>
 import atwho from '../mixins/mount.atwho.js';
 import errorHandler from '../mixins/axios.error.handler.js';
 export default {
     mixins: [atwho, errorHandler],
     mounted () {
	 this.initAtWho();
     },
     data() {
         return {
             body: ''
         };
     },
     methods: {
         addReply() {
	     axios.post(location.pathname + '/reply', { body: this.body })
	     	  .then(({data}) => {
		      this.body = '';
		      flash('Your reply has been posted.', 'success');
		      this.$emit('created', data);
		  })
	     	  .catch((error) => this.handle(error));
	 }
     }
 }
</script>
