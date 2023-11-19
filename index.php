<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    <title>Document</title>
</head>
<body>
<?php
// Load the database configuration file
include_once 'db.php';

// Get status message
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Users data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
?>

<!-- Display status message -->
<?php if(!empty($statusMsg)){ ?>
<div class="col-xs-12">
    <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<div class="container-fluid">
    <div class="row mt-5">
        <!-- Import & Export link -->
        <div class="col-md-12 head">
            <div class="float-right">
                <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
                <a href="exportData.php" class="btn btn-primary"><i class="exp"></i> Export</a>
            </div>
        </div>
        <!-- CSV file upload form -->
        <div class="col-md-12 mt-3 mb-3" id="importFrm" style="display: none;">
            <form action="importData.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
            </form>
        </div>

        <!-- Data list table --> 
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#ID</th>
                    <th>Unique ID</th>
                    <th>Product</th>
                    <th>Value</th>
                  
                </tr>
            </thead>
            <tbody>
            <?php
            // Get member rows
            $result = $conn->query("SELECT * FROM users");
            if($result != false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['unique_id']; ?></td>
                    <td><?php echo $row['product']; ?></td>
                    <td><?php echo $row['value']; ?></td>
                    
                </tr>
            <?php } }else{ ?>
                <tr><td colspan="5">No Record(s) found...</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Show/hide CSV upload form -->
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
</script>
</body>
</html>