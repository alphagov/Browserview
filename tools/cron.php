<?php

require_once('../init.php');

    $swiches = getopt('a:');
    $command = isset($swiches['a']) ? $swiches['a'] : null;
    
    if ($command == 'run'){
        run_tests();
    }else if ($command == 'save'){
        save_latest_version();
    }else{
        print "You need to tell me to run (-a run) or to save (-a save)\n";
    }


    function run_tests(){
        //NB, for some reason to run a new tests requires the test ID and the version ID of the most recent test.
        foreach (split(',', CROSSBROWSER_TEST_IDS) as $test_id) {

            print "Running test id: " . $test_id . "\n";

            //get the latest version of the test
            $version_details = get_latest_version_details($test_id);

            //run
            $success = call_crossbrowser("http://crossbrowsertesting.com/api/screenshots/$test_id/version/$version_details->id/run");
            if ($success){
                print "Success\n";
            }else{
                print "FAIL\n";
            }
        }
    }

    function save_latest_version(){
        foreach (split(',', CROSSBROWSER_TEST_IDS) as $test_id) {

            //get the latest version of the test
            print "Getting metadata for test id: " . $test_id . "\n";            
            $version_details = get_latest_version_details($test_id);
        
            //get the data for that test
            print "Getting latest version data for test id: " . $test_id . "\n";                        
            $version_data = call_crossbrowser("http://crossbrowsertesting.com/api/screenshots/$test_id/version/$version_details->id/show");

            //save it to disk
            print "Saving to disk test id: " . $test_id . "\n";                                    
            $file_handle = fopen("../data/$test_id.xml", "w");
            fwrite($file_handle, $version_data);
            fclose($file_handle);
        }
    }


    function get_latest_version_details($test_id){
        $result = False;
        $versions_data = call_crossbrowser("http://crossbrowsertesting.com/api/screenshots/$test_id/show");
        if ($versions_data){
            $versions = simplexml_load_string($versions_data);

            //first one is the latest version of the test
            $result = $versions->versions->version[0];
        }
        
        return $result;
    }


    function call_crossbrowser($url){
        $context = stream_context_create(array(
            'http' => array(
                'header'  => "Authorization: Basic " . base64_encode(CROSSBROWSER_USERNAME . ":" . CROSSBROWSER_PASSWORD)
            )
        ));

        return file_get_contents($url, false, $context);    
    }

?>