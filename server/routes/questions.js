module.exports = function(router, questions, bayeux){
	router.route('/questions')
		.get(function(req, res){
			questions.find(
				function(err, items){
					if(err)
						res.send(err);
					res.json(items);
				}
			);
		});
	
	router.route('/questions/:id')
		.get(function(req, res){
			questions.findById(req.params.id, 
				function(err, item){
					if(err){ console.error(err); res.status(500).end(); }
					else{
						if(item){
							res.json(item);
						}
					}
				});
		});
};