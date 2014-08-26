module.exports = function(router){

	var showQuestions = require('../models/showQuestions');
	var showQuestionAnswers = require('../models/showQuestionAnswers');
	var shows = require('../models/shows');
	
	router.route('/special/reset')
		.get(function(req, res){
			shows.findById('53ec2169199eccc436000016',
				function(err, item){
					if(err) { console.error(err); res.status(500).end(); }
					item.isDone = false;
					item.isEnabled = false;
					item.save();
				});
			showQuestions.remove({
				showId: '53ec2169199eccc436000016'
			}, function(err){
				if(err) { console.error(err); res.status(500).end(); }
			});
			showQuestionAnswers.remove({
				showId: '53ec2169199eccc436000016'
			}, function(err){
				if(err) { console.error(err); res.status(500).end(); }
			});
			res.send();
		});
	
	router.route('/special/clearAnswers')
		.get(function(req, res){
			showQuestionAnswers.find().where('showId').equals('53ec2169199eccc436000016').remove().exec();
			res.send();
		});
		
	router.route('/test/currentColor/:number')
		.get(function(req, res){
			var number = parseInt(req.params.number);
			res.json(number);
		});
}