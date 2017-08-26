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
	     body: this.message,
	     show: false
	 }
     },
     created() {
         if (this.message) {
	     this.flash({message: this.message})
	 }

	 window.events.$on('flash', data => this.flash(data));
     },
     methods: {
	 flash (data) {
	     if (data) {
                 this.body = data.message;
		 this.flashLevel = data.level ? data.level : 'info';
		 this.flashType = data.level == 'success' ? 'Right on man!' :  'Alert!';
             }

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
