<?php
include_once 'class.TicketHistory.php';
include_once 'class.TicketAttachment.php';
/*
 * Main ticket container
 * 
 * @author Patrick Geddie <geddiep@yahoo.com>  
*/
class TicketSystemTicket 
{
	/*
	 * id assigned by ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_ticket_id;
	/*
	 * date created in ticket.
	 *
	 * @var int; unix time
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $create_date;
	/*
	 * date updated in ticket.
	 *
	 * @var int; unix time
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $update_date;
	/*
	 * complete ticket number assigned by ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_number;
	/*
	 * ticket href used to get ticket details from ticket.
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_ticket_href;
	/*
	 * id of customer associated with ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_customer_id;
	/*
	 * status id of ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $status_id;
	/*
	 * id of csr assigned to the ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $assigned_to;
	/*
	 * value of bug in ticket (ie. CRM bug, iPhone Bug)
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $issue_list;
	/*
	 * yes/no value for if ticket is a documented issue.
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $documented_issue;
	/*
	 * name of account associated to ticket.  maybe null if no account name provided.
	 *
	 * @var str|null
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $account_name;
	/*
	 * specific text provided about ticket. could be customer comments or csr comments
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_details;
	/*
	 * area of software that is affected (ie. CRM, ILM, Inventory).
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_category;
	/*
	 * the subject line of the ticket.
	 *
	 * @var str|null
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_summary;
	/*
	 * any attachments associated with the main ticket.
	 *
	 * @var mixed
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $TicketAttachment;
	/*
	 * any history items associated with the ticket.
	 *
	 * @var mixed
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $TicketHistory;
	
	/*
	 * Constructor: 
	 *
	 * @return NULL
	 * @author Patrick Geddie <geddiep@yahoo.com>
	*/
	public function __construct()
	{
		$my_var = new TicketHistory();
		$this->set_TicketHistory($my_var);	
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_ticket_id()
	{
		return $this->ticket_ticket_id;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_ticket_id($value)
	{
		$this->ticket_ticket_id = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function create_date()
	{
		return $this->create_date;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_create_date($value)
	{
		$this->create_date = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function update_date()
	{
		return $this->update_date;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_update_date($value)
	{
		$this->update_date = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_number()
	{
		return $this->ticket_number;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_number($value)
	{
		$this->ticket_number = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_ticket_href()
	{
		return $this->ticket_ticket_href;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_ticket_href($value)
	{
		$this->ticket_ticket_href = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_customer_id()
	{
		return $this->ticket_customer_id;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_customer_id($value)
	{
		$this->ticket_customer_id = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function status_id()
	{
		return $this->status_id;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_status_id($value)
	{
		$this->status_id = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function assigned_to()
	{
		return $this->assigned_to;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_assigned_to($value)
	{
		$this->assigned_to = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function issue_list()
	{
		return $this->issue_list;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_issue_list($value)
	{
		$this->issue_list = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function documented_issue()
	{
		return $this->documented_issue;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_documented_issue($value)
	{
		$this->documented_issue = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function account_name()
	{
		return $this->account_name;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_account_name($value)
	{
		$this->account_name = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_details()
	{
		return $this->ticket_details;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_details($value)
	{
		$this->ticket_details = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_category()
	{
		return $this->ticket_category;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_category($value)
	{
		$this->ticket_category = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_summary()
	{
		return $this->ticket_summary;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_summary($value)
	{
		$this->ticket_summary = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return mixed
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function TicketAttachment()
	{
		return $this->TicketAttachment;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param mixed 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_TicketAttachment(TicketAttachment $TicketAttachment)
	{
		$this->TicketAttachment = $TicketAttachment;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return mixed
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function TicketHistory()
	{
		return $this->TicketHistory;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param mixed 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_TicketHistory(TicketHistory $TicketHistory)
	{
		$this->TicketHistory = $TicketHistory;
	}
}
?>