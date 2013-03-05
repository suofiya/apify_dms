<?php
/**
 * 
 * Jobs Navigation Widget
 * 
 * @author yilee
 *
 */
class Jobs_Navigation_Widget extends WP_Widget {

	public function __construct() {
				/* Widget settings. */
		$widget_ops = array( 'classname' => 'Jobs_Navigation_Widget', 'description' => __('Jobs Navigation Widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'jobs_navigation_widget' );
		
		parent::__construct(
	 		'jobs_navigation_widget', // Base ID
			'Jobs_Navigation_Widget', // Name
			$widget_ops,// Args
			$control_ops
		);
	}

 	public function form( $instance ) {
		// outputs the options form on admin
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
		//echo "outputs the content of the widget";
		echo '<form>'		;	
		$htm = '<div>
				dept:<br>';
		$htm2='</div>	';
		
		$locations = getPositionLocationList();
		if( count( $locations) > 0 ) {
 			foreach( $locations as $key=>$location ) {
 				if('0'==$key){
 					 $htm= '<div>'.$location.'<br>';}
 				else{
 					$output ='<input type="checkbox" name="checkbox2" value="'.$key.' ">'. $location .'<br> ';
 					$htm = $htm .$output;
 				}
			}
		}
		
		echo '<p>'.$htm.$htm2.'</p>';
		
		$levels = getPositionLevelList();
		if( count( $levels) > 0 ) {
 			foreach( $levels as $key=>$level ) {
 				if('0'==$key){
 					 $htm= '<div>'.$level.'<br>';}
 				else{
 					$output ='<input type="checkbox" name="checkbox2" value="'.$key.' ">'. $level .'<br> ';
 					$htm = $htm .$output;
 				}
			}
		}
		
		echo '<p>'.$htm.$htm2.'</p>';
		
		$depts = getPositionDeptList();
		if( count( $depts) > 0 ) {
 			foreach( $depts as $key=>$dept ) {
 				if('0'==$key){
 					 $htm= '<div>'.$dept.'<br>';}
 				else{
 					$output ='<input type="checkbox" name="checkbox2" value="'.$key.' ">'. $dept .'<br> ';
 					$htm = $htm .$output;
 				}
			}
		}
		
		echo '<p>'.$htm.$htm2.'</p>';
		
		echo '<P><INPUT TYPE="SUBMIT" VALUE="OK"><INPUT TYPE="RESET" VALUE="Reset"></P>';
		echo '</form>';
		//insertJob();
	}

}