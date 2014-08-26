<?php include('header.php'); ?>

<div id="showsMainContent" class="jumbotron">
	<h1>Welcome!</h1>
	<p>A green button will appear below when the show is ready to begin.</p>
	<p>
		<div id="showsLoading">
			Please wait...
		</div>
		<button id="btnShowReady" type="button" class="btn btn-block btn-lg btn-success" onclick="javascript:showConfirmationModal(this);">Proceed</button>
	</p>
  
	<div id="showConfirmModal" class="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					<p>Enter the code on the screen...</p>
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-lg-10">
								<input type="text" class="form-control" id="txtShowConfirmCode" />
								<span class="help-block">The code is used to validate your participation in this specific performance.</span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button id="btnSubmitShowConfirmCode" onclick="validateShowConfirmCode(this);" type="button" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="showsDoneContent" class="jumbotron">
	<h1>Show has completed</h1>
	<p>Thank you for attending.</p>
</div>

<div id="showsWaitToProceed" class="jumbotron">
	<h1>Please wait...</h1>
	<p>The button below will turn green when we are ready to proceed.</p>
	<p>
		<button id="btnProceedToQuestions" type="button" class="btn btn-default btn-lg btn-block disabled">Please wait</button>
	</p>
</div>

<script>
	function validateShowConfirmCode(btn){
		var txtShowConfirmCode = $("#txtShowConfirmCode");
		txtShowConfirmCode.parents('.form-group:first').removeClass('has-error');
		var showConfirmCode = txtShowConfirmCode.val();
		var showId = $("#btnShowReady").attr('data-showId');
		$.ajax({
			url: '<?php echo $restBase ?>/shows/validate/' + showId + '/' + showConfirmCode,
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				if(data.isValid){
					$("#showConfirmModal").modal('hide');
					$("#showsMainContent").hide();
					$("#showsWaitToProceed").show();
					$.cookie('NEAshowId', showId, {expires: 1, path: '/' });
					$.cookie('NEAshowUserId', data.showUserId, {expires: 1, path: '/'});
					window.location.href = '/home.php';
				}else{
					txtShowConfirmCode.parents('.form-group:first').addClass('has-error');
				}
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	function showConfirmationModal(btn){
		$("#showConfirmModal").modal();
	}
	
	function checkForShows(){
		$.ajax({
			url: '<?php echo $restBase ?>/shows',
			type: 'GET',
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			success: function(data, status, xhr){
				if(data.length == 1){
					$("#showsLoading").hide();
					$("#btnShowReady").attr('data-showId', data[0]._id).show();
				}else{
					setTimeout(checkForShows, 5000);
				}
			},
			error: function(xhr, status, err){
				console.error(status);
				console.dir(err);
			}
		});
	}
	
	var showsClient = new Faye.Client('<?php echo $fayeBase ?>');
	$(function(){
		if(isDoneSet == false){
			if((cookieShowId && cookieShowId.length > 0) && (cookieShowUserId && cookieShowUserId.length > 0)){
				$.ajax({
					url: '<?php echo $restBase ?>/shows/validateCookie/' + cookieShowId,
					type: 'GET',
					crossDomain: true,
					contentType: 'application/json; charset=utf-8',
					success: function(data, status, xhr){
						if(data && data.isActive == true)
							window.location.href = 'home.php';
						else{
							checkForShows();
						}
					},
					error: function(xhr, status, err){
						console.error(status);
						console.dir(err);
					}
				});
			}else{
				checkForShows();
			}
		}else{
			$("div#showsMainContent").hide();
			$("div#showsDoneContent").show();
		}
	});
</script>

<style>
	body{
		background-color: #118499;
	}
	#showsLoading{text-align: center;}
	#btnShowReady{display: none;}
	#showsWaitToProceed{display: none; text-align: center;}
	div#showsMainContent, div#showsDoneContent{ margin: 14px; border-radius: 5px; }
	div#showsDoneContent{ display: none; }
</style>

<?php include('footer.php'); ?>
