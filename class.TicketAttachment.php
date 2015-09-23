<?php 
/*
 * Main container for TicketAttachmentItem
 * 
 * @author Patrick Geddie <geddiep@yahoo.com>  
*/
class TicketAttachment extends ArrayObject 
{
	
}
/*
 * Contains Getters and Setters for Ticket attachments items and used as TicketAttachment
 * 
 * @author Patrick Geddie <geddiep@yahoo.com>  
*/
class TicketAttachmentItem 
{
	/*
	 * attachment location in ticket ie. details or history.
	 *
	 * @var int|null
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $attachment_location;
	
	/*
	 * ID that determines which history item attachment is associated with.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $history_id;
	/*
	 * ID that determines which ticket attachment is associated with.
	 *
	 * @var int
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $ticket_id;
	/*
	 * Name of the attachment file/image.
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $attachment_name;
	/*
	 * Unique ID provided by Ticket and used in the name of the file stored on the media server.
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $attachment_guid;
	/*
	 * href provided by Ticket as a means to get the attachment file.
	 *
	 * @var str
	 * @access protected
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com>
	 */
	protected $attachment_href;
	
	
	
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function attachment_location()
	{
		return $this->attachment_location;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_attachment_location($value)
	{
		$this->attachment_location = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function history_id()
	{
		return $this->history_id;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_history_id($value)
	{
		$this->history_id = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return int
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function ticket_id()
	{
		return $this->ticket_id;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param int 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_ticket_id($value)
	{
		$this->ticket_id = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function attachment_name()
	{
		return $this->attachment_name;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_attachment_name($value)
	{
		$this->attachment_name = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function attachment_href()
	{
		return $this->attachment_href;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_attachment_href($value)
	{
		$this->attachment_href = $value;
	}
	/*
	 * Getter: See associated member variable.
	 *
	 * @return str
	 * 
	 * @author Patrick Geddie <geddiep@yahoo.com> 
	*/
	public function attachment_guid()
	{
		return $this->attachment_guid;
	}
	/*
	 * Setter: See associated member variable.
	 *
	 * @param str 
	 *
	 * @author Patrick Geddie <geddiep@yahoo.com>  
	*/
	public function set_attachment_guid($value)
	{
		$this->attachment_guid = $value;
	}
}
?>