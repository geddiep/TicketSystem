<?php

interface TicketInterface
{
	CONST CSR_ADD 			= 1;
	CONST CUSTOMER_ADD 		= 2;
	CONST ACCOUNT_ADD 		= 3;
	CONST TICKET_ADD 		= 4;
	CONST STATUS_ADD		= 5;
	CONST ATTACHMENT_ADD	= 6;
	CONST DETAILS_ADD		= 7;
	CONST HISTORY_ADD		= 8;
	CONST ACTION_ADD		= 9;
	CONST TICKET_UPDATE		= 10;
	CONST ACCOUNT_UPDATE	= 11;
	CONST CSR_UPDATE		= 12;
	CONST CUSTOMER_UPDATE	= 13;
}

include_once 'class.TicketSystemTicket.php';
include_once 'class.TicketSystemTicketDataModel.php';

/*
 * Accesses TicketSystem API to bring back account, customer, ticket and customer service rep data.
 * 
 * @author Patrick Geddie <geddiep@yahoo.com>  
*/
class TicketSystem implements TicketInterface
{
	public $token					= "token";
	public $db_name					= "TicketSystem";
	public $url_API_hostname		= "hostname";
	public $url_api					= "api/";
	public $url_version				= "v1/";
	public $url_account_id			= "16064/"; //account
	public $url_dept_id				= "16072/"; //department
	public $url_assigned_to			= "_Assigned_To_=";
	public $url_object_type			= '';
	public $url_page_size			= "_pageSize_=";
	public $url_start_page			= "_startPage_=";
	public $url_total				= "_total_=true";  //only shows the totals for the respective call
	public $url_schema				= "schema/";
	public $url_status				= "status/";
	public $url_upload				= "upload/";
	public $url_ticket				= "Ticket/";
	public $url_action				= "action/";
	public $url_view				= "view/";
	public $url_date_updated		= "Date_Updated=";
	public $url_token				= "_token_=";
	public $url_history				= "_history_=true";
	public $media_location			= '/www/media/TicketSystem/';
	public $attachment_ftp_connect	= null;
	public $login_ftp				= null;
	public $insert_records			= 0;
	public $url_action_id			= 0;
	public $url_status_id			= 0;
	public $total_ticket_calls		= 0;
	public $url_object_id			= 0;
	public $url_csr_id				= 0;
	public $ticket_num				= 0;
	public $page_num				= 0;
	public $page_size				= 0;
	public $total_csr_records_added = 0;
	
