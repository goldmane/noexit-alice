var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var ShowsSchema = new Schema({
	startDate: Date,
	isEnabled: Boolean,
	isDone: Boolean,
	confirmationCode: Number,
	isTest: Boolean
});

module.exports = mongoose.model('Shows', ShowsSchema, 'Shows');