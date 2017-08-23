export default {
    methods: {
	reset () {
	    axios.get('/token')
		.then((response) => {
		    window.updateToken(response.data);
		})
		.catch((error) => {
		    flash('unable to reset token');
		});
	}
    }
};
