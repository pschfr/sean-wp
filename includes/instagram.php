<?php
class instagramPHP {
    /* Attributes: Instagram username, access token, and userID
    TODO: Build way to filter by tag */
    private $username, $access_token, $userid;
    /* Constructor */
    function __construct($username='', $access_token='') {
        if(empty($username) || empty($access_token)){
            $this->error('Empty username or access token.');
        } else {
            $this->username     = $username;
            $this->access_token = $access_token;
        }
    }
    /*
     * The API works mostly with user IDs, but it's easier for users to use their username.
     * This function gets the userID corresponding to the username
     */
    public function getUserIDFromUserName() {
        if(strlen($this->username) > 0 && strlen($this->access_token) > 0) {
            //Search for the username
            $useridquery = $this->queryInstagram('https://api.instagram.com/v1/users/search?q=' . $this->username . '&access_token=' . $this->access_token);
            if(!empty($useridquery) && $useridquery->meta->code == '200' && $useridquery->data[0]->id > 0){
                $this->userid=$useridquery->data[0]->id;
            } else { $this->error('getUserIDFromUserName error.'); }
        } else { $this->error('Empty username or access token.'); }
    }
    /*
     * Get the most recent media published by a user.
     * You can use the $args array to pass the attributes that are used by the GET/users/user-id/media/recent method
     */
    public function getUserMedia($args=array()) {
        if($this->userid<=0){ //If no user id, get user id
            $this->getUserIDFromUserName();
        }
        if($this->userid > 0 && strlen($this->access_token) > 0) {
            $qs='';
            if(!empty($args)) { $qs = '&' . http_build_query($args); } // Adds query string if any args are specified
            $shots = $this->queryInstagram('https://api.instagram.com/v1/users/' . (int)$this->userid . '/media/recent?access_token=' . $this->access_token . $qs);
            if($shots->meta->code == '200'){ // Gets Instagram shots if above query was successful
                return $shots;
            } else { $this->error('getUserMedia error.'); }
        } else { $this->error('Empty username or access token.'); }
    }
    /*
     * Method that simply displays the shots.
     */
    public function simpleDisplay($shots) {
        $simpleDisplay = '';
        if(!empty($shots->data)) {
            foreach($shots->data as $istg) {
                // var_dump($istg);
                // If you want to display another size, you can use 'low_resolution', or 'standard_resolution' in place of 'thumbnail'
                $istg_thumbnail = $istg->{'images'}->{'thumbnail'}->{'url'};
                $istg_link      = $istg->{'images'}->{'standard_resolution'}->{'url'};
                $istg_caption   = $istg->{'caption'}->{'text'};
                $simpleDisplay .= '<div class="instashot"><a class="lightbox" href="' . $istg_link . '"><img src="' . $istg_thumbnail . '" alt="' . $istg_caption . '" title="' . $istg_caption . '"></a></div>';
            }
        } else { $this->error('simpleDisplay error.'); }
        return $simpleDisplay;
    }
    /*
     * Common mechanism to query the Instagram API
     * TODO: I'm not too sure how well this works, investigate later
     */
    public function queryInstagram($url) {
        // Prepare caching
        $cachefolder = __DIR__ . '/';
        $cachekey    = md5($url);
        $cachefile   = $cachefolder . $cachekey . '_' . date('i') . '.txt'; // Cached for one minute
        // If not cached, -> Instagram request
        if(!file_exists($cachefile)) {
            // Request
            $request = 'error';
            if(!extension_loaded('openssl')) { $request = 'This class requires the PHP extension open_ssl to work as the Instagram API works with HTTPS.'; }
            else { $request = file_get_contents($url); }
            // Remove old caches
            $oldcaches = glob($cachefolder . $cachekey . "*.txt");
            if(!empty($oldcaches)) { foreach($oldcaches as $todel) {
              unlink($todel);
            } }
            // Cache result
            $rh = fopen($cachefile, 'w+');
            fwrite($rh, $request);
            fclose($rh);
        }
        // Execute and return query
        $query = json_decode(file_get_contents($cachefile));
        return $query;
    }
    public function error($src='') { echo '/!\ error ' . $src . '. '; } // If all else fails, error
}
