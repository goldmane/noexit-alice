var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var AnswersSchema = new Schema({
	questionId: [Schema.Types.ObjectId],
	text: String
});

module.exports = mongoose.model('Answers', AnswersSchema, 'Answers');