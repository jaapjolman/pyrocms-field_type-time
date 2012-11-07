pyrocms-field_type-time
=======================

$field = array(
       /**
	* field_type
	*/
       'type'    => 'time',

       'extra'    => array(
       		  /**
		   * Option : duration : 'yes'/'no'
		   *
		   * Allow you to register time HH:mm as dropdown
		   * or duration HH:mm as input box.
		   */
       		  'use_duration'   => 'yes'
       ),

       'name'    => 'lang:fields:duration',
       'slug'    => 'duration',
       'namespace'   => 'stream_namespace',
       'assign'   => 'stream_slug',
       'title_column'  => false,
       'required'   => true,
       'unique'   => false
);
	
$this->streams->fields->add_field($field);
