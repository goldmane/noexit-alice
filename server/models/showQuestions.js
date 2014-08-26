var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var ShowQuestionsSchema = new Schema({
	showId: [Schema.Types.ObjectId],
	questionId: [Schema.Types.ObjectId],
	sortOrder: Number,
	isEnabled: Boolean,
	isDone: Boolean,
	answers: []
});

//module.exports = mongoose.model('ShowQuestions', ShowQuestionsSchema, 'ShowQuestions');

var ShowQuestion = mongoose.model('ShowQuestions', ShowQuestionsSchema, 'ShowQuestions');
module.exports = ShowQuestion;