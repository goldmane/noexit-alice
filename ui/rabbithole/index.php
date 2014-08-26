<?php include('../header.php'); ?>

<div class="panel-group" id="accordion">
	<!-- SHOWS -->
	<div class="panel panel-warning">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#sectionShows">Shows</a>
			</h4>
		</div>
		<div id="sectionShows" class="panel-collapse collapse in">
			<div class="panel-body">
				<div id="showsContainer"></div>
				<div id="showsContainerHasActive">
					There is already an active show.
					<p class="code"></p>
				</div>
			</div>
		</div>
	</div>
	
	<!-- QUESTIONS -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#sectionQuestions">Questions</a>
			</h4>
		</div>
		<div id="sectionQuestions" class="panel-collapse collapse">
			<div class="panel-body">
				<table id="questionsContainer" class="table">
				</table>
			</div>
		</div>
	</div>
	
	<!-- END SHOW BUTTON -->
	<div id="endShowContainer">
		<button id="btnEndShow" type="button" class="btn btn-lg btn-block btn-danger" onclick="javascript:endShow(this);">End Show</button>
		<p id="showEndMessage">
			You have ended the show.
		</p>
	</div>
	
</div>

<style>
	#showsContainerHasActive{ display: none; }
	#questionsContainer button span.glyphicon{display: none; color: green; margin-left: 10px;}
		#questionsContainer td{ padding-right: 10px; }
	#endShowContainer{ display: none; margin-top: 15px; }
	#showEndMessage{ display: none; }
</style>

<script>
	$(function(){
		//load shows
		loadShows();
	});
	
	function loadShows(){
		$.ajax({
			url: '<?php echo $restBase ?>/shows/admin',
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				if(!data.hasEnabledShow){
					if(data && data.shows){
						activeShow = {};
						for(var idx = 0; idx < data.shows.length; idx++){
							var curr = data.shows[idx];
							if(!curr.isDone){
								var startDate = new XDate(curr.startDate);
								var formattedStartDate = startDate.toString('dddd MMM d yyyy, h:mm TT');
								if(curr.isTest != undefined && curr.isTest != 'undefined' && curr.isTest == true)
									formattedStartDate = 'TEST TEST TEST';
								$("#showsContainer").append(
									$('<button class="btn btn-block btn-lg" type="button"></button>')
										.attr('data-showId', curr._id)
										.html(formattedStartDate)
										.click(function(){
											if(curr.isEnabled == false){
												if(confirm('Start this show?')){
													$("#showsContainer button").addClass('disabled');
													startShow($(this).attr('data-showId'));
												}
											}else{
												$(this).addClass('disabled');
											}
										})
								);
							}
						}
					}
				}else{
					$("#showsContainerHasActive").find("p.code").html("Code: " + data.shows[0].confirmationCode);
					$("#showsContainerHasActive").show();
					$("#sectionShows").collapse('hide');
					loadQuestions(data.shows[0]._id);
				}
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function startShow(id){
		$.ajax({
			url: '<?php echo $restBase ?>/shows/admin/start/' + id,
			type: 'POST',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				$("#sectionShows").collapse('hide');
				loadQuestions(id);
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function loadQuestions(showId){
		$.ajax({
			url: '<?php echo $restBase ?>/showQuestions/byShow/' + showId,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				if(data && data.length > 0){
					$("#endShowContainer").show();
					$("#btnEndShow").attr('data-showId', showId);
					$("#questionsContainer").empty();
					for(var i = 0; i < data.length; i++){
						var curr = data[i];
						var qb = $("<button type='btn' class='btn btn-error'>Question " + (i+1) + "</button>")
							.attr('data-showQuestionId', curr._id)
							.append($("<span class='glyphicon glyphicon-ok'></span>"));
						var db = $("<button type='btn' class='btn btn-error'>End Question</button>")
							.attr('data-showQuestionId', curr._id).addClass('decisionButton')
							.append($("<span class='glyphicon glyphicon-ok'></span>"));
						if(curr.isDone){
							qb.addClass('disabled');
							qb.find("span.glyphicon").show();
							db.addClass('disabled');
							db.find("span.glyphicon").show();
						}
						else{
							if(curr.isEnabled){
								qb.addClass('btn-success');
								db.addClass('btn-warning').click(function(event){
									if(confirm('End this question?')){
										deactivateQuestion($(this).attr('data-showQuestionId'), curr.showId);
									}
								});
							}
							else{
								qb.click(function(event){
									if(confirm('Activate this question?'))
										activateQuestion($(this).attr('data-showQuestionId'), curr.showId);
								});
								db.addClass('disabled');
							}
						}
						$("#questionsContainer").append(
							$("<tr></tr>").append(
								$("<td></td>").append(qb)
							).append(
								$("<td></td>").append(db)
							)
						);
					}
					$("#sectionQuestions").collapse('show');
				}else{
					setTimeout(function(){
						loadQuestions(showId);
					}, 2000);
				}
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function activateQuestion(showQuestionId, showId){
		console.log('activate');
		$.ajax({
			url: '<?php echo $restBase ?>/showQuestions/admin/activate/' + showQuestionId,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				console.log('activate success');
				publishQuestionActivate(showQuestionId, showId);
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function deactivateQuestion(showQuestionId, showId){
		$.ajax({
			url: '<?php echo $restBase ?>/showQuestions/admin/deactivate/' + showQuestionId,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				alertResults(showQuestionId, showId);
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function alertResults(showQuestionId, showId){
		$.ajax({
			url: '<?php echo $restBase ?>/showQuestionAnswers/results/' + showQuestionId,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				var colors = ["red","green","blue","orange"];
				var winningColor = colors[0];
				var winningAnswer = '';
				if(data && data.length > 0){
					var winningVal = data[0]._id;
					winningColor = colors[winningVal-1];
					winningAnswer = data[0].winningAnswer;
				}
				alert('The winning answer is: ' + winningAnswer + '\nThe winning color is: ' + winningColor);
				loadQuestions(showId);
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function publishQuestionActivate(showQuestionId, showId){
		$.ajax({
			url: '<?php echo $restBase ?>/showQuestions/admin/onActivate/' + showQuestionId,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				loadQuestions(showId);
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function endShow(btn){
		var showId = $(btn).attr('data-showId');
		if(confirm('End this show?')){
			$.ajax({
				url: '<?php echo $restBase ?>/shows/admin/end/' + showId,
				type: 'POST',
				crossDomain: true,
				contentType: 'application/json; charset=utf-8',
				success: function(data, status, xhr){
					$(btn).hide();
					$("#showEndMessage").show();
					$("div.panel").hide();
				},
				error: function(xhr, status, err){
					console.error(status);
					console.dir(err);
				}
			});
		}
	}
</script>

<?php include('../footer.php'); ?>