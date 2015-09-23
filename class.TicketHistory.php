<?php 
include_once 'class.TicketAttachment.php';
/*
 * Main container for TicketHistoryItem
 * 
 * @author Patrick Geddie <geddiep@yahoo.com>  
*/
class TicketHistory extends ArrayObject 
{
	
}

/*
 * Contains the getsers and setters for Ticket History and is added as TicketHistory
 * 
 * @author Patrick Geddie <geddiep@yahoo.com>  
*/
class TicketHistoryItem 
{
	/*
	 * the ticket id for the ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_ticket_id;
	/*
	 * the ticket id for the history item.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $history_ticket_id;
	/*
	 * the action id associated with the history (ie. why there is a history item; closed, internal note, reopened).
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $action_id;
	/*
	 * the old status of the ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $old_status;
	/*
	 * the new status of the ticket.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $new_status;
	/*
	 * the csr that performed the action.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $action_performer;
	/*
	 * the csr which the action was sent to.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $action_target;
	/*
	 * the comments in the history.
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $comments;
	/*
	 * the date the history item occured
	 *
	 * @var int; unix time 
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $action_date;
	/*
	 * any attachments associated with the history item
	 *
	 * @var mixed
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $TicketAttachment;
	
	
	
	
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
	public function history_ticket_id()
	{
		return $this->history_ticket_id;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_history_ticket_id($value)
	{
		$this->history_ticket_id = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function action_id()
	{
		return $this->action_id;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_action_id($value)
	{
		$this->action_id = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function old_status()
	{
		return $this->old_status;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_old_status($value)
	{
		$this->old_status = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function new_status()
	{
		return $this->new_status;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_new_status($value)
	{
		$this->new_status = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function action_performer()
	{
		return $this->action_performer;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_action_performer($value)
	{
		$this->action_performer = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function action_target()
	{
		return $this->action_target;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_action_target($value)
	{
		$this->action_target = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str any text from history item
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function comments()
	{
		return $this->comments;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str; any text from history item
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_comments($value)
	{
		$this->comments = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int; unix time stamp
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function action_date()
	{
		return $this->action_date;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int; unix time stamp 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_action_date($value)
	{
		$this->action_date = $value;
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
}

?>