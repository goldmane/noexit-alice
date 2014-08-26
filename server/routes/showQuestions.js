module.exports = function(router, showQuestions, bayeuxClient){

	var questions = require('../models/questions');

	router.route('/showQuestions/:id')
		.get(function(req, res){
			showQuestions.findById(req.params.id, 
				function(err, item){
					if(err){ console.error(err); res.status(500).end(); }
					else{
						res.json(item);
					}
				}
			);
		});
	
	router.route('/showQuestions/byShow/:showId')
		.get(function(req, res){
			showQuestions.find().where('showId').equals(req.params.showId).sort('sortOrder').exec(
				function(err, items){
					if(err)
						res.send(err);
					res.json(items);
				});
		});
		
	router.route('/showQuestions/getActive/:showId')
		.get(function(req, res){
			showQuestions.find()
				.where('showId').equals(req.params.showId)
				.where('isEnabled').equals(true)
				.exec(
					function(err, items){
						if(err)
							res.send(err);
						res.json(items);
					});
		});
		
	router.route('/showQuestions/admin/activate/:showQuestionId')
		.get(function(req, res){
			showQuestions.findById(req.params.showQuestionId)
				.exec(function(err, docToActivate){
					if(err)
						res.status(500).end();
					else{
						var showId = docToActivate.showId;
						console.log(showId);
						//complete any active
						showQuestions.find()
							.where('showId').equals(showId)
							.where('isEnabled').equals(true)
							.exec(function(err2, docsToComplete){
								if(err2){res.status(500).end();}
								if(docsToComplete){
									if(docsToComplete.length > 0){
										console.log('recs to complete: ' + docsToComplete.length);
										for(var i = 0; i < docsToComplete.length; i++){
											var docToComplete = docsToComplete[i];
											docToComplete.isEnabled = false;
											docToComplete.isDone = true;
											docToComplete.save();
										}
									}
								}
							});
						//activate the new one
						docToActivate.isEnabled = true;
						docToActivate.save();
						res.send();
					}
				});
		});
		
	router.route('/showQuestions/admin/deactivate/:showQuestionId')
		.get(function(req, res){
			showQuestions.findById(req.params.showQuestionId)
				.exec(function(err, showQuestion){
					if(err){ console.error(err); res.status(500).end(); }
					else{
						showQuestion.isEnabled = false;
						showQuestion.isDone = true;
						showQuestion.save(function(err2){
							if(err2){ console.error(err2); res.status(500).end(); }
							else{
								res.send();
							}
						});
					}
				});
		});
		
	router.route('/showQuestions/admin/onActivate/:showQuestionId')
		.get(function(req, res){
			console.log('onActivate');
			bayeuxClient.publish('/questionActivated', {});
			res.send();
		});
}