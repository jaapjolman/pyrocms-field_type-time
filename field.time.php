<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Date/Time Field Type
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Field Types
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */

class Field_time
{
    public	$field_type_slug	= 'time';
    public	$db_col_type		= 'varchar';
    public	$version			= '1.0.0';
    public	$custom_parameters	= array('use_duration');
    public	$author				= array('name'	=> 'Antoine Benevaut',
    									'url'	=> 'http://www.cavaencoreparlerdebits.fr');
    									
    /**
     *
     */
    public function form_output($data, $entry_id, $field)
    {    	
    	if ($data['custom']['use_duration'] == 'yes')
    	{
    		// Hour
			$time_input = form_input($data['form_slug'].'_hour', $time['hour'], 'style="min-width: 100px; width:100px;"').'&nbsp&nbsph&nbsp&nbsp';
			
			// Minute
			$time_input .= form_input($data['form_slug'].'_minute', $time['minute'], 'style="min-width: 100px; width:100px;"');
    	}
    	else
    	{
    		$time = $this->get_time(trim($data['value']), $data['form_slug']);
    		
    		// Hour	
			$hour_count	= 1;
			$hours		= array();
			
			while ($hour_count <= 12 )
			{
				$hour_key = $hour_count;
			
				if (strlen($hour_key) == 1)
				{
					$hour_key = '0'.$hour_key;
				}
				$hours[$hour_key] = $hour_count;
				$hour_count++;
			}
			$time_input = lang('global:at').'&nbsp;&nbsp;'.form_dropdown($data['form_slug'].'_hour', $hours, $time['hour'], 'style="min-width: 100px; width:100px;"');
			
			// Minute
			$minute_count	= 0;
			$minutes		= array();
			
			while ($minute_count <= 59)
			{
				$minute_key = $minute_count;
				
				if (strlen($minute_key) == 1)
				{
					$minute_key = '0'.$minute_key;
				}
				$minutes[$minute_key] = $minute_key;
				$minute_count++;
			}
			$time_input .= form_dropdown($data['form_slug'].'_minute', $minutes, $time['minute'], 'style="min-width: 100px; width:100px;"');
		
			// AM/PM
			$am_pm		= array('am' => 'am', 'pm' => 'pm');
			
			// Is this AM or PM?
			if ($this->CI->input->post($data['form_slug'].'_am_pm'))
			{
				$am_pm_current = $this->CI->input->post($data['form_slug'].'_am_pm');
			}
			else
			{
				$am_pm_current = 'am';
		
				if (isset($date['pre_hour']))
				{
					if ($date['pre_hour'] >= 12)
					{
						$am_pm_current = 'pm';
					}
				}
			}
			$time_input .= form_dropdown($data['form_slug'].'_am_pm', $am_pm, $am_pm_current, 'style="min-width: 100px; width:100px;"');
		}
		return $time_input;
    }
    
    /**
     *
     */
    public function pre_save($input, $field)
	{
		// Hour
		$hour = '00';
		if ($this->CI->input->post($field->field_slug.'_hour'))
		{
			$hour = $this->CI->input->post($field->field_slug.'_hour');
	
			if ($this->CI->input->post($field->field_slug.'_am_pm') == 'pm' and $hour < 12)
			{
				$hour = $hour+12;
			}
		}
			
		// Minute
		$minute = '00';
		if ($this->CI->input->post($field->field_slug.'_minute'))
		{
			$minute = $this->CI->input->post($field->field_slug.'_minute');
		}
		
		return $hour.':'.$minute;
	}
	
	/**
	 *
	 */
	/*public function pre_output($input, $params)
	{
		return ;
	}*/
	
	/**
	 *
	 */
	private function get_time($time, $slug)
	{
		$out['hour']	= '';
		$out['minute']	= '';

		if ($time == '')
		{
			return $out;
		}
		
		$times = explode(':', $time);
		
		$out['hour']		= $this->two_digit_number($time[0]);
		$out['minute']		= $this->two_digit_number($time[1]);
			
		// Format hour for our drop down since we are using am/pm
		if( $out['hour'] > 12 ) $out['hour'] = $out['hour']-12;

		return $out;
	}
	
	/**
	 *
	 */
	public function two_digit_number($num)
	{
		$num = trim($num);

		if ($num == '') { return '00'; }

		if (strlen($num) == 1)
		{
			return '0'.$num;
		}
		else { return $num; }
	}
	
	/**
	 *
	 */
	public function param_use_duration($value = '')
	{
		if ($value == 'no')
		{
			$no_select 		= true;
			$yes_select 	= false;
		}
		else
		{
			$no_select 		= false;
			$yes_select 	= true;
		}
	
		$form  = '<ul><li><label>'.form_radio('use_duration', 'yes', $yes_select).' Yes</label></li>';
		$form .= '<li><label>'.form_radio('use_duration', 'no', $no_select).' No</label></li>';
		
		return $form;
	}
}