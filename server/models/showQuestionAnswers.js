var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var ShowQuestionAnswersSchema = new Schema({
	showId: [Schema.Types.ObjectId],
	questionId: [Schema.Types.ObjectId],
	showQuestionId: [Schema.Types.ObjectId],
	showUserId: [Schema.Types.ObjectId],
	answerValue: String
});

module.exports = mongoose.model('ShowQuestionAnswers', ShowQuestionAnswersSchema, 'ShowQuestionAnswers');