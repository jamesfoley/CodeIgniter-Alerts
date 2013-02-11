<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Alerts
{
	public $CI;

	public function __construct()
	{
		//	Get an instance of CI
		$this->CI =& get_instance();
	}

	//	Add an alert to a group
	function add($type = '', $text = '', $items = array(), $group = 0, $flush = false)
	{		
		if($flush === true)
			$this->flush($group);

		//	Check to see if the alert group exists, its value will be an array
		if($this->CI->session->userdata('alerts_group_'.$group) !== FALSE)
		{
			//	The group already exists, get the array
			$group_array = $this->CI->session->userdata('alerts_group_'.$group);
		}
		else
		{
			//	This group doesnt exist, make a new group array
			$group_array = array();
		}
			
		//	Create the mini alert array
		$alert_array = array
		(
			'type'	=>	$type,
			'text'	=>	$text,
			'items' =>	$items
		);
		
		//	Add the mini alert to the group
		$group_array[] = $alert_array;
		
		//	Set the userdata
		$this->CI->session->set_userdata('alerts_group_'.$group, $group_array);
	}
	
	//	Get a formated alert group
	function get($group = 0, $section = '')
	{		
		//	Create a new template variable that will be returned later on
		$template = '';
		
		//	Check to see if the group userdata exists
		if($this->CI->session->userdata('alerts_group_'.$group) === FALSE)
			return $template;	
			
		//	Get the group
		$group_array = $this->CI->session->userdata('alerts_group_'.$group);
				
		//	Loop the group alert array
		foreach($group_array as $alert)
		{
			switch($alert['type'])
			{
				case 'error':
					$title = 'Oh snap!';
					$class = 'alert-error';
				break;
				
				case 'success':
					$title = 'Success!';
					$class = 'alert-success';
				break;
								
				case 'information':
					$title = 'Heads up!';
					$class = 'alert-info';
				break;
				
				default:
					$title = 'Warning!';
					$class = '';
				break;
			}

			$template .= '<div class="alert ' . $class . '">';
			$template .= '<strong>' . $title . '</strong> ';
			$template .= $alert['text'];
			$template .= '</div>';
		}

		//	Kill the group
		$this->CI->session->unset_userdata('alerts_group_'.$group);
		
		//	Return the template
		return $template;
	}
	
	//	Flush an alert group, usefull for setting new alerts after a long time
	function flush($group = 0)
	{		
		//	Flush the alert group set
		if($this->CI->session->userdata('alerts_group_'.$group) !== FALSE)
			$this->CI->session->unset_userdata('alerts_group_'.$group);
	}
}

?>