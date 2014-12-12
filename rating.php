<?php

//add ratings to blog posts
function insertRatings($content) {
    global $wpdb;

    if (!is_feed() && !is_home()) {
        
        //current ratings
        $content .= "<div class='ratingsContainer'>";
       
        $content .= getRatings($wpdb->escape($_GET['p']));
       

        //vote ratings            
        
        if(!checkIfVoted($_GET['p'])){          
            $content .= "<div class='voteRatings'>";
            $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'advancedratings_ratings');
            foreach ($result as $key => $value) {
                $content .= "<a href='#' title='$value->name'><img ratingid='$value->id' src='$value->image'></a>";
            }
            $content .= "</div>";
        }
        $content .= "</div>";
            
        return $content;
        
    }
}
add_filter('the_content', 'insertRatings');

//function that gets the current ratings on a blog post.
function getRatings($id) {
    global $wpdb;
   
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'advancedratings_data WHERE blog_id="' . $id . '"';
    $result = $wpdb->get_results($sql);
    $amountArray = array();
    foreach ($result as $key => $value) {
        $amountArray[$value->rating_id] = $amountArray[$value->rating_id] + 1;
    }

    $return = "<div class='currentRatings'>";
    foreach ($amountArray as $key => $value) {
        $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'advancedratings_ratings WHERE id="' . $key . '"');
        $return .= "<span><img src='" . $result[0]->image . "'>" . $result[0]->name . " x $value</span> ";
    }
    $return .= "</div>";
    
    return $return;
}

//save rating to database.
function saveRating() {
    global $wpdb;
    $blogId = $wpdb->escape($_POST['blogId']);
    $ratingId = $wpdb->escape($_POST['ratingId']);
    $wpdb->insert(
            $wpdb->prefix . 'advancedratings_data', array(
        'rating_id' => $ratingId,
        'blog_id' => $blogId,
            ), array(
        '%d',
        '%d'
            )
    );
    $json = saveCookie($blogId);
    
    echo getRatings($blogId);
    
    //die to prevent the ajax request to run more functions
    die();
}
//add ajax hook for logged in and not logged in people
add_action( 'wp_ajax_saveRating', 'saveRating' );
add_action('wp_ajax_nopriv_saveRating', 'saveRating');

//function to check if you have allready voted, returns true if  voted
function checkIfVoted($voteId){
    $votedArray = getCookie();       
    if($votedArray == null){
        return false;
    }
    return in_array($voteId,$votedArray);
}

//function to save a cookie
function saveCookie($id){
    $cookie = array();
    $cookie = getCookie();    
    $cookie[] = $id;
    $json = json_encode($cookie, true);    
    $time=mktime(0,0,0,date("n",time()),date("j",time()),date("Y",time())+1,0);
    setcookie('advancedRatings', $json,$time, COOKIEPATH, COOKIE_DOMAIN);
    return $json;
}
//function to retrive the cookie and convert the josn to php array.
function getCookie(){
    $cookie = $_COOKIE['advancedRatings'];
    $cookie = stripslashes($cookie);
    
    return json_decode($cookie, true);
}