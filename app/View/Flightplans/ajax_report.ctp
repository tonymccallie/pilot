<?php
	$label = "background: #eee;";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css" media="screen">
<!--
	body {
		font-family: sans-serif;
	}
-->
</style>
<title>Report</title>
</head>
<body>
	<table cellpadding="10" style="width: 100%">
		<tr>
			<td style="width: 100%; background: #1e1e1e;" colspan="2"><img src="<?php echo Common::currentUrl() ?>img/logo.png" alt=""></td>
		</tr>
		<tr>
			<td style="width: 80%">&nbsp;</td>
			<td style="width: 20%; <?php echo $label ?>">Total: $<?php echo $flightplan['Flightplan']['days']*$flightplan['Pilot']['rate'] ?></td>
		</tr>
		<tr>
			<td style="width: 80%">&nbsp;</td>
			<td style="width: 20%; <?php echo $label ?>">Pilot: <?php echo $flightplan['Pilot']['first_name'].' '.$flightplan['Pilot']['last_name'] ?></td>
		</tr>
	</table>
	<table cellpadding="10" style="width: 100%">
		<tr>
			<td style="<?php echo $label ?> width: 100%;"><b>Plane</b></td>
		</tr>
		<tr>
			<td><?php echo $flightplan['Plane']['tag'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Pilot</b></td>
		</tr>
		<tr>
			
			<td><?php echo $flightplan['Pilot']['first_name'].' '.$flightplan['Pilot']['last_name'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Owner Responsible</b></td>
		</tr>
		<tr>
			
			<td><?php echo $flightplan['Responsible']['first_name'].' '.$flightplan['Responsible']['last_name'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Hobbs Meter In</b></td>
		</tr>
		<tr>
			
			<td><?php echo $flightplan['Flightplan']['hobbs_in'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Hobbs Meter Out</b></td>
		</tr>
		<tr>
			
			<td><?php echo $flightplan['Flightplan']['hobbs_out'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Days</b></td>
		</tr>
		<tr>
			
			<td><?php echo $flightplan['Flightplan']['days'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Rate</b></td>
		</tr>
		<tr>
			
			<td>$<?php echo $flightplan['Pilot']['rate'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Start</b></td>
		</tr>
		<tr>
			
			<td><?php echo date('m/d/Y h:ia',strtotime($flightplan['Flightplan']['start'])) ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Stop</b></td>
		</tr>
		<tr>
			
			<td><?php echo date('m/d/Y h:ia',strtotime($flightplan['Flightplan']['stop'])) ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Hours</b></td>
		</tr>
		<tr>
			
			<td>
				<?php
					$start = strtotime($flightplan['Flightplan']['start']);
					$stop = strtotime($flightplan['Flightplan']['stop']);
					$diff = round(($stop - $start)/3600, 1);

					echo $diff;
				?>
			</td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Stops</b></td>
		</tr>
		<tr>
			
			<td>
				<?php
					$stops = json_decode($flightplan['Flightplan']['stops'],true);
					foreach($stops as $stop):
				?>
				<?php echo $stop['name'] ?><br />
				<?php endforeach ?>
			</td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Maintenance</b></td>
		</tr>
		<tr>
			
			<td><?php echo $flightplan['Flightplan']['maintenance'] ?></td>
		</tr>
		<tr>
			<td style="<?php echo $label ?>"><b>Notes</b></td>
		</tr>
		<tr>
			
			<td><?php echo $flightplan['Flightplan']['notes'] ?></td>
		</tr>
	</table>
</body>
</html>