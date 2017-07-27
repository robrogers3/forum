<template>
  <div class="content" v-show=show>
      <div class="flash alert" :class="flashClass">
	  <strong v-text="flashType"></strong>
	  <span v-text="body"></span>
    </div>
  </div>
</template>

<script>
 export default {
     props: ['message'],
     data() {
	 return {
	     flashLevel: 'nothing',
	     flashType: '',
	     body: '',
	     show: false
	 }
     },
     created() {
         if (this.message) {
	     flash(message)
	 }
	 window.events.$on('flash', data => this.flash(data));
     },
     methods: {
	 flash (data) {
	     console.log('flash data', data, data.message, data.level);
	     this.body = data.message
	     this.flashType = data.level == 'success' ? 'Right on man!' : 'Sorry dude!';
	     this.flashLevel = data.level ? data.level : 'success';
	     this.show = true;
	     this.hide();
	 },
	 hide() {
	     setTimeout(() => {
		 this.show = false;
	     }, 5000)
	 }
     },
     computed: {
	 flashClass () {
	     return 'alert-' + this.flashLevel;
	 }
     }
 }
</script>
