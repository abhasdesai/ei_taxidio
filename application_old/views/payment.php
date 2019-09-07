
		<h2> Payu Payments Example </h2> <hr />
		<form method='POST'>
			<table border='0'>
				<tr> <td> Key : </td> <td> <input name='key' type='text' value='71tFEF'> </td>
				<tr> <td> Transaction Id : </td> <td> <input name='txnid' type='text' value='<?php echo uniqid( "animesh_" );?>'> </td>			
				<tr> <td> Amount : </td> <td> <input name='amount' type='text' value='<?php echo rand(0, 100);?>'> </td>
				<tr> <td> Firstname : </td> <td> <input name='firstname' type='text' value='animesh'> </td>
				<tr> <td> Email : </td> <td> <input name='email' type='text' value='animesh.kundu@payu.in'> </td>
				<tr> <td> Phone : </td> <td> <input name='phone' type='text' value='1234567890'> </td>
				<tr> <td> Product Info : </td> <td> <input name='productinfo' type='text' value='Just another test site'> </td>
			</table>
			
			<input type="submit" value="Submit">
		</form>

