export default {
    data () {
	return {
	    items: []
	};
    },
    methods: {
	add(item) {
	    this.items.push(item);
	    this.$emit('added');
	},
	remove(index) {
	    this.items.splice(index, 1);
	    this.$emit('removed');
	},
	has(item) {
	    return this.items.includes(item);
	},
	sort() {
	    this.items.sort();
	}
    }
};
