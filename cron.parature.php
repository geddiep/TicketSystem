<?php
include('class.TicketSystem.php');
//include('config.php'); //not included

$TicketSystem = new TicketSystem();

switch ($argv[1]) 
{
	case 'account':
		$TicketSystem->poll_account_data();
		break;
	case 'customer':
		$TicketSystem->poll_customer_data();
		break;
	case 'csr':
		$TicketSystem->poll_csr_data();
		break;
	case 'ticket':		
		$TicketSystem->poll_tickets();
		break;
}

//log

?>