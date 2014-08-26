<?php include('header.php'); ?>
	<?php if(!isset($_COOKIE[$Cookie_ShowId])){ die('ShowId not set'); } ?>
	
	<div style="margin: 0 14px;">
		<h1>The Journey</h1>
		<h4>Active question is highlighted...click to proceed</h4>
		<div id="questionsContainer">
		</div>
	</div>
	
	<script>
		var lastSQID = "<?php if(isset($_GET['lastSQID'])){ echo $_GET['lastSQID']; } ?>";
		function checkForActiveQuestion(){
			$.ajax({
				url: '<?php echo $restBase ?>/showQuestions/getActive/' + cookieShowId,
				type: 'GET',
				crossDomain: true,
				contentType: 'application/json; charset=utf-8',
				success: function(data, status, xhr){
					if(data && data.length > 0){
						if(data.length == 1){
							var btn = $("button[data-showQuestionId='"+data[0]._id+"']");
							if(lastSQID.length == 0 || lastSQID != data[0]._id){
								btn.removeClass('disabled').removeClass('btn-default').addClass('btn-success')
										.click(function(event){
											window.location.href = 'question.php?sqid=' + $(this).attr('data-showQuestionId');
										});
							}
						}else{
							setErrorMessage('Invalid number of active questions.');
						}
					}
					setTimeout(function(){
						loadQuestions();
					}, 5000);
				},
				error: function(xhr, status, err){
					console.error(status);
					console.dir(err);
				}
			});
		}
		
		function loadQuestions(){
			$.ajax({
				url: '<?php echo $restBase ?>/showQuestions/byShow/' + cookieShowId,
				type: 'GET',
				crossDomain: true,
				contentType: 'application/json; charset=utf-8',
				success: function(data, status, xhr){
					if(data && data.length > 0){
						$("#questionsContainer").empty();
						for(var i = 0; i < data.length; i++){
							var curr = data[i];
							var btn = $("<button type='btn' class='btn btn-error btn-block disabled'>Question " + (i+1) + "</button>")
										.attr('data-showQuestionId', curr._id);
							if(curr.isDone || (lastSQID.length > 0 && lastSQID == curr._id))
								btn.append($("<span class='glyphicon glyphicon-ok'></span>"));
							$("#questionsContainer").append(btn);
							
						}
						checkForActiveQuestion();
					}else{
						setTimeout(function(){
							loadQuestions(cookieShowId);
						}, 2000);
					}
				},
				error: function(xhr, status, err){
					console.error(status);
					console.dir(err);
				}
			});
		}
	
		$(function(){
			if(cookieShowId && cookieShowId.length > 0){
				loadQuestions();
			}
		});
		
		function checkForShowEnd(){
		}
	</script>
	
	<style>
		.glyphicon.glyphicon-ok{ color: green; margin-left: 10px; }
	</style>

<?php include('footer.php'); ?>