	/*
	 * Ensure all necessary tables are created prior to adding data
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function create_db_tables()
	{
		$sql = "CREATE TABLE IF NOT EXISTS 
					`{$this->db_name}`.`ticket_action` 
					(
						`action_id` int(10) NOT NULL,
						`action_name` varchar(255) NOT NULL,
						PRIMARY KEY (`action_id`)
					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS
					`{$this->db_name}`.`ticket_status` 
					(
						`status_id` int(11) NOT NULL,
						`status_name` varchar(255) NOT NULL,
						PRIMARY KEY (`status_id`)
					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);		
		
		$sql = "CREATE TABLE IF NOT EXISTS
					`{$this->db_name}`.`ticket_att_location` 
					(
						`location_id` int(10) NOT NULL,
						`location_name` varchar(255) NOT NULL,
						PRIMARY KEY (`location_id`)
					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);

		$sql  = "CREATE TABLE IF NOT EXISTS 
					`{$this->db_name}`.`ticket_csr`
					(
 						`csr_id` int(10) NOT NULL AUTO_INCREMENT,
 						`ticket_id` int(10) NOT NULL,
 						`full_name` varchar(255) NOT NULL,
						`email` varchar(255) NOT NULL,
						`ticket_href` varchar(255) NOT NULL,
						`ticket_status` int(10) NOT NULL,
 						PRIMARY KEY (`csr_id`),
 						UNIQUE KEY `ticket_id` (`ticket_id`)
					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);
		
		$sql =	"CREATE TABLE IF NOT EXISTS 
					`{$this->db_name}`.`ticket_account`
					(
 						`account_id` int(10) NOT NULL AUTO_INCREMENT,
 						`ticket_id` int(10) NOT NULL,
 						`account_name` varchar(255) NOT NULL,
						`ticket_href` varchar(255) NULL,
 						PRIMARY KEY (`account_id`),
 						UNIQUE KEY `ticket_id` (`ticket_id`)
 					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS 
					`{$this->db_name}`.`ticket_customer`
					(
 						`customer_id` int(10) NOT NULL AUTO_INCREMENT,
 						`ticket_id` int(10) NOT NULL,
 						`first_name` varchar(255) NOT NULL,
 						`last_name` varchar(255) NOT NULL,
						`email` varchar(255) NOT NULL,
						`ticket_href` varchar(255) NOT NULL,
						`ticket_account_id` int(10) NULL,
						PRIMARY KEY (`customer_id`),
 						KEY `ticket_account_id` (`ticket_account_id`),
 						UNIQUE KEY `ticket_id` (`ticket_id`),
 						CONSTRAINT `ticket_account_id` FOREIGN KEY (`ticket_account_id`) REFERENCES `ticket_account` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS
					`{$this->db_name}`.`ticket_ticket` 
					(
						`ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  						`ticket_id` int(10) NOT NULL,
  						`create_date` int(10) NOT NULL,
  						`update_date` int(10) NOT NULL,
  						`ticket_number` varchar(255) NOT NULL,
  						`ticket_href` varchar(255) NOT NULL,
  						`customer_id` int(10) NOT NULL,
  						`status` int(10) NOT NULL,
  						`assigned_to` int(10) NULL,
  						`issue_list` varchar(255) NULL,
  						`documented_issue` varchar(255) NULL,
  						`account_name` varchar(255) NULL,
  						`ticket_details` text NULL,
  						`ticket_category` varchar(255) NULL,
  						`ticket_summary` text NULL,
  						PRIMARY KEY (`ticket_id`),
  						UNIQUE KEY `ticket_id` (`ticket_id`),
  						KEY `status` (`status`),
  						KEY `assigned_to` (`assigned_to`),
  						CONSTRAINT `customer` FOREIGN KEY (`customer_id`) REFERENCES `ticket_customer` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  						CONSTRAINT `csr` FOREIGN KEY (`assigned_to`) REFERENCES `ticket_csr` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  	CONSTRAINT `status` FOREIGN KEY (`status`) REFERENCES `ticket_status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS
					`{$this->db_name}`.`ticket_history` 
					(
						`history_id` int(11) NOT NULL AUTO_INCREMENT,
  						`ticket_ticket_id` int(10) NOT NULL,
  						`history_ticket_id` int(10) NOT NULL,
  						`action_id` int(10) NOT NULL,
  						`old_status` int(10) NULL,
  						`new_status` int(10) NULL,
  						`action_performer` int(10) NULL,
  						`action_target` int(10) NULL,
  						`comments` text NULL,
  						`action_date` int(11) NOT NULL,
  						PRIMARY KEY (`history_id`),
  						UNIQUE KEY `history_ticket_id` (`history_ticket_id`),
  						KEY `ticket_ticket_id` (`ticket_ticket_id`),
  						KEY `action_id` (`action_id`),
  						KEY `old_status` (`old_status`),
  						KEY `new_status` (`new_status`),
  						KEY `action_performer` (`action_performer`),
  						KEY `action_target` (`action_target`),
  						CONSTRAINT `ticket_ticket_id` FOREIGN KEY (`ticket_ticket_id`) REFERENCES `ticket_ticket` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  						CONSTRAINT `action_id` FOREIGN KEY (`action_id`) REFERENCES `ticket_action` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  						CONSTRAINT `old_status` FOREIGN KEY (`old_status`) REFERENCES `ticket_status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  						CONSTRAINT `new_status` FOREIGN KEY (`new_status`) REFERENCES `ticket_status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  						CONSTRAINT `action_performer` FOREIGN KEY (`action_performer`) REFERENCES `ticket_csr` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  						CONSTRAINT `action_target` FOREIGN KEY (`action_target`) REFERENCES `ticket_csr` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE  						
  					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
		
		query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS
					`{$this->db_name}`.`ticket_attachment` 
					(
						`attachment_id` int(10) NOT NULL AUTO_INCREMENT,
					  	`attachment_location` int(11) NOT NULL,
					 	`history_id` int(10) DEFAULT NULL,
					  	`ticket_id` int(10) NOT NULL,
					  	`attachment_guid` varchar(255) NOT NULL,
					  	`attachment_name` varchar(255) NOT NULL,
					  	PRIMARY KEY (`attachment_id`),
					  	KEY `attachment_location` (`attachment_location`),
					  	KEY `history_id` (`history_id`),
					  	KEY `ticket_id` (`ticket_id`),
					  	CONSTRAINT `attachment_location` FOREIGN KEY (`attachment_location`) REFERENCES `ticket_att_location` (`location_id`) ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT `history_id` FOREIGN KEY (`history_id`) REFERENCES `ticket_history` (`history_ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT `ticket_id` FOREIGN KEY (`ticket_id`) REFERENCES `ticket_ticket` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE
  					) 
					ENGINE=InnoDB DEFAULT CHARSET=utf8
				";
					
		query($sql);
	}
	/*
	 * Gets all account info from ticket and sends to add_records_to_db method
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function poll_account_data()
	{
		//get total accounts
		$arr_of_ids			= array();
		$url 				= $this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Account?".$this->url_total."&".$this->url_token.$this->token;
		$data 				= $this->retrieve_ticket_data($url);
		$account_attr		= $data->attributes();
		$this->page_size	= $account_attr->total;
		
		$sql = 	"	SELECT
						ticket_id, account_name
					FROM
						{$this->db_name}.ticket_account
				";
		
		$local_accounts = query($sql);
		
		if($local_accounts)
		{
			foreach($local_accounts as $local_account)
				array_push($arr_of_ids, $local_account['ticket_id']);
		}
		
		$this->page_num	= 1;
		$calls			= ceil($this->page_size / 100);
		$done 			= false;
			
		if($this->page_size > 100)			
			$this->page_size = 100;
			
		while(!$done)
		{
			$accounts_to_add	= array();
			$accounts_to_update	= array();
			$url 				= $this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Account?".$this->url_page_size.$this->page_size."&".$this->url_start_page.$this->page_num."&".$this->url_token.$this->token;
			$data 				= $this->retrieve_ticket_data($url);
			$arr_of_objects 	= $data->Account;
			
			foreach ($arr_of_objects as $remote_account)
			{
				$account_attr			= $remote_account->attributes();
				$remote_ticket_id		= sql_escape($account_attr->id);
				$remote_account_name	= $remote_account->Account_Name;
				
				if(!in_array($remote_ticket_id, $arr_of_ids))
				{
					$remote_account_name	= sql_escape($remote_account->Account_Name);
					$ticket_href			= sql_escape($account_attr->href);
					$account_info 			= "(NULL, '{$remote_ticket_id}', '{$remote_account_name}', '{$ticket_href}')";
						
					array_push($accounts_to_add, $account_info);
				}
				else
				{
					foreach($local_accounts as $local_account)
					{
						if($remote_ticket_id == $local_account['ticket_id'])
						{
							if($remote_account_name != $local_account['account_name'])
							{
								$update_account['ticket_id']	= $remote_ticket_id;
								$update_account['account_name']	= sql_escape($remote_account_name);
								
								array_push($accounts_to_update, $update_account);
							}
						}
					}
				}
			}
			
			if(!empty($accounts_to_add))
			{
				if($this->page_num == 1)
					$accounts_to_add[] = "(NULL, 0, 'NO ACCOUNT', NULL)";
					
				$this->add_records_to_db($accounts_to_add, self::ACCOUNT_ADD);
			}
			
			if(!empty($accounts_to_update))
				$this->add_records_to_db($accounts_to_update, self::ACCOUNT_UPDATE);
				
			$this->page_num++;
			
			if($calls < $this->page_num)
				$done = true;
		}
		
		echo "end account polling";
	}
	/*
	 * Builds URL and accesses the API, returns data, filters to desired information and sends to DB
	 * 
	 * @return int; amount of records added
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function poll_csr_data()
	{
		$arr_of_ids 		= array();
		$usable_csr_data	= array();
		$csr_to_update		= array();
		$urls 				= array($this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Csr?status_id_=1"."&".$this->url_token.$this->token,$this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Csr?status_id_=-1"."&".$this->url_token.$this->token);
		$sql 				= "	SELECT
									*
								FROM
									{$this->db_name}.ticket_csr
							  ";
		$local_results 		= query($sql);
		
		if($local_results)
		{			
			foreach($local_results as $result)
				array_push($arr_of_ids, $result['ticket_id']);
		}
		
		// foreach on $url is because there are 2 links, one for active accounts and the other for inactive accounts
		foreach($urls as $group)
		{
			$data = $this->retrieve_ticket_data($group);
			
			foreach($data->Csr as $remote_single_csr)
			{
				$single_csr_attr 		= $remote_single_csr->attributes();
				$remote_ticket_id		= sql_escape($single_csr_attr->id);
				$remote_full_name		= sql_escape($remote_single_csr->Full_Name);
				$remote_email			= sql_escape($remote_single_csr->Email);
				$ticket_href_attr		= $remote_single_csr->attributes();
				$ticket_href			= sql_escape($ticket_href_attr->href);
				$ticket_status_attr 	= $remote_single_csr->Status->Status->attributes();
				$status_id				= sql_escape($ticket_status_attr->id);
				
				if(!in_array($remote_ticket_id, $arr_of_ids))
				{
					$str_csr = "(NULL,'{$remote_ticket_id}', '{$remote_full_name}', '{$remote_email}', '{$ticket_href}', '{$status_id}')";
					
					array_push($usable_csr_data, $str_csr);
				}
				else
				{
					foreach($local_results as $local_csr)
					{
						if($remote_ticket_id == $local_csr['ticket_id'])
						{
							if($remote_full_name != $local_csr['full_name'])
								$update_csr['full_name'] = sql_escape($remote_full_name);
															
							if($remote_email != $local_csr['email'])
								$update_csr['email'] = sql_escape($remote_email);
							
							if(!empty($update_csr))
							{
								$update_csr['ticket_id'] = $remote_ticket_id;
							
								array_push($csr_to_update, $update_csr);
								
								$update_csr = array();
							}
						}
					}					
				}
			}
		}
		
		if(!empty($usable_csr_data))
			$this->add_records_to_db($usable_csr_data, self::CSR_ADD);
			
		if(!empty($csr_to_update))
			$this->add_records_to_db($csr_to_update, self::CSR_UPDATE);
			
		echo "end csr polling";
			
	}
	/*
	 * Grabs ticket history and sends to add_records method
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function mine_ticket_history()
	{
		$ticket_href 			= array();
		$action_id_list			= array();
		$ticket_history			= array();
		$actions_to_add			= array();
		$attachments_to_add		= array();
		$attachment_guid_list	= array();
		
		$sql		 			= 	"	SELECT 
										ticket_id, ticket_href
									FROM
										$this->db_name.ticket_ticket	
									";
				
		$ticket_href_results	= query($sql);
		
		if(!empty($ticket_href_results))
		{
			//get action list
			$action_id_sql 	= 	"	SELECT
										action_id
									FROM
										$this->db_name.`ticket_action`	
								";
										
			$action_result 	= query($action_id_sql);
			
			if($action_result)
			{
				foreach($action_result  as $action_id)
					array_push($action_id_list, $action_id['action_id']);
			}
			
			$attach_guid_sql	= 	"	SELECT
											attachment_guid
										FROM
											$this->db_name.`ticket_attachment`
										WHERE
											`attachment_location` = 2	
									";
										
			$attach_guid_result = query($attach_guid_sql);
			
			if($attach_guid_result)
			{
				foreach($attach_guid_result  as $guid)
					array_push($attachment_guid_list, $guid['attachment_guid']);				
			}
			
			foreach ($ticket_href_results as $ticket) 
			{
				$url 		= $ticket['ticket_href']."?".$this->url_history."&".$this->url_token.$this->token;
				$data 		= $this->retrieve_ticket_data($url);
				$history 	= $data->ActionHistory;
				
				if($history)
				{
					foreach($history as $history_item)
					{
						foreach($history_item->History as $item)
						{
							$history_ticket_id_attr 	= $item->attributes();
							$history_ticket_id 		= sql_escape($history_ticket_id_attr['id']);
							$action_attr				= $item->Action->attributes();
							$action_id					= sql_escape($action_attr['id']);
							
							if(in_array($action_id, $action_id_list) === false)
							{
								$action_name 	= sql_escape($action_attr['name']);
								$action_to_add 	= "('{$action_id}', '{$action_name}')";
								array_push($actions_to_add, $action_to_add);
								array_push($action_id_list,$action_id);
							}
							
							$old_status_attr	= $item->Old_Status->Status->attributes();
							$old_status			= sql_escape($old_status_attr->id);
							$new_status_attr	= $item->New_Status->Status->attributes();
							$new_status			= sql_escape($new_status_attr->id);
										
							if(!empty($item->Action_Performer->Csr))
							{
								$action_performer_attr 	= $item->Action_Performer->Csr->attributes();
								$action_performer		= sql_escape($action_performer_attr['id']);
							}
							else
								$action_performer = 0;
								
							if(!empty($item->Action_Target->Csr))
							{
								$action_target_attr 	= $item->Action_Target->Csr->attributes();

								$action_target			= sql_escape($action_target_attr['id']);
							}
							else
								$action_target = 0;
								
							if(!empty($item->Comments))
								$comments = sql_escape($item->Comments);
							else
								$comments = '';

							$find		= 'Click Here for Details';
							$html 		= $comments;
							$results 	= strpos($html, $find);
								
							$action_date = sql_escape(strtotime($item->Action_Date));
							
							if(!empty($item->History_Attachments))
							{
								foreach($item->History_Attachments->Attachment as $attachment)
								{
									
									$is_comment 		= false;
									$attachment_name	= false;
									$attachment_attr 	= $attachment->attributes();
									$comment_html		= 'Comment.html';
									$name				= sql_escape($attachment->Name);
									
									if($name == $comment_html)
										$attachment_name = true;
																	
									if($attachment_name == true && $results != 0)
										$is_comment = true;
																		
									//if there is a large comment and ticket has added it as an attachment, this will place the text into the comment field 
									if($is_comment === true)
									{
										$attachment_href	= sql_escape($attachment_attr->href);
										$comments 			= $this->add_attachment_to_ftp($attachment_href);
									}
									else
									{
										$history_attachment_href	= sql_escape($attachment_attr->href);
										$history_attachment_guid	= sql_escape($attachment->Guid);
										$attachment_name_pre		= sql_escape($attachment->Name);
										$attachment_name_exp		= explode(".", $attachment_name_pre);
										$attachment_name_ext		= array_pop($attachment_name_exp);
										
										//check to make sure .php, .phtml, .js files add a .txt for security 	
										if(strcasecmp($attachment_name_ext,'php') == 0 || strcasecmp($attachment_name_ext,'phtml') == 0 || strcasecmp($attachment_name_ext,'js') == 0)
											$attachment_name_ext = $attachment_name_ext.".txt";
										
										$attachment_name			= $ticket['ticket_id']."_".$history_attachment_guid.".".sql_escape($attachment_name_ext);
										
										if(!in_array($history_attachment_guid, $attachment_guid_list))
										{
											$return 			= $this->add_attachment_to_ftp($history_attachment_href, $attachment_name);
											$attachmet_to_add 	= "(NULL, '2','{$history_ticket_id}','{$ticket['ticket_id']}', '{$history_attachment_guid}', '{$attachment_name_pre}')";
											
											if($return === true)
												array_push($attachments_to_add, $attachmet_to_add);
										}
										array_push($attachment_guid_list, $history_attachment_guid);
									}
								}
							}
							
							$history_line 	= "(	NULL,
													'{$ticket['ticket_id']}',
													'{$history_ticket_id}', 
													'{$action_id}', 
													'{$old_status}', 
													'{$new_status}',
													'{$action_performer}',
													'{$action_target}',
													'{$comments}',
													'{$action_date}'
												)";
						
							array_push($ticket_history, $history_line);
						}
					}
				}
				
				if(!empty($actions_to_add))
					$this->add_records_to_db($actions_to_add, self::ACTION_ADD);

				
				if(!empty($ticket_history))
					$this->add_records_to_db($ticket_history, self::HISTORY_ADD);
			
				if(!empty($attachments_to_add))
					$this->add_records_to_db($attachments_to_add, self::ATTACHMENT_ADD);
				
				$ticket_history			= array();
				$attachments_to_add		= array();
				$actions_to_add			= array();
				
			}
		}
		if(is_resource($this->attachment_ftp_connect))
			ftp_close($this->attachment_ftp_connect);
			
		return true;
	}
	/*
	 * Queries TicketSystem API for details of ticket and sends data to add_records method
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function mine_ticket_details()
	{
		$ticket_href 	= array();
		$done 			= false;
		$position		= 0;
		$total_count	= 0;
		$increment		= 100;
		
		$sql = 	"	SELECT 
						count(ticket_href) count 
					FROM
						`{$this->db_name}`.ticket_ticket
					WHERE
						`ticket_details` IS NULL 
				";
		
		$count = query($sql, true);
		
		while(!$done)
		{
		
			$sql = 	"	SELECT
							ticket_href
						FROM
							`{$this->db_name}`.ticket_ticket
						WHERE
							`ticket_details` IS NULL 	
						LIMIT {$position},{$increment}			
					";
					
			$href_list_results = query($sql);
			
			if($href_list_results)
			{
				$csr_sql = 	"	SELECT
									ticket_id
								FROM
									`{$this->db_name}`.ticket_csr
							";
				$results = query($csr_sql);
				
				if($results)
				{
					foreach($results as $csr)
						$csr_id_list[] = $csr['ticket_id'];
										
					foreach($href_list_results as $ticket)
					{
						$url					= $ticket['ticket_href']."?".$this->url_history."&".$this->url_token.$this->token;
						$data 					= $this->retrieve_ticket_data($url);
						$ticket_attr 			= $data->attributes();
						$ticket_id			= sql_escape($ticket_attr->id);
						
						if(!empty($data->Assigned_To))
						{
							$assigned_to_attr 	= $data->Assigned_To->Csr->attributes();
							$assigned_to		= sql_escape($assigned_to_attr['id']);
							
							//reset to 0 if csr is not in DB
							if(!empty($assigned_to))
							{
								if(!in_array($assigned_to,$csr_id_list))
									$assigned_to = 0;																
							}
						}
						else
							$assigned_to = 0;
						
						$details_set = $data->Custom_Field;
						
						if($details_set[0])
						{
							//get the documented list item
							foreach($details_set->Option as $item)
							{
								$item_attr = $item->attributes();
								
								if(!empty($item_attr['selected']))
									$issue_list = sql_escape($item->Value);
							}
														
							if(empty($issue_list))
								$issue_list = "NULL";
						}	
						
						if($details_set[1])
						{
							//get the documented issue
							foreach($details_set[1]->Option as $item)
							{
								$item_attr = $item->attributes();
								
								if($item_attr['selected'] == true)
									$documented_issue = sql_escape($item->Value);
							}
							
							if(empty($documented_issue))
								$documented_issue = "No";
						}
				
						$account_name	= sql_escape($details_set[3]);
						$ticket_details		= sql_escape($details_set[4]);
						
						if($details_set[5])
						{
							//get the documented issue
							foreach($details_set[5]->Option as $item)
							{
								$item_attr = $item->attributes();
								
								if($item_attr['selected'] == true)
									$ticket_category = sql_escape($item->Value);
							}
							
							if(empty($ticket_category))
								$ticket_category = "NULL";
						}
						
						$ticket_summary	= sql_escape($details_set[6]);
						$detail_to_add	=  array($ticket_id, $assigned_to, $issue_list, $documented_issue, $account_name, $ticket_details, $ticket_category, $ticket_summary);
										
						$this->add_records_to_db($detail_to_add, self::DETAILS_ADD);
						
						$total_count++;
					}
				}
			}
			
			if($total_count >= $count['count'])
				$done = true;
		}
		return true;
	}
	/*
	 * Takes attachment and saves locally then DL to media server. 
	 * 
	 * @param Str; href to access attachment
	 * @param Str; attachment name
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function add_attachment_to_ftp($href, $attachment_name = null)
	{
		if(is_null($attachment_name))
		{
			$results = strip_tags(file_get_contents($href));
			return $results;
		}
		else
		{
			$buffer			= @file_get_contents($href);
			$local_file		= dirname(__FILE__)."/../../common/cache/ticket/{$attachment_name}";
			$media_location	= $this->media_location.$attachment_name;
			$resource		= $GLOBALS['media_host'];
			$user 			= $GLOBALS['media_server_dl_user'];
			$pass			= $GLOBALS['media_server_dl_pass'];
			$local_results 	= @file_put_contents($local_file,$buffer);
			
			if($local_results)
			{
				if(!is_resource($this->attachment_ftp_connect))
					$this->attachment_ftp_connect = ftp_connect($resource);
				
				if(is_resource($this->attachment_ftp_connect))
				{
					if($this->login_ftp == null)
						$this->login_ftp = ftp_login($this->attachment_ftp_connect, $user, $pass);
	
					if($this->login_ftp)
					{
						if(file_exists($local_file))
						{
							if(filesize($local_file) > 0)
							{
								$handle = fopen($local_file, "r");
								
								if(ftp_fput($this->attachment_ftp_connect, $media_location, $handle, FTP_BINARY, 0))
								{
									echo "successfully added image for {$attachment_name} \r\n";
									
									return true;
								} 
								else
									echo "error downloading {$url} to {$resource}\n";
							}
							else
								echo "File size not correct \r\n";
						}
						else
							echo "Local file not present \r\n";
					}
					else
						echo "Unable to login \r\n";
				}
				else
					echo "Unable to connect \r\n";
			}
			else
				echo "Error saving file locally: {$href} \r\n";
		}
			
		return false; 
	}
	/*
	 * Loops through building URL and accesses the API, returns ticket data, filters to desired information and sends to add_records function
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function mine_ticket_data()
	{
		$ticket_id_list 			= array();
		$statuses_to_add			= array();
		$tickets_to_add 			= array();
		$attachments_to_add			= array();
		$status_id_list				= array();
		$customer_id_list			= array();
		
		//get list of current tickets
		$ticket_id_sql = 	"	SELECT
									ticket_id
								FROM
									$this->db_name.`ticket_ticket`	
							";
						
		$result = query($ticket_id_sql);
		
		if($result)
		{
			foreach($result  as $ticket_id)
				array_push($ticket_id_list, $ticket_id['ticket_id']);				
		}
		
		//get list of current statuses
		$status_id_sql = 	"	SELECT
									status_id
								FROM
									$this->db_name.`ticket_status`	
							";
						
		$result = query($status_id_sql);
		
		if($result)
		{
			foreach($result  as $status_id)
				array_push($status_id_list, $status_id['status_id']);				
		}
		
		//get list of customer ID's
		$customer_id_sql = 	"	SELECT
									ticket_id
								FROM
									$this->db_name.`ticket_customer`	
							";
						
		$result = query($customer_id_sql);
		
		if($result)
		{
			foreach($result  as $customer_id)
				array_push($customer_id_list, $customer_id['ticket_id']);				
		}

		$url				= $this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Ticket?".$this->url_total."&".$this->url_token.$this->token;
		$data 				= $this->retrieve_ticket_data($url);
		$account_attr		= $data->attributes();
		$this->page_size	= $account_attr->total;
		$done				= false;
		$page_num			= 1;
		$calls				= ceil($this->page_size / 100);
		
		if($this->page_size > 100)
			$this->page_size = 100;
		
		while(!$done)
		{
			$url	= $this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Ticket?".$this->url_page_size.$this->page_size."&".$this->url_start_page.$page_num."&".$this->url_token.$this->token;
			$data 	= $this->retrieve_ticket_data($url);
			
			if($data)
			{
				foreach($data->Ticket as $ticket)
				{
					$ticket_attr = $ticket->attributes();
					$ticket_id = sql_escape($ticket_attr['id']);
				
					if(!in_array($ticket_id, $ticket_id_list))
					{
						array_push($ticket_id_list, $ticket_id);
						
						if(!empty($ticket->Ticket_Customer->Customer))
						{
							$customer_attr	= $ticket->Ticket_Customer->Customer->attributes();
							$customer_id	= sql_escape($customer_attr['id']);
								
							if(!in_array($customer_id, $customer_id_list))
								$customer_id = 0;									
						}
						else
							$customer_id = 0;
							
						if(in_array($customer_id, $customer_id_list))
						{
							$create_date	= sql_escape(strtotime($ticket->Date_Created));
							$update_date	= sql_escape(strtotime($ticket->Date_Updated));
							$ticket_href	= sql_escape($ticket_attr['href']);
							$status_attr	= $ticket->Ticket_Status->Status->attributes();
							$status_id		= sql_escape($status_attr['id']);
							$status_name	= sql_escape($ticket->Ticket_Status->Status->Name);	
							$ticket_number	= sql_escape($ticket->Ticket_Number);
							
							if(!empty($ticket->Ticket_Attachments))
							{
								foreach($ticket->Ticket_Attachments->Attachment as $attachment)
								{
									$ticket_attachment_attr = $attachment->attributes();
									$ticket_attachment_href	= sql_escape($ticket_attachment_attr->href);
									$ticket_attachment_guid = sql_escape($attachment->Guid);
									$attachment_name_pre	= sql_escape($attachment->Name);
									$attachment_name_exp	= explode(".", $attachment_name_pre);
									$attachment_name_ext	= array_pop($attachment_name_exp);
									
									//check to make sure .php, .phtml, .js files add a .txt for security 
									if(strcasecmp($attachment_name_ext,'php') == 0 || strcasecmp($attachment_name_ext,'phtml') == 0 || strcasecmp($attachment_name_ext,'js') == 0)
										$attachment_name_ext = $attachment_name_ext.".txt";
										
									$attachment_name		= $ticket_id."_".$ticket_attachment_guid.".".sql_escape($attachment_name_ext);
									$attachmet_to_add		= "(NULL, '1',NULL,'{$ticket_id}', '{$ticket_attachment_guid}', '{$attachment_name_pre}')";
									
									$return = $this->add_attachment_to_ftp($ticket_attachment_href, $attachment_name);
									
									if($return === true)
										array_push($attachments_to_add, $attachmet_to_add);
								}
							}
														
							$ticket_to_add = "(NULL, '{$ticket_id}','{$create_date}','{$update_date}','{$ticket_number}', '{$ticket_href}', '{$customer_id}', '{$status_id}')"; 
														
							if (!in_array($status_id, $status_id_list))
							{
								$status_to_add 		= "('{$status_id}', '{$status_name}')";
								array_push($statuses_to_add, $status_to_add);								
								$status_id_list[] 	= $status_id;
							}
							array_push($tickets_to_add, $ticket_to_add);
						}
					}
				}
				
				if(!empty($statuses_to_add))
					$this->add_records_to_db($statuses_to_add, self::STATUS_ADD);
								
				if(!empty($tickets_to_add))
					$this->add_records_to_db($tickets_to_add, self::TICKET_ADD);
			
				if(!empty($attachments_to_add))
					$this->add_records_to_db($attachments_to_add, self::ATTACHMENT_ADD);
			}				
			
			$page_num++;
			$tickets_to_add 			= array();
			$attachments_to_add			= array();
			$statuses_to_add			= array();
				
			if($calls < $page_num)
				$done = true;
		}
		
		if(is_resource($this->attachment_ftp_connect))
			ftp_close($this->attachment_ftp_connect);
				
		return true;
	}
	/*
	 * Loops through building URL and accesses the API, returns data, filters to desired information and sends to add DB function
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function poll_customer_data()
	{
		$this->poll_account_data();
		$customer_id_list 		= array();
		$customers_to_add 		= array();
		$customers_to_update	= array();
		$sql 					= 	"	SELECT
											ticket_id, first_name, last_name, email, ticket_account_id
										FROM
											$this->db_name.`ticket_customer`	
									";
		$local_results 			= query($sql);
		
		if($local_results)
		{
			foreach($local_results as $customer_id)
				$customer_id_list[] = $customer_id['ticket_id'];			
		}
		
		//builds the url to hit ticket api
		$url	= $this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Customer?".$this->url_total."&".$this->url_token.$this->token;
		$data 	= $this->retrieve_ticket_data($url);
		
		if($data)
		{
			$array_attr 		= $data->attributes();					//breaks down to an array
			$this->page_size 	= $array_attr->total;					//sets the page size
			$calls				= ceil(($this->page_size )/(100)); 		//calculates the total calls that should occur
			
			if($this->page_size > 100)
				$this->page_size = 100;
			
			$done 		= false;
			$page_num	= 1;
			
			while(!$done)
			{
				$url 			= $this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Customer?".$this->url_page_size.$this->page_size."&".$this->url_start_page.$page_num."&".$this->url_token.$this->token;
				$data 			= $this->retrieve_ticket_data($url);
				$arr_of_objects = $data->Customer;
				
				foreach($arr_of_objects as $remote_single_customer)
				{
					$single_customer_attr	= $remote_single_customer->attributes();
					$remote_ticket_id 		= sql_escape($single_customer_attr->id);
					$remote_ticket_href 	= sql_escape($single_customer_attr->href);
					$remote_email			= sql_escape($remote_single_customer->Email);
					$remote_first_name		= sql_escape($remote_single_customer->First_Name);
					$remote_last_name		= sql_escape($remote_single_customer->Last_Name);
					
					if(!empty($remote_single_customer->Account->Account))
					{
						$account_attr				= $remote_single_customer->Account->Account->attributes();
						$remote_ticket_account_id	= sql_escape($account_attr->id);
					}
					else
						$remote_ticket_account_id = '0';
					
					//branch for adding to DB or updating current data in DB					
					if(!in_array($remote_ticket_id, $customer_id_list))
					{
						$customer_info 		= "(NULL, '{$remote_ticket_id}', '{$remote_first_name}', '{$remote_last_name}', '{$remote_email}', '{$remote_ticket_href}', '{$remote_ticket_account_id}')";
						
						array_push($customer_id_list, $remote_ticket_id);
						array_push($customers_to_add, $customer_info);
					}
					else
					{
						foreach($local_results as $local_customer)
						{
							if($remote_ticket_id == $local_customer['ticket_id'])
							{
								if($remote_first_name != $local_customer['first_name'])
									$update_customer['first_name'] = sql_escape($remote_first_name);
								
								if($remote_last_name != $local_customer['last_name'])
									$update_customer['last_name'] = sql_escape($remote_last_name);
																
								if($remote_email != $local_customer['email'])
									$update_customer['email'] = sql_escape($remote_email);
								
								if($remote_ticket_account_id != $local_customer['ticket_account_id'])
									$update_customer['ticket_account_id'] = sql_escape($remote_ticket_account_id);
								
								if(!empty($update_customer))
								{
									$update_customer['ticket_id'] = $remote_ticket_id;
								
									array_push($customers_to_update, $update_customer);
									
									$update_customer = array();
								}
							}
						}					
					}
				}
				
				if(!empty($customers_to_add))
				{
					if($page_num == 1)
					{
						if(!in_array(0, $customer_id_list))
						{
							$customer_info 	= "(NULL,'0', 'No', 'Name', 'test@test.com', 'NONE', '0')";
							array_push($customers_to_add, $customer_info);
						}					
					}
					$this->add_records_to_db($customers_to_add, self::CUSTOMER_ADD);
				}
				
				if(!empty($customers_to_update))
					$this->add_records_to_db($customers_to_update, self::CUSTOMER_UPDATE);
				
				$page_num++;				
				$customers_to_add 		= array();
				$customers_to_update	= array();
				
				if($calls < $page_num)
					$done = true;						
			}
			
		}
		
		echo "end of customer polling";
		return true;
	}
	/*
	 * requests data from ticket, decodes and prepares return for use within the class
	 * 
	 * @param string; url to request data
	 * @return mixed; returns all data pertaining to request
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function retrieve_ticket_data($url)
	{
		$counter = 0;
		do
		{
			$this->total_ticket_calls++;
			$data	= @file_get_contents($url);
			
			if($data)
			{
				$data = simplexml_load_string($data);
				
				return $data;
			}
			
			sleep(2);
			$counter++;
		}		
		while($counter < 5);
	}
	/*
	 * Takes provided ticket object and reduces to an array of actions available for provided ticket
	 * 
	 * @param mixed; ticket object
	 * @return mixed; all available action in action => url format
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function get_action_urls($object)
	{
		$unclean_actions = $object->Actions;
		
		foreach($unclean_actions as $action)
		{
			foreach($action as $attributes)
			{
				$action_name			= sql_escape($attributes['name']);
				$action_href			= sql_escape($attributes['href']);
				$actions[$action_name]	= $action_href; 
			}			
		}
		
		return $actions;
	}
	/*
	 * Builds URL to request list of all tickets updated today (00:00:00 to 23:59:59 of current day)
	 * 
	 * @return mixed; array of ticket objects
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function tickets_updated_today()
	{
		//build URL  //UTC_minus_5_
		$this->page_size	= 100;
		$url 				= $this->url_API_hostname.$this->url_api.$this->url_version.$this->url_account_id.$this->url_dept_id."Ticket?"."Date_Updated=_today_"."&".$this->url_history."&".$this->url_page_size.$this->page_size."&".$this->url_token.$this->token;		
		$results 			= $this->retrieve_ticket_data($url);
		
		return $results;
	}
	/*
	 * Gets a list of tickets updated today and updates DB with only items that were changed 
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function poll_tickets()
	{
		$attachments_to_add	= array();
		$ticket_history 	= array();
		$tickets_to_add		= array();
		$results	 		= $this->tickets_updated_today();
		
		if($results)
		{
			foreach($results->Ticket as $ticket)
			{
				$RemoteTicketSystemTicket = new TicketSystemTicket();
				
				$ticket_attr 			= $ticket->attributes();
				$ticket_id				= sql_escape($ticket_attr->id);
				$ticket_href			= sql_escape($ticket_attr->href);
				
				$single_ticket			= $this->get_single_ticket($ticket_href);
				
				$create_date			= sql_escape(strtotime($ticket->Date_Created));
				$update_date			= sql_escape(strtotime($ticket->Date_Updated));
				
				if(!empty($single_ticket->Assigned_To->Csr))
				{
					$assigned_to_attr = $single_ticket->Assigned_To->Csr->attributes();
					$RemoteTicketSystemTicket->set_assigned_to(sql_escape($assigned_to_attr->id));
				}
				else		
					$RemoteTicketSystemTicket->set_assigned_to(sql_escape(0));
				
				$customer_attr	= $single_ticket->Ticket_Customer->Customer->attributes();
				$status_attr	= $single_ticket->Ticket_Status->Status->attributes();
				
				foreach($single_ticket->Custom_Field[1] as $documented_issue)
				{
					$documented_issue_attr = $documented_issue->attributes();
					
					if(!empty($documented_issue_attr->selected))
					{
						$documented_issue = sql_escape($documented_issue->Value);
						
						$RemoteTicketSystemTicket->set_documented_issue($documented_issue);
					}
					
					if($documented_issue == 'Yes')
					{
						foreach($single_ticket->Custom_Field[0] as $option)
						{
							$option_attr = $option->attributes();
								
							if(!empty($option_attr->selected))
							{
								$issue_list	= sql_escape($option->Value);
									
								$RemoteTicketSystemTicket->set_issue_list($issue_list);
							}
						}
					}
				}
				
				foreach($single_ticket->Custom_Field[5]->Option as $ticket_category)
				{
					$ticket_category_attr = $ticket_category->attributes();
					
					if(!empty($ticket_category_attr['selected']))
						$RemoteTicketSystemTicket->set_ticket_category(sql_escape($ticket_category->Value));						
				}
				
				//Look for attachements and add to ticket object
				if(!empty($ticket->Ticket_Attachments))
				{
					$TicketAttachment = new TicketAttachment;
					
					foreach($ticket->Ticket_Attachments->Attachment as $attachment)
					{
						$ticket_attachment_attr = $attachment->attributes();
						$TicketAttachmentItem = new TicketAttachmentItem();
						
						$TicketAttachmentItem->set_attachment_location(1);
						$TicketAttachmentItem->set_history_id('NULL');
						$TicketAttachmentItem->set_ticket_id($ticket_id);
						$TicketAttachmentItem->set_attachment_guid($attachment->Guid);
						$TicketAttachmentItem->set_attachment_name($attachment->Name);
						$TicketAttachmentItem->set_attachment_href(sql_escape($ticket_attachment_attr->href));
						
						$TicketAttachment->append($TicketAttachmentItem);
					}
					$RemoteTicketSystemTicket->set_TicketAttachment($TicketAttachment);
				}
				
				$RemoteTicketSystemTicket->set_ticket_ticket_id($ticket_id);
				$RemoteTicketSystemTicket->set_create_date($create_date);
				$RemoteTicketSystemTicket->set_update_date($update_date);
				$RemoteTicketSystemTicket->set_ticket_number(sql_escape($single_ticket->Ticket_Number));
				$RemoteTicketSystemTicket->set_ticket_ticket_href($ticket_href);
				$RemoteTicketSystemTicket->set_ticket_customer_id(sql_escape($customer_attr->id));
				$RemoteTicketSystemTicket->set_status_id(sql_escape($status_attr->id));
				$RemoteTicketSystemTicket->set_ticket_details(sql_escape($single_ticket->Custom_Field[4]));
				$RemoteTicketSystemTicket->set_ticket_summary(sql_escape($single_ticket->Custom_Field[6]));
				$RemoteTicketSystemTicket->set_account_name(sql_escape($single_ticket->Custom_Field[3]));
				
				if($single_ticket->ActionHistory->History)
				{
					$TicketHistory = new TicketHistory();
					
					foreach($single_ticket->ActionHistory->History as $history_item)
					{
						$TicketHistoryItem	= new TicketHistoryItem();
						$history_item_attr 		= $history_item->attributes();
						$action_id_attr			= $history_item->Action->attributes();
						$old_status_attr		= $history_item->Old_Status->Status->attributes();
						$new_status_attr		= $history_item->New_Status->Status->attributes();
						
						if(empty($history_item->Action_Performer->Csr))
							$action_performer = 0;
						else
						{
							$action_perf_attr = $history_item->Action_Performer->Csr->attributes();
							$action_performer = sql_escape($action_perf_attr->id);
						}
						
						if(empty($history_item->Action_Target->Csr))
							$action_target = 0;
						else
						{
							$action_target_attr = $history_item->Action_Target->Csr->attributes();
							$action_target 		= sql_escape($action_target_attr->id);
						}
						
						$find		= 'Click Here for Details';
						$html 		= $history_item->Comments;
						$results 	= strpos($html, $find);
						
						if(!empty($history_item->History_Attachments))
						{
							$TicketAttachment = new TicketAttachment();
							
							foreach($history_item->History_Attachments->Attachment as $attachment)
							{
								$is_comment 		= false;
								$attachment_name	= false;
								$attachment_attr 	= $attachment->attributes();
								$comment_html		= 'Comment.html';
								$name				= sql_escape($attachment->Name);
								
								if($name == $comment_html)
									$attachment_name = true;
								
								if($attachment_name == true && $results != 0)
									$is_comment = true;
									
								//if there is a large comment and ticket has added it as an attachment, this will place the text into the comment field 
								if($is_comment === true)
								{
									$attachment_href	= sql_escape($attachment_attr->href);
									$return 			= $this->add_attachment_to_ftp($attachment_href);
									$TicketHistoryItem->set_comments($return);
								}
								else
								{
									$TicketAttachmentItem = new TicketAttachmentItem();
									$TicketAttachmentItem->set_attachment_location(2);
									$TicketAttachmentItem->set_history_id(sql_escape($history_item_attr->id));
									$TicketAttachmentItem->set_ticket_id($ticket_id);
									$TicketAttachmentItem->set_attachment_guid(sql_escape($attachment->Guid));
									$TicketAttachmentItem->set_attachment_name(sql_escape($attachment->Name));
									$TicketAttachmentItem->set_attachment_href(sql_escape($attachment_attr->href));
									
									$TicketAttachment->append($TicketAttachmentItem);
								}
							}
							$TicketHistoryItem->set_TicketAttachment($TicketAttachment);
						}
						
						$TicketHistoryItem->set_history_ticket_id(sql_escape($history_item_attr->id));
						$TicketHistoryItem->set_ticket_ticket_id($ticket_id);
						$TicketHistoryItem->set_action_id(sql_escape($action_id_attr->id));
						$TicketHistoryItem->set_old_status(sql_escape($old_status_attr->id));
						$TicketHistoryItem->set_new_status(sql_escape($new_status_attr->id));
						$TicketHistoryItem->set_action_performer($action_performer);					
						$TicketHistoryItem->set_action_target($action_target);
						
						//if the comment is not in attachment form, go ahead and set it
						if($results == 0)
							$TicketHistoryItem->set_comments(sql_escape($history_item->Comments));
						
							//$elements 	= array();
							//$dom 		= new DOMDocument; 
							//$dom->loadHTML($html); 
														
							//foreach ($dom->getElementsByTagName('a') as $node) 
							//	$href = $node->getAttribute( 'href' );
						  	
						$TicketHistoryItem->set_action_date(strtotime(sql_escape($history_item->Action_Date)));
						
						$TicketHistory->append($TicketHistoryItem);
					}
					$RemoteTicketSystemTicket->set_TicketHistory($TicketHistory);
				}
				
				$TicketSystemTicketDataModel= new TicketSystemTicketDataModel();
				$LocalTicketSystemTicket	= $TicketSystemTicketDataModel->get($ticket_id);
				$id 						= $LocalTicketSystemTicket->ticket_ticket_id();
				
				if(empty($id))
				{
					$sql = 	"	SELECT
									ticket_id
								FROM
									{$this->db_name}.ticket_customer
								WHERE
									ticket_id = {$RemoteTicketSystemTicket->ticket_customer_id()} 
							";
					$customer_results = query($sql);
					
					//check if the ticket owner(customer) is in the db, if not, then add
					if(empty($customer_results))
						$customer_results = $this->poll_customer_data();
					else
						$customer_results = true;
						
					if($customer_results === true)
					{
						if($RemoteTicketSystemTicket->ticket_ticket_id())
						{
							if($RemoteTicketSystemTicket->TicketAttachment())
							{
								foreach($RemoteTicketSystemTicket->TicketAttachment() as $attachment)
								{
									$attachment_name_pre	= sql_escape($attachment->attachment_name());
									$attachment_name_exp	= explode(".", $attachment_name_pre);
									$attachment_name_ext	= array_pop($attachment_name_exp);
									
									//check to make sure .php, .phtml, .js files add a .txt for security 
									if(strcasecmp($attachment_name_ext,'php') == 0 || strcasecmp($attachment_name_ext,'phtml') == 0 || strcasecmp($attachment_name_ext,'js') == 0)
										$attachment_name_ext = $attachment_name_ext.".txt";
										
									$attachment_name		= $attachment->ticket_id()."_".$attachment->attachment_guid().".".sql_escape($attachment_name_ext);
									$return 				= $this->add_attachment_to_ftp($attachment->attachment_href(), $attachment_name);
									
									if($return === true)
									{
										$attachment_item = "(NULL, '{$attachment->attachment_location()}', '{$attachment->history_id()}', '{$attachment->ticket_id()}', '{$attachment->attachment_guid()}', '{$attachment->attachment_name()}')";
										array_push($attachments_to_add, $attachment_item);
									}
								}
							}
						
							$ticket_to_add = "(NULL, '{$RemoteTicketSystemTicket->ticket_ticket_id()}', '{$RemoteTicketSystemTicket->create_date()}', '{$RemoteTicketSystemTicket->update_date()}', '{$RemoteTicketSystemTicket->ticket_number()}', '{$RemoteTicketSystemTicket->ticket_ticket_href()}', '{$RemoteTicketSystemTicket->ticket_customer_id()}', '{$RemoteTicketSystemTicket->status_id()}')";
							$detail_to_add	=  array($RemoteTicketSystemTicket->ticket_ticket_id(), $RemoteTicketSystemTicket->assigned_to(), $RemoteTicketSystemTicket->issue_list(), $RemoteTicketSystemTicket->documented_issue(), $RemoteTicketSystemTicket->account_name(),$RemoteTicketSystemTicket->ticket_details(), $RemoteTicketSystemTicket->ticket_category(), $RemoteTicketSystemTicket->ticket_summary());
							array_push($tickets_to_add, $ticket_to_add);						
							
							if($RemoteTicketSystemTicket->TicketHistory())
							{
								foreach($RemoteTicketSystemTicket->TicketHistory() as $history_member)
								{
									if($history_member->TicketAttachment())
									{
										foreach($history_member->TicketAttachment() as $attachment)
										{
											$attachment_name_pre	= sql_escape($attachment->attachment_name());
											$attachment_name_exp	= explode(".", $attachment_name_pre);
											$attachment_name_ext	= array_pop($attachment_name_exp);
											
											//check to make sure .php, .phtml, .js files add a .txt for security 
											if(strcasecmp($attachment_name_ext,'php') == 0 || strcasecmp($attachment_name_ext,'phtml') == 0 || strcasecmp($attachment_name_ext,'js') == 0)
												$attachment_name_ext = $attachment_name_ext.".txt";
												
											$attachment_name 	= $attachment->ticket_id()."_".$attachment->attachment_guid().".".sql_escape($attachment_name_ext);
											$return 			= $this->add_attachment_to_ftp($attachment->attachment_href(), $attachment_name);
											
											if($return === true)
											{
												$attachment_item		= "(NULL, '{$attachment->attachment_location()}', '{$attachment->history_id()}', '{$attachment->ticket_id()}', '{$attachment->attachment_guid()}', '{$attachment->attachment_name()}')";
												array_push($attachments_to_add, $attachment_item);
											}
										}
									}
									$history_to_add = "(NULL, '{$history_member->ticket_ticket_id()}', '{$history_member->history_ticket_id()}', '{$history_member->action_id()}', '{$history_member->old_status()}', '{$history_member->new_status()}', '{$history_member->action_performer()}', '{$history_member->action_target()}', '{$history_member->comments()}', '{$history_member->action_date()}')";
									array_push($ticket_history, $history_to_add);
								}
							}
							
							if(!empty($tickets_to_add))
								$this->add_records_to_db($tickets_to_add, self::TICKET_ADD);
							
							if(!empty($detail_to_add))						
								$this->add_records_to_db($detail_to_add, self::DETAILS_ADD);
								
							if(!empty($ticket_history))
								$this->add_records_to_db($ticket_history, self::HISTORY_ADD);
								
							if(!empty($attachments_to_add))
								$this->add_records_to_db($attachments_to_add, self::ATTACHMENT_ADD);
								
							$ticket_history 	= array();
							$tickets_to_add 	= array();
							$detail_to_add 		= array();
							$attachments_to_add	= array();
						}
					}	
				}
				else
					$this->compair_tickets($RemoteTicketSystemTicket, $LocalTicketSystemTicket);
			}
		}
		
		echo "End of Polling";
	}
	/*
	 * Compairs the local ticket object and the remote ticket object and determines which values have changed. 
	 * 
	 * @param mixed; local ticket object
	 * @param mixed; remote ticket object
	 * @return mixed; array of values that will be updated in the database for the ticket
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function compair_tickets($remote, $local)
	{
		$local_history_id_list	= array();
		$items_to_add			= array();
		$history_to_add			= array();
		$attachments_to_add		= array();
		
		if($remote->update_date() != $local->update_date())
		{
			$items_to_add['update_date'] = sql_escape($remote->update_date());
			
			if($remote->ticket_customer_id() != $local->ticket_customer_id())
				$items_to_add['customer_id'] = sql_escape($remote->ticket_customer_id());
			
			if($remote->status_id() != $local->status_id())
				$items_to_add['status_id'] = sql_escape($remote->status_id());
				
			if($remote->assigned_to() != $local->assigned_to())
				$items_to_add['assigned_to'] = sql_escape($remote->assigned_to());
			
			if($remote->issue_list() != $local->issue_list())
				$items_to_add['issue_list'] = sql_escape($remote->issue_list());
			
			if($remote->documented_issue() != $local->documented_issue())
				$items_to_add['documented_issue'] = sql_escape($remote->documented_issue());
			
			if($remote->account_name() != $local->account_name())
				$items_to_add['account_name'] = sql_escape($remote->account_name());
			
			if($remote->ticket_details() != $local->ticket_details())
				$items_to_add['ticket_details'] = sql_escape($remote->ticket_details());
				
			if($remote->ticket_category() != $local->ticket_category())
				$items_to_add['ticket_category'] = sql_escape($remote->ticket_category());
			
			if($remote->ticket_summary() != $local->ticket_summary())
				$items_to_add['ticket_summary'] =  sql_escape($remote->ticket_summary());
				
			if($remote->TicketHistory())
			{
				foreach($local->TicketHistory() as $local_history)
				{
					$history_id = $local_history->history_ticket_id();
					
					array_push($local_history_id_list, $history_id);
				}
				
				foreach($remote->TicketHistory() as $remote_history)
				{
					$history_id = $remote_history->history_ticket_id();
					
					if(!in_array($history_id, $local_history_id_list))
					{
						$ticket_ticket_id 	= sql_escape($remote_history->ticket_ticket_id());
						$history_ticket_id 	= sql_escape($remote_history->history_ticket_id());
						$action_id				= sql_escape($remote_history->action_id());
						$old_status 			= sql_escape($remote_history->old_status());
						$new_status				= sql_escape($remote_history->new_status());
						$action_performer		= sql_escape($remote_history->action_performer());
						$action_target			= sql_escape($remote_history->action_target());
						$comments				= sql_escape($remote_history->comments());
						$action_date			= sql_escape($remote_history->action_date());
						
						if($remote_history->TicketAttachment())
						{
							foreach($remote_history->TicketAttachment() as $attachment)
							{
								$attachment_location 	= sql_escape($attachment->attachment_location());
								$history_id				= sql_escape($attachment->history_id());
								$ticket_id 				= sql_escape($attachment->ticket_id());
								$attachment_name_pre	= sql_escape($attachment->attachment_name());
								$attachment_name_exp	= explode(".", $attachment_name_pre);
								$attachment_name_ext	= array_pop($attachment_name_exp);
								
								//check to make sure .php, .phtml, .js files add a .txt for security 
								if(strcasecmp($attachment_name_ext,'php') == 0 || strcasecmp($attachment_name_ext,'phtml') == 0 || strcasecmp($attachment_name_ext,'js') == 0)
									$attachment_name_ext = $attachment_name_ext.".txt";
									
								$attachment_guid		= sql_escape($attachment->attachment_guid());
								$attachment_name		= $ticket_id."_".$attachment_guid.".".sql_escape($attachment_name_ext);
								$attachment_href		= sql_escape($attachment->attachment_href());
								$attachment_item 		= "(NULL, '{$attachment_location}', '{$history_id}', '{$ticket_id}', '{$attachment_guid}', '{$attachment_name}')";
								$return 				= $this->add_attachment_to_ftp($attachment_href, $attachment_name);
								
								if($return == true)
									array_push($attachments_to_add, $attachment_item);
							}
						}
						$history_item = "(NULL, '{$ticket_ticket_id}', '{$history_ticket_id}', '{$action_id}', '{$old_status}', '{$new_status}', '{$action_performer}', '{$action_target}', '{$comments}', '{$action_date}')";
						array_push($history_to_add, $history_item);
					}
				}
				
				if(!empty($history_to_add))
					$this->add_records_to_db($history_to_add, self::HISTORY_ADD);
				
				if(!empty($attachments_to_add))
					$this->add_records_to_db($attachments_to_add, self::ATTACHMENT_ADD);
			}
			
			if(!empty($items_to_add))
			{			
				$items_to_add['ticket_id'] = $remote->ticket_ticket_id();
				
				$this->add_records_to_db($items_to_add, self::TICKET_UPDATE);
			}
		}
		return true;
	}
	/*
	 * used in poll_tickets to get entire ticket including details and history
	 * 
	 * @param str; href for individual ticket
	 * @return mixed; ticket object
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function get_single_ticket($href)
	{
		$url 		= $href."?".$this->url_history."&".$this->url_token.$this->token;
		$results 	= $this->retrieve_ticket_data($url);
		
		return $results;
	}
	/*
	 * used to add attachment location in db
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function add_att_locations()
	{	
		//@todo add truncate table first 
		$sql = 	"	INSERT
					INTO
						`{$this->db_name}`.ticket_att_location
						(location_id, location_name)
					VALUES
						(1,'Details'),
						(2,'History')
				";
		
		query($sql);
	}
	/*
	 * Designed to direct the query to the appropriate db
	 * 
	 * @param mixed; array of items used for adding to db
	 * @param str;  constant to determine which switch tofollow 
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function add_records_to_db($array, $add_type)
	{
		switch ($add_type)
		{
			case self::CSR_ADD:
				
				$email = 'unknown@unknown.com';
				$email2 = sql_escape($email);
				$str = "(NULL, '0', 'System', '{$email2}','None', '1')";
				array_push($array, $str);
				
				$qry = implode(",",$array);
				$sql = 	"	INSERT
							INTO
								`{$this->db_name}`.ticket_csr
								(csr_id, ticket_id, full_name, email, ticket_href, ticket_status)
							VALUES
								{$qry}
						";
								
				query($sql);
			break;
			
			case self::CUSTOMER_ADD:
				
				$qry = implode(",",$array);
				$sql = 	"	INSERT
							INTO
								`{$this->db_name}`.ticket_customer
								(customer_id, ticket_id, first_name, last_name, email, ticket_href, ticket_account_id)
							VALUES
								{$qry}
						";
								
				query($sql);
			break;
			
			case self::ACCOUNT_ADD:
								
				$qry = implode(",",$array);
				$sql = 	"	INSERT
							INTO
								`{$this->db_name}`.ticket_account
								(account_id, ticket_id, account_name, ticket_href)
							VALUES
								{$qry}
						";
				query($sql);
			break;
				
			case self::TICKET_ADD:
				
				$qry = implode(",",$array);
				$sql = 	"	INSERT
							INTO
								`{$this->db_name}`.`ticket_ticket`
								(ticket_id, ticket_id, create_date, update_date, ticket_number, ticket_href, customer_id, status)
							VALUES
								{$qry}
						";
								
				query($sql);
			break;
			
			case self::STATUS_ADD:
				
				$qry = implode(",",$array);
				$sql = 	"	INSERT
							INTO
								`{$this->db_name}`.ticket_status
									(status_id, status_name)								
							VALUES
								{$qry}
						";
								
				query($sql);
			break;
			
			case self::ATTACHMENT_ADD:
				
				$qry = implode(",",$array);
				$sql = 	"	INSERT
							INTO
								`{$this->db_name}`.ticket_attachment
								(attachment_id, attachment_location, history_id, ticket_id, attachment_guid, attachment_name)
								
							VALUES
								{$qry}
						";
								
				query($sql);
			break;

			case self::DETAILS_ADD:
			
				$sql =	"	UPDATE
								`{$this->db_name}`.`ticket_ticket` 
							SET 
								`assigned_to`='{$array[1]}', `issue_list`='{$array[2]}', `documented_issue`='{$array[3]}', `account_name`='{$array[4]}', `ticket_details`='{$array[5]}', `ticket_category`='{$array[6]}', `ticket_summary`='{$array[7]}'  
						WHERE
								`ticket_id` = {$array[0]}
						";
			
				query($sql);
			break;
			
			case self::HISTORY_ADD:
			
				$qry = implode(",",$array);
				$sql =	"	INSERT
							INTO
								`{$this->db_name}`.`ticket_history` 
								(history_id, ticket_ticket_id, history_ticket_id, action_id, old_status, new_status, action_performer, action_target, comments, action_date)  
							VALUES
								{$qry}
						";
			
				query($sql);
			break;
			
			case self::ACTION_ADD:
			
				$qry = implode(",",$array);
				$sql =	"	INSERT
							INTO
								`{$this->db_name}`.`ticket_action` 
								(action_id, action_name)
							VALUES
								{$qry}
						";
			
				query($sql);
			break;
			
			case self::TICKET_UPDATE:
				
				$arr_set 		= array();
								
				foreach($array as $key => $value)
				{
					if($key == 'ticket_id')
						$where = "{$key} = '{$value}'";
					else
					{
						$set_str = "{$key} = '{$value}'";
						array_push($arr_set, $set_str);
					}
				}
				
				$set		= implode(",",$arr_set);
				
				$sql = 	"	UPDATE
								$this->db_name.ticket_ticket
							SET
								{$set}
							WHERE
								{$where}
						";
								
				query($sql);
			break;
			
			case self::ACCOUNT_UPDATE:
				
				foreach($array as $account)
				{
					$arr_set 		= array();
					
					foreach($account as $key => $value)
					{
						if($key == 'ticket_id')
							$where = "{$key} = '{$value}'";
						else
						{
							$set_str = "{$key} = '{$value}'";
							array_push($arr_set, $set_str);
						}
					}
					
					$set = implode(",",$arr_set);
					$sql = 	"	UPDATE
									$this->db_name.ticket_account
								SET
									{$set}
								WHERE
									{$where}
							";
								
					query($sql);
				}
				
			break;
			
			case self::CSR_UPDATE:
				
				$arr_set = array();
				
				foreach($array as $csr)
				{
					foreach($csr as $key => $value)
					{
						if($key == 'ticket_id')
							$where = "{$key} = '{$value}'";
						else
						{
							$set_str = "{$key} = '{$value}'";
							array_push($arr_set, $set_str);
						}
					}
					
					$set = implode(",",$arr_set);
					$sql = 	"	UPDATE
									$this->db_name.ticket_csr
								SET
									{$set}
								WHERE
									{$where}
							";
									
					query($sql);
					
					$arr_set = array();
				}
				
			break;
			
			case self::CUSTOMER_UPDATE:
				
				$arr_set = array();
				
				foreach($array as $customer)
				{
					foreach($customer as $key => $value)
					{
						if($key == 'ticket_id')
							$where = "{$key} = '{$value}'";
						else
						{
							$set_str = "{$key} = '{$value}'";
							array_push($arr_set, $set_str);
						}
					}
					
					$set = implode(",",$arr_set);
					$sql = 	"	UPDATE
									$this->db_name.ticket_customer
								SET
									{$set}
								WHERE
									{$where}
							";

					query($sql);
					
					$arr_set = array();
				}
				
			break;
			
			
	#########################################################
							
			default:
				echo "No Query Ready!";
			break;
		}
	}
}

?>
