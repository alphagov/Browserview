<?php require_once('../init.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Browser View</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript" charset="utf-8"></script>	
	<script src="javascript/main.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="css/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
</head>

<body onload="setup();">
    <fieldset>
        <input type="hidden" id="refreshdelay" value="<?php print REFRESH_DELAY; ?>" />
    </fieldset>
    <?php foreach (get_files() as $file_name) { ?>
    
        <?php
            //get the test version
            $test_version = simplexml_load_file('../data/' . $file_name);
            $html_errors = $test_version->versions->version[0]->w3c_html_errors;
            $html_warnings = $test_version->versions->version[0]->w3c_html_warnings;
        ?>

        <div id="browserpages">
            <?php $counter = 0 ?>
            <?php foreach ($test_version->versions->version[0]->results->result as $browser) { ?>
                <div id="page<?php print $counter ?>" class="browserpage">
                    <div class="browsershot">
                        <img src="<?php print $browser->windowed ?>"/>
                        <?php if (intval($html_errors) > 0 || intval($html_warnings) > 0){ ?>
                            <span class="validation"><?php print $html_errors ?> HTML errors, <?php print $html_warnings ?> HTML warnings</span>
                        <?php } ?>
                    </div>
                    <div class="browsermeta">
                        <ul>
                            <li><?php print $browser->start_date?></li>
                            <li><?php print $browser->os?></li>
                            <li><?php print $browser->browser?></li>
                            <li><?php print $browser->resolution?></li>
                        </ul>
                    </div>
                </div>
                <?php $counter = $counter + 1 ?>
            <?php } ?>
        </div>
    <?php } ?>
</body>
</html>


<?php

    function get_files(){

        $result = array();

        //get array of files
        if ($handle = opendir('../data')) {

            while (false !== ($file = readdir($handle))) {
                if(strrpos($file, '.xml') > 0){
                    array_push($result, $file);
                }
            }
            closedir($handle);
        }else{
            trigger_error ('Cant find /data directory');
        }
        return $result;
    }

?>

