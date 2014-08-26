var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var QuestionsSchema = new Schema({
	text: String,
	instructionText: String,
	sortOrder: Number,
	answerChoices: []
});

module.exports = mongoose.model('Questions', QuestionsSchema, 'Questions');