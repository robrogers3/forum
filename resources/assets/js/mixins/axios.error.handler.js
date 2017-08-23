export default {
    methods: {
	handle (error) {
	    console.log('handling it');
	    window.E = error;
	    if (error.response.data) {
		if (error.response.status == 451) {
		    return events.$emit('resetToken');
		}
		if (typeof error.response.data == 'string') {
		    return flash(error.response.data, 'danger');
		}

		//validation errors
		if (error.response.status == 422 && error.response.data.length) {
		    let errors = [];

		    error.response.data.forEach(datum => {
			errors.push(datum);
		    });
		    
		    return flash(errors.join("\n"), 'danger');
		}

		//used to handle a teapot message 418
		//can be a random exception you throw as say MessagingException
		if (error.response.status == 418 && error.response.data.message) {
		    return flash(error.response.data.massage, 'danger');
		}
		
	    }
	    return flash('We could not handle your request','danger');
	}
    }
}
