<template>
  <div class="content" v-show=show>
    <div class="flash alert alert-danger">
      {{body}}
    </div>
  </div>
</template>

<script>
    export default {
	props: ['message'],
	data() {
	    return {
		body: '',
		show: false
	    }
	},
	created() {
            if (this.message) {
		this.flash(this.message);
	    }

	    window.events.$on('flash', message => {
		this.flash(message)
	    });
        },
	methods: {
	    flash(message) {
		this.body = message;
		this.show = true;
//		this.hide();
	    },
	    hide() {
		setTimeout(() => {
		    this.show = false;
		}, 3000)
	    }
	}
    }
</script>

<style scoped>
 .flash {
     bottom: 5px;
     right: 5px;
     position: fixed;
     max-width: 500px;
 }
</style>
