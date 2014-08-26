module.exports = function(router, shows, bayeux, questions, mongoose){
	var showQuestions = require('../models/showQuestions');
	var showUsers = require('../models/showUsers');
	var surveys = require('../models/surveys');
	
	router.route('/shows')
		.get(function(req, res){
			shows.find().where('isEnabled').equals(true).exec(
				function(err, items){
					if(err)
						res.send(err);
					res.json(items);
				});
		});
		
	router.route('/shows/validate/:id/:code')
		.get(function(req, res){
			shows.findById(req.params.id)
				.exec(function(err, item){
					var ret = { isValid: false, showUserId: '' };
					if(err){
					}else{
						if(item.confirmationCode == req.params.code){
							var userId = mongoose.Types.ObjectId();
							var newShowUser = new showUsers({
								showId: req.params.id,
								userId: userId
							});
							newShowUser.save();
							ret.isValid = true;
							ret.showUserId = newShowUser._id;
						}
					}
					res.json(ret);
				});
		});
		
	router.route('/shows/validateCookie/:showId')
		.get(function(req, res){
			var ret = { isActive: false };
			var showId = req.params.showId;
			//check if this show is active
			shows.findById(showId)
				.exec(function(err, item){
					if(err){ console.error(err); res.status(500).end(); }
					else{
						ret.isActive = item.isEnabled;
					}
					res.json(ret);
				});
		});
		
	router.route('/shows/admin')
		.get(function(req, res){
			var ret = {
				shows: [],
				hasEnabledShow: false
			}
			shows.find().where('isEnabled').equals(true).exec(
				function(err, items){
					if(items && items.length > 0){
						ret.hasEnabledShow = true;
						ret.shows.push(items[0]);
						res.json(ret);
					}else{
						shows.find().sort('startDate').exec(
							function(err2, items2){
								ret.shows = items2.slice(0);
								res.json(ret);
							}
						);
					}
				}
			);
		});
	router.route('/shows/admin/start/:id')
		.post(function(req, res){
			shows.findById(req.params.id).exec(
				function(err, doc){
					if(doc && !doc.isEnabled){
						//update show record
						doc.isEnabled = true;
						//doc.confirmationCode = req.params.confirmationCode;
						doc.save();
						//write show question records
						questions.find().sort('sortOrder').exec(function(err, items){
							if(items){
								for(var i = 0; i < items.length; i++){
									var curr = items[i];
									var sq = new showQuestions({
										questionId: curr._id,
										showId: req.params.id,
										sortOrder: curr.sortOrder,
										isEnabled: false,
										isDone: false
									});
									if(curr.answerChoices && curr.answerChoices.length > 0){
										for(var j = 0; j < curr.answerChoices.length; j++){
											sq.answers.push(curr.answerChoices[j].text);
										}
									}
									sq.save();
								}
							}
						});
						//return
						res.send();
					}else{
						res.status(500).end();
					}
				}
			);
		});
		
	router.route('/shows/admin/end/:id')
		.post(function(req, res){
			shows.findById(req.params.id).exec(
				function(err, showToEnd){
					if(showToEnd){
						showToEnd.isEnabled = false;
						showToEnd.isDone = true;
						showToEnd.save(
							function(err, doc){
								if(err){
									console.error(err);
									res.status(500).end();
								}else{
									bayeux.getClient().publish('/showEnded', {
										showId: req.params.id
									});
									res.send();
								}
							}
						);
					}
				}
			);
		});
		
	router.route('/shows/signup')
		.post(function(req, res){
			var emailAddress = req.body.emailAddress;
			var newRec = new surveys({
				emailAddress: emailAddress
			});
			newRec.save(function(err, item){
				if(err){ console.error(err); res.status(500).end(); }
				else{
					res.send();
				}
			});
		});
};