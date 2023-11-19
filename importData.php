<?php
// Load the database configuration file
include_once 'db.php';


    if(isset($_POST['importSubmit'])){

        // Allowed mime types
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        $uniqueid = rand(10,1000);

        // Validate whether selected file is a CSV file
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
            
            // If the file is uploaded
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                
                // Open uploaded CSV file with read-only mode
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                
                // Skip the first line
                fgetcsv($csvFile);
                
                // Parse data from CSV file line by line
                while(($line = fgetcsv($csvFile)) !== FALSE){
                    // Get row data
                    $product   = $line[0];
                    $value     = $line[1];
                    //$uniqueid  = $line[2];
                
                    
                    // Check whether member already exists in the database with the same email
                    $prevQuery = "SELECT * FROM users WHERE product = '".$line[0]."'";
                    $prevResult = $conn->query($prevQuery);
                    
                    if($prevResult->num_rows > 0){
                        // Update member data in the database
                        $conn->query("UPDATE users SET unique_id = '".$uniqueid."', product = '".$product."', value = '".$value."' WHERE product = '".$product."'");
                    }else{
                        // Insert member data in the database
                        $user_sql = "INSERT INTO users (unique_id, product, value) VALUES ('".$uniqueid."', '".$product."', '".$value."')";
                        $insertSucc1 = mysqli_multi_query($conn, $user_sql);

                        
                    }
                }
                
                // Close opened CSV file
                fclose($csvFile);

                    if ($insertSucc1) {

                        $last_id = mysqli_insert_id($conn);
                        $check =  "SELECT * FROM users WHERE id = '$last_id'";
                        $result1 = mysqli_query($conn, $check) ;
                        $result = mysqli_fetch_array($result1);
                        // $uniqueId = $result["unique_id"];

                        $uniqueId = $result["unique_id"]; 

                        $retail = "UPDATE retail SET insight_data_id = $uniqueId";
                        $var=mysqli_query($conn, $retail);
                
                        header('Location: index.php');
                    }
                
                $qstring = '?status=succ';
            }else{
                $qstring = '?status=err';
            }
        }else{
            $qstring = '?status=invalid_file';
        }
    }



// Redirect to the listing page
header("Location: index.php".$qstring);