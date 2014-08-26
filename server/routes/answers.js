module.exports = function(router, answers, bayeux){
	router.route('/answers')
		.get(function(req, res){
			var query = answers.find();
			if(req.query.questionId)
				query = answers.find().where('questionId').equals(req.query.questionId);
			query.exec(function(err, items){
				if(err)
					res.send(err);
				res.json(items);
			});
		});
};