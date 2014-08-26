<?php include('header.php'); ?>

<div class="containerOuter">
	<h3>Mailing List Signup</h3>
	<p>
		If you would like to be added to the No Exit Performance mailing list, enter your email address below and click submit.
	</p>
	<div class="form-horizontal">
		<div class="form-group">
		  <div class="col-lg-10">
			<input type="text" class="form-control" id="txtEmail" placeholder="Email" autocomplete="off" style="cursor: auto; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QsPDhss3LcOZQAAAU5JREFUOMvdkzFLA0EQhd/bO7iIYmklaCUopLAQA6KNaawt9BeIgnUwLHPJRchfEBR7CyGWgiDY2SlIQBT/gDaCoGDudiy8SLwkBiwz1c7y+GZ25i0wnFEqlSZFZKGdi8iiiOR7aU32QkR2c7ncPcljAARAkgckb8IwrGf1fg/oJ8lRAHkR2VDVmOQ8AKjqY1bMHgCGYXhFchnAg6omJGcBXEZRtNoXYK2dMsaMt1qtD9/3p40x5yS9tHICYF1Vn0mOxXH8Uq/Xb389wff9PQDbQRB0t/QNOiPZ1h4B2MoO0fxnYz8dOOcOVbWhqq8kJzzPa3RAXZIkawCenHMjJN/+GiIqlcoFgKKq3pEMAMwAuCa5VK1W3SAfbAIopum+cy5KzwXn3M5AI6XVYlVt1mq1U8/zTlS1CeC9j2+6o1wuz1lrVzpWXLDWTg3pz/0CQnd2Jos49xUAAAAASUVORK5CYII=); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
		  </div>
		</div>
		<button type="button" class="btn btn-lg btn-block btn-info" onclick="javascript:submitForm(this);">Submit</button>
	</div>
</div>

<script>
	$(function(){
		$.removeCookie('NEAshowUserId');
		$.removeCookie('NEAshowId');
	});
	
	function submitForm(btn){
		hideMessages();
		var txtEmail = $("#txtEmail");
		if(txtEmail.val().length > 0){
			if(checkEmail(txtEmail.val())){
				$.ajax({
					url: '<?php echo $restBase ?>/shows/signup',
					type: 'POST',
					data: JSON.stringify({emailAddress: txtEmail.val()}),
					crossDomain: true,
					contentType: 'application/json; charset=utf-8',
					success: function(data, status, xhr){
						window.location.href="/index.php?done=true";
					},
					error: function(xhr, status, err){
						console.error(status);
						console.dir(err);
					}
				});
			}else{
				setErrorMessage('Looks like you entered an invalid email address');
			}
		}else{
			setErrorMessage('Looks like you forgot to enter an email address');
		}
	}
	
	function checkEmail(emailAddress) {
	  var sQtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
	  var sDtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
	  var sAtom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
	  var sQuotedPair = '\\x5c[\\x00-\\x7f]';
	  var sDomainLiteral = '\\x5b(' + sDtext + '|' + sQuotedPair + ')*\\x5d';
	  var sQuotedString = '\\x22(' + sQtext + '|' + sQuotedPair + ')*\\x22';
	  var sDomain_ref = sAtom;
	  var sSubDomain = '(' + sDomain_ref + '|' + sDomainLiteral + ')';
	  var sWord = '(' + sAtom + '|' + sQuotedString + ')';
	  var sDomain = sSubDomain + '(\\x2e' + sSubDomain + ')*';
	  var sLocalPart = sWord + '(\\x2e' + sWord + ')*';
	  var sAddrSpec = sLocalPart + '\\x40' + sDomain; // complete RFC822 email address spec
	  var sValidEmail = '^' + sAddrSpec + '$'; // as whole string

	  var reValidEmail = new RegExp(sValidEmail);

	  if (reValidEmail.test(emailAddress)) {
		return true;
	  }

	  return false;
	}
</script>

<style>
	div.containerOuter{ margin: 0 14px; }
	div.form-horizontal{ padding: 10px 0; }
</style>

<?php include('footer.php'); ?>