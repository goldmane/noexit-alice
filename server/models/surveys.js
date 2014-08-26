var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var SurveySchema = new Schema({
	emailAddress: String
});

module.exports = mongoose.model('Surveys', SurveySchema, 'Surveys');