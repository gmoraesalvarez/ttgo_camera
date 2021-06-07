<?
function black_pic_detect(){
  $id = 'default_id';
	if ( isset($_GET['id']) ){ $id = $_GET['id']; }
    $received = file_get_contents('php://input');
    $darkcount = 0;
    $im = new Imagick();
	if ($im->readImageBlob($received)){
        file_put_contents("status.txt","opened an image from $id\n",FILE_APPEND);
      	$it = $im->getPixelIterator(); 		  /* Get iterator */
      	foreach( $it as $row => $pixels ){      	/* Loop trough pixel rows */
      		foreach ( $pixels as $column => $pixel ){       	/* Loop trough the pixels in the row (columns) */
        		$color = $pixel->getColor();
       			if ( $color['g'] < 30 ){
            		$darkcount++;
            	}
        	}
      	}
    	$it->syncIterator(); /* Sync the iterator, this is important to do on each iteration */
    	$pixcount = (($im->getImageWidth() * $im->getImageHeight()) * 0.9);
        file_put_contents("status.txt","threshold is $pixcount, dark pixel count is $darkcount\n",FILE_APPEND);
    	if ($darkcount > $pixcount) {
     	    file_put_contents("status.txt",$darkcount." > ".$pixcount." | Rejected image from $id\n",FILE_APPEND);
            return true;
    	} else {
        	file_put_contents("status.txt",$darkcount." < ".$pixcount." | Accepted image from $id\n",FILE_APPEND);
            return false;
    	}
    } else {
        file_put_contents("status.txt","could not open an image\n",FILE_APPEND);
    }
}

function get_time($format){
    $tz = 'America/Sao_Paulo';
	$timestamp = time();
	$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
	$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
	return $dt->format($format);
}

function save_photo(){
  $id = 'default_id';
	if ( isset($_GET['id']) ){ $id = $_GET['id']; }
    $bot = "1415075521:AAFzINhS2Yr0u5FdSv6prUbHLdBSpidVmGE";
	$chat_id = "249021960";
    $received = file_get_contents('php://input');
	//$received_get = $_GET["pic"];
	$timeNow = get_time("Y-j-F.h_i_s");
	$timeNow_ = get_time("Y j F h:i:s\n");
	//echo $timeNow;
	$subject = "$id Mexeu - ".$timeNow."\n";
	//save_photo($received);
	//echo "teste";
	file_put_contents("data/$id.$timeNow.jpg",$received);
}

function save_mjpeg(){
  $id = 'default_id';
	if ( isset($_GET['id']) ){ $id = $_GET['id']; }
    $bot = "1415075521:AAFzINhS2Yr0u5FdSv6prUbHLdBSpidVmGE";
	$chat_id = "249021960";

	$timeNow = get_time("Y-j-F.h_i_s");
	$timeNow_ = get_time("Y j F h:i:s\n");

    $received = file_get_contents('php://input');
	$aviheader = substr($received, -240);
	$avibody = substr($received, 0, -240);
	$avifile = $aviheader.$avibody;
	file_put_contents("data/$id.$timeNow.avi", $avifile);

}

function save_motion(){
  $id = 'default_id';
	if ( isset($_GET['id']) ){ $id = $_GET['id']; }
	$received = file_get_contents('php://input');
	$timeNow = get_time("Y-j-F.h_i_s");
	$timeNow_ = get_time("Y j F h:i:s\n");
	file_put_contents("data/$id.motion.".$timeNow.'.txt',$received);
}

function save_bg(){
  $id = 'default_id';
	if ( isset($_GET['id']) ){ $id = $_GET['id']; }
	$received = file_get_contents('php://input');
	$timeNow = get_time("Y-j-F.h_i_s");
	$timeNow_ = get_time("Y j F h:i:s\n");
	file_put_contents("data/$id.bg.".$timeNow.'.txt',$received);
}

function save_status(){
  $id = 'default_id';
	if ( isset($_GET['id']) ){ $id = $_GET['id']; }
	$received = file_get_contents('php://input');
	$timeNow_ = get_time("Y j F h:i:s\n");
	file_put_contents("status.txt",$timeNow_.' --> '.$id.$received."\n",FILE_APPEND);
}

function send_orders(){
    $config = "SLP";
    $config = file_get_contents("current_orders.txt",false);
	echo $config;
}
function send_led(){
    $led = "NLE";
    $led = file_get_contents("led.txt",false);
	echo $led;
}

dayly_stuff();

if ($_GET['pic'] == 'motion_detect'){
    if (black_pic_detect() == false){
      save_photo();
    }
    //save_photo();
}
if ($_GET['pic'] == 'mjpeg'){
    //if (black_pic_detect() == false){
      save_mjpeg();
    //}
    //save_photo();
}
if ($_GET['pic'] == 'status'){
    save_status();
}

if ($_GET['pic'] == 'orders'){
    send_orders();
}

if ($_GET['pic'] == 'motion_debug'){
    save_motion();
}

if ($_GET['pic'] == 'motion_debug_bg'){
    save_bg();
}
if ($_GET['pic'] == 'led'){
    send_led();
}
?>
