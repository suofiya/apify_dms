<!-- sidebar.php -->                                                                                                                                          
<div class="listLeft">                                                                                                                                        
<?php                                                                                                                                                         
$cat_ID = get_query_var('cat');              # current category id                                                                              
$job_id = get_query_var('p'); 				   ### current job id
                                                                                                                                                              
$typecat_id     = ((int)($_GET['typecat']) ? (int)($_GET['typecat']) : $typecat_id);                                                                          
                                                                                                                                                              
if (is_category()) {                                                                                                                                          
    $directcat      = get_category($cat_ID);                                                                                                                  
    $args           = array( 'numberposts' => 1, 'order'=> 'DESC', 'orderby' => 'post_date', 'category__and' => array($typecat_id,$cat_ID) );                 
    $rand_posts     = get_posts( $args );                                                                                                                     
} else if (is_single()) {                                                                                                                                     
    $directcat_all      = get_the_category($job_id);                                                                                                          
    $directcat          = $directcat_all[0];                                                                                                                  
} else {                                                                                                                                                      
    $job_id = 880;                          ### default Public Relations Coordinator                                                                          
    $directcat_all      = get_the_category($job_id);                                                                                                          
    $directcat          = $directcat_all[0];           
}                                                                                                                                                             
$maincat_id     = $directcat->category_parent;  

if ($maincat_id==53) {     ### 2011 allopenings    >> 1st_level_cat_info                                                                                       
            $maincat_id = $directcat->term_id;                                                                                                                
            $typecat_id = ((int)($_GET['typecat']) ? (int)($_GET['typecat']) : $typecat_id);                                                                  
}                                                                                                                                                             
$jobcat_name = get_cat_name($maincat_id);                                                                                                                     
$typecat_id = ((int)($_GET['typecat']) ? (int)($_GET['typecat']) : $typecat_id);                                                                              
?>                                                                                                                                                            
<em class="iconc<?php echo $maincat_id; ?>"><?php echo $jobcat_name; ?></em>    

<!--- OpeningLocation dropdown list -->
<h3><span>	<form action="<?php bloginfo('url'); ?>/" method="get"> 
<input type="hidden" name="cat" value="<?php echo $jobcat_id; ?>" />
<input type="hidden" name="tag_id" value="<?php // echo $tag_id; ?>" />
<?php 
$select = wp_dropdown_categories('show_count=0&orderby=name&echo=0&child_of=54&hide_if_empty=1&name=typecat&selected=<?php echo $typecat_id; ?>'); ### HARDCODE cat_id for OpeningLocation
$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
echo $select;
?>
<noscript><div><input type="submit" value="View" /></div></noscript>
</form>
</span>招聘职位</h3>
<!--- eof OpeningLocation dropdown list -->

<dl>
<?php                                                                                                                                                         
                                                                                                                                                              
echo '<dt><strong><a href="' . get_category_link( $maincat_id ) . '&typecat=' . $typecat_id .  '" title="' . sprintf( __( " %s" ), $jobcat_name) . '" ' .     '>' . $jobcat_name.'</a> </strong></dt> ';                                                                                                                    
                                                                                                                                                              
$subcategories = get_categories('child_of='.$maincat_id);      
foreach($subcategories as $subcat) {                                                                                                                          
    $subcat_id = $subcat->term_id;                                                                                                                            
    echo '<dd>';      
    echo '<strong><a href="' . get_category_link( $subcat_id ) . '&typecat=' . $typecat_id .  '" title="' . sprintf( __( " %s" ), $subcat->name) . '" ' .     '>' . $subcat->name.'</a> </strong> ';                                                                                                                                              
    echo '</dd>';  
    if (isset($typecat_id)) { 
	$args = array( 'numberposts' => 60, 'order'=> 'DESC', 'orderby' => 'post_date', 'category__and' => array($typecat_id,$subcat_id) );       
    } else {
    	$args = array( 'numberposts' => 60, 'order'=> 'DESC', 'orderby' => 'post_date', 'category' => $subcat_id );                                                                                                                                                                                   
    }
    $morepostslist = get_posts( $args );   
    foreach ($morepostslist as $post) {                                                                                                                       
        setup_postdata($post);  
    ?>                                                                                                                                                        
        <dd><a href="<?php the_permalink(); ?>&typecat=<?php echo $typecat_id; ?>" class="blue" title="<?php the_title() ?>"><?php the_title() ?></a></dd>    
    <?php                                                                                                                                                         }                                                                                                                                                         
}                                                                                                                                                             
echo '</dl>';                                                                                                                                                 
                                                                                                                                                              
?>                                                                                                                                                            
                                                                                                                                                              
                                                                                                                                                              
</div>                                                                                                                                                        
<!-- eof sidebar.php -->    
