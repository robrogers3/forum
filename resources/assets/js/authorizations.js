let user = window.Laravel.user;
let authorizations = {
    updateReply(reply) {
	return reply.user_id = user.id;
    },
    lockThread() {
	return user.isAdmin;
    }
};

module.exports = authorizations;
