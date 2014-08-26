module.exports = function(router, showQuestionAnswers, bayeux){
	var questions = require('../models/questions');
	var showQuestions = require('../models/showQuestions');
	var mongoose = require('mongoose');
	
	router.route('/showQuestionAnswers/insert')
		.post(function(req, res){
			var newRec = new showQuestionAnswers({
				showId: req.body.showId,
				questionId: req.body.questionId,
				showQuestionId: req.body.showQuestionId,
				showUserId: req.body.showUserId,
				answerValue: req.body.answerValue
			});
			newRec.save(function(err, newItem){
				res.json(newRec);
			});
		});
		
	router.route('/showQuestionAnswers/results/:showQuestionId')
		.get(function(req, res){
			var showQuestionId = req.params.showQuestionId;
			showQuestions.findById(showQuestionId, function(errSQ, showQuestion){
				if(errSQ){ console.error(errSQ); res.status(500).end(); }
				else{
					questions.findById(showQuestion.questionId, function(errQ, question){
						if(errQ){ console.error(errQ); res.status(500).end(); }
						else{
							var agg = [
								{$match: {showQuestionId: new mongoose.Types.ObjectId(showQuestionId)}},
								{
									$group: {
										_id: '$answerValue',
										total: {$sum: 1}
									}
								}
							];
							showQuestionAnswers.aggregate(agg,
								function(err, items){
									if(err){ console.error(err); res.status(500).end(); }
									else{
										items.sort(compare);
										for(var i = 0; i < items.length; i++){
											for(var j = 0; j < question.answerChoices.length; j++){
												if(items[i]._id == question.answerChoices[j].value)
													items[i]['winningAnswer'] = question.answerChoices[j].text;
											}
										}
										res.json(items);
									}
								});
						}
					});
				}
			});
		});
		
	router.route('/showQuestionAnswers/currentColor')
		.get(function(req, res){
			ret = { colorValue: 0 };
			showQuestions.findOne({isEnabled: true}, function(errSQ, showQuestion){
				try{
					console.dir(showQuestion);
					if(errSQ){ console.error(errSQ); res.status(500).end(); }
					else if(showQuestion){
						showQuestionAnswers.find().where('showQuestionId').equals(showQuestion._id)
							.exec(function(err, answers){
								console.dir(answers);
								if(err){ console.error(err); res.status(500).end(); }
								else if(answers && answers.length > 0){
									console.log(showQuestion._id);
									var agg = [
										{$match: {showQuestionId: new mongoose.Types.ObjectId(String(showQuestion._id))}},
										{
											$group: {
												_id: '$answerValue',
												total: {$sum: 1}
											}
										}
									];
									showQuestionAnswers.aggregate(agg,
										function(err, items){
											if(err){ console.error(err); res.status(500).end(); }
											else{
												if(items.length > 0){
													console.log('here');
													items.sort(compare);
													console.dir(items);
													res.json(parseInt(items[0]._id));
												}else{
													res.json(0);
												}
											}
										});
								}
								else{ res.json(9); }
							});
					}else{ res.json(0); }
				}catch(exc){ console.dir(exc); res.json(0); }
			});
		});
		
	function compare(a,b){
		if(a.total < b.total)
			return 1;
		if(a.total > b.total)
			return -1;
		return 0;
	}
};