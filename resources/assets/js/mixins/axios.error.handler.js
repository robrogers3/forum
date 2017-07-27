export default {
    methods: {
	handle (error) {
	     console.log('handling it');
	     window.E = error;
	     if (error.response.data) {
		 if (typeof error.response.data == 'string') {
		     flash(error.response.data, 'danger');
		     return;
		 }

		 //validation errors
		 if (error.response.status == 422 && Object.keys(error.response.data)) {
		     let errors = [];
		     Object.keys(error.response.data).forEach(key => {
			 error.response.data[key].forEach(datum => {
			     errors.push(datum);
			 });
		     });
		     
		     return flash(errors.join("\n"), 'danger');
		 }

		 //used to handle a teapot message 418
		 //can be a random exception you throw as say MessagingException
		 if (error.response.status = 418 && error.response.data.message) {
		     return flash(error.response.data.message, 'danger');
		 }
		 
	     }
	     return flash('We could not handle your request','danger');
	}
    }
}
