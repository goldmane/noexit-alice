var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var ShowUsersSchema = new Schema({
	showId: [Schema.Types.ObjectId],
	userId: [Schema.Types.ObjectId]
});

module.exports = mongoose.model('ShowUsers', ShowUsersSchema, 'ShowUsers');