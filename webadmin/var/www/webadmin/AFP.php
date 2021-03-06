<?php

include "inc/config.php";
include "inc/auth.php";
include "inc/functions.php";

$title = "AFP";

include "inc/header.php";

$afp_running = (trim(suExec("getafpstatus")) === "true");

$accounterror = "";
$accountsuccess = "";

if (isset($_POST['afppass']))
{
	$afppw1 = $_POST['afppass1'];
  $afppw2 = $_POST['afppass2'];
  if ($afppw1 != "") 
  {
    if ($afppw1 == $afppw2)
    {
        $result = suExec("resetafppw ".$afppw1);
        if (strpos($result,'BAD PASSWORD') !== false) {
                $accounterror = $result;
        }
        else {
        $accountsuccess = "AFP password changed.";
        $conf->changedPass("afpaccount");
        }
    }
    else 
    {
    	$accounterror = "Passwords do not match.";
    }
  }
  else
  {
  	$accounterror = "All fields required.";
  }
}

?>

<div id="restarting" class="alert alert-warning" style="display:none">
	<span><img src="images/progress.gif" width="25"> Restarting...</span>
</div>

<?php if ($accounterror != "") { ?>
	<?php echo "<div class=\"alert alert-danger\" >ERROR: " . $accounterror . "</div>" ?>
<?php } ?>

<?php if ($accountsuccess != "") { ?>
	<?php echo "<div class=\"alert alert-success\">" . $accountsuccess . "</div>" ?></span>
<?php } ?>

<script type="text/javascript">
function showErr(id, valid)
{
	if (valid || document.getElementById(id).value == "")
	{
		document.getElementById(id).style.borderColor = "";
		document.getElementById(id).style.backgroundColor = "";
	}
	else
	{
		document.getElementById(id).style.borderColor = "#a94442";
		document.getElementById(id).style.backgroundColor = "#f2dede";
	}
}

function enableButton(id, enable)
{
	document.getElementById(id).disabled = !enable;
}

function validateafpPW()
{
	var validPassword = (document.getElementById("afppass1").value != "");
	var validConfirm = (document.getElementById("afppass1").value == document.getElementById("afppass2").value);
	showErr("afppass2", validConfirm);
	enableButton("afppass", validPassword && validConfirm);
}
</script>

<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

		<h2>AFP</h2>

		<hr>

		<br>

		<?php
		if ($afp_running)
		{
			echo "<div class=\"alert alert-success alert-with-button\">
					<span>Enabled</span>
					<input type=\"button\" class=\"btn btn-sm btn-success pull-right\" value=\"Disable AFP\" onClick=\"javascript: return goTo(true, 'afpCtl.php?disable=true');\" />
				</div>";
		}
		else
		{
			echo "<div class=\"alert alert-danger alert-with-button\">
					<span>Disabled</span>
					<input type=\"button\" class=\"btn btn-sm btn-danger pull-right\" value=\"Enable AFP\" onClick=\"javascript: return goTo(true, 'afpCtl.php?enable=true');\" />
				</div>";
		}
		?>

		<form action="AFP.php" method="post" name="AFP" id="AFP">

			<!--
			<span class="label label-default">AFP Service</span>
			<input type="button" value="Restart" class="btn btn-sm btn-primary" onClick="javascript: return goTo(toggle_visibility('restarting', 'AFP'), 'afpCtl.php?restart=true');" <?php if (!$afp_running) { echo "disabled=\"disabled\""; } ?>/>
			<br><br>
			-->

			<span class="label label-default">AFP Password</span>

			<label class="control-label">New Password</label>
			<input type="password" placeholder="Required" name="afppass1" id="afppass1" class="form-control input-sm" value="" onClick="validateafpPW();" onKeyUp="validateafpPW();" onChange="validateafpPW();" />

			<label class="control-label">Confirm New Password</label>
			<input type="password" placeholder="Required" name="afppass2" id="afppass2" class="form-control input-sm" value="" onClick="validateafpPW();" onKeyUp="validateafpPW();" onChange="validateafpPW();" />
			<br>

			<input type="submit" name="afppass" id="afppass" value="Save" class="btn btn-primary" disabled="disabled" />
			<br>
			<br>

		</form> <!-- end AFP form -->

		<hr>
		<br>
		<input type="button" id="back-button" name="action" class="btn btn-sm btn-default" value="Back" onclick="document.location.href='settings.php'">

	</div>
</div>

<?php include "inc/footer.php"; ?>
