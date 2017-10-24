<template>
    <ul class="pagination" v-show="prevUrl || nextUrl">
	<li :class="hasPrevUrl">
	    <a href="#" @click.prevent="decrementPage" aria-label="Previous">
		<span aria-hidden="true">&laquo; Previous</span>
	    </a>
	</li>
	<li :class="hasNextUrl">
	    <a @click.prevent="incrementPage" href="#" aria-label="Next">
		<span aria-hidden="true">Next &raquo;</span>
	    </a>
	</li>
    </ul>
</template>
<script>
 export default {
     props: ['dataSet'],
     data () {
	 return {
	     all: this.dataSet,
	     prevUrl: null,
	     nextUrl: null,
	     page: 1,
	     lastPage: 1
	 }
     },
     methods: {
	 incrementPage() {
	     if (this.page < this.lastPage) {
		 this.page++;
	     }
	 },
	 decrementPage() {
	     if (this.page > 1)
		 this.page--;
	 },
	 broadCast() {
	     return this.$emit('page', this.page);
	 },
	 updateUrl () {
	     history.pushState(null, null, '?page=' + this.page);
	 }
     },
     computed: {
	 hasPrevUrl () {
	     return !! this.prevUrl ? '' : 'disabled';
	 },
	 hasNextUrl () {
	     return !! this.nextUrl ? '' : 'disabled';
	 }
     },
     watch: {
	 dataSet () {
	     this.prevUrl =  this.dataSet.prev_page_url;
	     this.nextUrl =  this.dataSet.next_page_url;
	     this.currentPage = this.dataSet.current_page;
	     this.page = this.dataSet.current_page;
	     this.lastPage = this.dataSet.last_page;
	 },
	 page () {
	     return this.broadCast().updateUrl();
	 }
     }
 }
</script>
<style scoped>
</style>
