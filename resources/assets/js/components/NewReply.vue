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
 export default {
     mixins: [atwho],
     mounted () {
	 this.initAtWho();
     },
     data() {
         return {
             body: ''
         };
     },
     computed: {
         signedIn() {
             return window.Laravel.signedIn;
         }
     },
     methods: {
         addReply() {
             axios.post(location.pathname + '/reply', { body: this.body })
	     	  .then(({data}) => {
		      this.body = '';
		      
		      flash('Your reply has been posted.');
		      
		      this.$emit('created', data);
		  })
		  .catch(error => {
		      window.E = error;
		      if (typeof error.response.data != 'undefined') {
			  let errors = [];
			  Object.keys(error.response.data).forEach(key => {
			      error.response.data[key].forEach(datum => {
				  errors.push(datum);
			      });});

			  return flash(errors.join(', '), 'danger');
		      }

		      
		      if (typeof error.message != 'undefined') {
			  return flash('really ' + error.message, 'danger');
		      }

		      if (typeof error.response.data != 'undefined' && typeof error.response.data.error != 'undefined') {
			  return flash(error.response.data.error.message, 'danger');
		      }
		      
		      flash('got new clue', 'danger');
                  })

	 }
     }
 }
</script>


