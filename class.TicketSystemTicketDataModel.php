<?php
/*
 * Used for building a ticket from DB
 * 
 * @author Patrick Geddie <geddiep@yahoo.com>  
*/
class TicketSystemTicketDataModel
{
	public $db_name = 'ticket_system'; 
	/*
	 * Builds TicketSystem Ticket
	 * 
	 * @param int; ticket id
	 * @return mixed; TicketSystemTicket 
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function get($int)
	{
		$array 				= $this->get_local_ticket($int);
		$TicketSystemTicket	= new TicketSystemTicket();
		
		if(!empty($array))
		{
			$history_results	= $this->get_ticket_history($array['ticket_id']);
			$attachment_results	= $this->get_ticket_attachments($array['ticket_id']);
			
			$TicketSystemTicket->set_ticket_ticket_id($array['ticket_id']);
			$TicketSystemTicket->set_create_date($array['create_date']);
			$TicketSystemTicket->set_update_date($array['update_date']);
			$TicketSystemTicket->set_ticket_number($array['ticket_number']);
			$TicketSystemTicket->set_ticket_ticket_href($array['ticket_href']);
			$TicketSystemTicket->set_ticket_customer_id($array['customer_id']);
			$TicketSystemTicket->set_status_id($array['status']);
			$TicketSystemTicket->set_assigned_to($array['assigned_to']);
			$TicketSystemTicket->set_issue_list($array['issue_list']);
			$TicketSystemTicket->set_documented_issue($array['documented_issue']);
			$TicketSystemTicket->set_account_name($array['account_name']);
			$TicketSystemTicket->set_ticket_details($array['ticket_details']);
			$TicketSystemTicket->set_ticket_category($array['ticket_category']);
			$TicketSystemTicket->set_ticket_summary($array['ticket_summary']);
			
			//if ticket has an attachment, build
			if($attachment_results)
			{
				$TicketAttachment = new TicketAttachment();
				
				foreach($attachment_results as $attachment)
				{
					if($attachment['attachment_location'] == 1)
					{
						$TicketAttachmentItem = new TicketAttachmentItem();
						
						$TicketAttachmentItem->set_history_id($attachment['history_id']);
						$TicketAttachmentItem->set_attachment_location($attachment['attachment_location']);
						$TicketAttachmentItem->set_ticket_id($attachment['ticket_id']);
						$TicketAttachmentItem->set_attachment_guid($attachment['attachment_guid']);
						$TicketAttachmentItem->set_attachment_name($attachment['attachment_name']);
						
						$TicketAttachment->append($TicketAttachmentItem);						
					}
				}
				$TicketSystemTicket->set_TicketAttachment($TicketAttachment);
			}
			$TicketHistory = new TicketHistory();
			
			if($history_results)
			{
				foreach($history_results as $history)
				{
					$TicketHistoryItem = new TicketHistoryItem();
					
					$TicketHistoryItem->set_ticket_ticket_id($history['ticket_ticket_id']);
					$TicketHistoryItem->set_history_ticket_id($history['history_ticket_id']);
					$TicketHistoryItem->set_action_id($history['action_id']);
					$TicketHistoryItem->set_old_status($history['old_status']);
					$TicketHistoryItem->set_new_status($history['new_status']);
					$TicketHistoryItem->set_action_performer($history['action_performer']);
					$TicketHistoryItem->set_action_target($history['action_target']);
					$TicketHistoryItem->set_comments($history['comments']);
					$TicketHistoryItem->set_action_date($history['action_date']);
					
					$TicketAttachment = new TicketAttachment();
					
					//if history has an attachment, build
					if($attachment_results)
					{
						foreach($attachment_results as $attachment)
						{
							if($attachment['history_id'] == $history['history_ticket_id'])
							{
								$TicketAttachmentItem = new TicketAttachmentItem();
									
								$TicketAttachmentItem->set_history_id($attachment['history_id']);
								$TicketAttachmentItem->set_attachment_location($attachment['attachment_location']);
								$TicketAttachmentItem->set_ticket_id($attachment['ticket_id']);
								$TicketAttachmentItem->set_attachment_guid($attachment['attachment_guid']);
								$TicketAttachmentItem->set_attachment_name($attachment['attachment_name']);
									
								$TicketAttachment->append($TicketAttachmentItem);
							}
						}
					}
					$TicketHistoryItem->set_TicketAttachment($TicketAttachment);
					
					$TicketHistory->append($TicketHistoryItem);
				}
				$TicketSystemTicket->set_TicketHistory($TicketHistory);
			}
		}
		return $TicketSystemTicket;	
	}
	/*
	 *Gets all data from the db 
	 * 
	 * @param int; ticket id
	 * @return mixed; array of ticket items
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function get_local_ticket($ticket_id)
	{
		$ticket_sql = 	"	SELECT
								*
							FROM
								$this->db_name.`ticket_ticket`
							WHERE
								`ticket_id`= {$ticket_id} 
						";
						
		$ticket		= query($ticket_sql, true);
		
		return $ticket;
	}
	/*
	 * Queries DB to get list of all history items of given ticket ID
	 * 
	 * @param int; ticket ticket id
	 * @return mixed; array of the history for the provided ticket id
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function get_ticket_history($ticket_id)
	{
		$history_sql		= 	"	SELECT
										*
									FROM
										$this->db_name.`ticket_history`
									WHERE
										`ticket_ticket_id`= {$ticket_id}
								";
									
		$history_results	= query($history_sql);
		
		return $history_results;
	}
	/*
	 * Queries DB to get list of all attachment items of given ticket ID
	 * 
	 * @param int; ticket ticket id
	 * @return mixed; array of the attachments for the provided ticket id
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>   
	*/
	public function get_ticket_attachments($ticket_id)
	{
		$attachment_sql		= 	"	SELECT
										*
									FROM
										$this->db_name.`ticket_attachment`
									WHERE
										`ticket_id`= {$ticket_id}
								";
									
		$attachment_results	= query($attachment_sql);
		
		return $attachment_results;
	}
}
?>
