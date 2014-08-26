<?php include('header.php'); ?>
<?php if(!isset($_GET['sqid'])){ die('Missing id'); } ?>
<div id="questionContainer">
	<blockquote>
		<p id="questionText"></p>
		<small id="instructionText"></small>
	</blockquote>
	
	<div id="answersContainer" class="form-horizontal">
		<div class="form-group">
			<div class="col-lg-10">
				
			</div>
		</div>
	</div>
	
	<button id="btnSubmit" type="button" class="btn btn-info btn-lg btn-block" onclick="javascript:btnSubmitClick(this);">Submit</button>
</div>

<script>
	var showQuestionId = '';
	var questionId = '';
	var numberOfTries = 0;
	$(function(){
		showQuestionId = '<?php echo $_GET['sqid'] ?>';
		$.ajax({
			url: '<?php echo $restBase ?>/showQuestions/' + showQuestionId,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){ //data is a showQuestion object
				if(data){
					questionId = data.questionId[0];
					loadQuestion();
				}
			},
			error: function(xhr, status, err){
			}
		});
	});
	
	function loadQuestion(){
		$.ajax({
			url: '<?php echo $restBase ?>/questions/' + questionId,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){ //data is a question object
				console.dir(data);
				if(data){
					if(data.text){
						$("#questionText").html(data.text);
						$("#questionContainer").show();
					}
					if(data.instructionText && data.instructionText.length > 0){
						$("#instructionText").html(data.instructionText).show();
					}
					//load bg image
					$("body").css('background-image', 'url("/img/' + data.imgName + '")');
					//depending on the type
					$("#answersContainer").attr('data-answerType', data.answerType);
					if(data.answerType === 'choice'){
						generateChoiceAnswers(data);
					}else if(data.answerType === 'text'){
						generateTextAnswer(data);
					}
					//show the submit button
					$("#btnSubmit").show();
				}
			},
			error: function(xhr, status, err){
				console.error(err);
			}
		});
	}
	
	function generateChoiceAnswers(question){
		if(question.answerChoices && question.answerChoices.length > 0){
			var answersRoot = $("#answersContainer > div.form-group > div.col-lg-10");
			answersRoot.empty();
			for(var i = 0; i < question.answerChoices.length; i++){
				var curr = question.answerChoices[i];
				answersRoot.append(
					$("<div class='radio'></div>")
						.append(
							$("<label></label>").append(
								$("<input type='radio' name='answers' value='" + curr.value + "'></input>")
							).append(curr.text)
						)
				);
			}
		}
	}
	
	function generateTextAnswer(question){
		if(question.answerChoices && question.answerChoices.length == 1){
			var answersRoot = $("#answersContainer > div.form-group > div.col-lg-10");
			answersRoot.empty();
			var answer = question.answerChoices[0];
			var txt = $("<input type='text' class='form-control' id='txtAnswer' autocomplete='off'></input>")
						.attr('data-correctAnswer', answer.value);
			answersRoot.append(txt);
		}else{
			setErrorMessage('Invalid question config');
		}
	}
	
	function btnSubmitClick(btn){
		hideMessages();
		if($("#answersContainer").attr('data-answerType') == 'choice'){
			//make sure there is a choice selected
			var selected = $("input[name=answers]:checked");
			if(selected.length > 0){
				var selectedValue = selected.val();
				//insert record for answer
				insertAnswerRecord(selectedValue);
			}else{
				setErrorMessage("Missing selection");
			}
		}else if($("#answersContainer").attr('data-answerType') == 'text'){
			var submittedAnswer = $("#txtAnswer").val().toLowerCase();
			if(submittedAnswer.length > 0){
				var correctAnswer = $("#txtAnswer").attr('data-correctAnswer').toLowerCase();
				if(correctAnswer == submittedAnswer){
					insertAnswerRecord(submittedAnswer);
				}else{
					$("#txtAnswer").val('');
					if(++numberOfTries >= 3)
						setInfoMessage('Hint: the answer is ' + correctAnswer);
					else
						setErrorMessage('That is incorrect. Try again.');
				}
			}else{
				setErrorMessage('Answer is required');
			}
		}
	}
	
	function insertAnswerRecord(answerValue){
		var objData = { 'showId': cookieShowId, 'questionId': questionId, 'showQuestionId': showQuestionId, 'showUserId': cookieShowUserId, 'answerValue': answerValue };
		console.dir(objData);
		$.ajax({
			url: '<?php echo $restBase ?>/showQuestionAnswers/insert',
			type: 'POST',
			data: JSON.stringify(objData),
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){ //data is a showQuestionAnswers object
				//window.location.href = 'results.php?sqid=' + showQuestionId;
				window.location.href = 'home.php?lastSQID=' + showQuestionId;
			},
			error: function(xhr, status, err){
				console.error(err);
			}
		});
	}
</script>

<style>
	#questionContainer{margin: 0 14px; display: none;}
	#instructionText{ display: none; }
	#answersContainer{margin: 0 auto;}
	#btnSubmit{ display none; margin-top: 20px; }
</style>

<?php include('footer.php'); ?>