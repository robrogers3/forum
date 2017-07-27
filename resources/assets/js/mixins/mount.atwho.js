import 'jquery.caret';
import 'at.js';

export default {
    methods: {
	initAtWho () {
	    $('textarea').atwho({
		at: "@",
		delay: 750,
		callbacks: {
		    remoteFilter: function(query, callback) {
			if (query == '') return;
			axios.get('/api/users?name='+query)
			    .then(function({data}){
				callback(data);
			    });
		    }
		}
	    });
	}
    }
}
