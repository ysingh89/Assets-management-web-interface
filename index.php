<?php
session_start();

if (!isset($_SESSION["username"])) //Check whether the admin has logged in
{  
    header("Location: login.html"); 
}
include '../inc/dbConn.php';
$dbConn = getDBConnection("assets_management");

function getRecords()
{
    global $dbConn;
    
    $sql = "SELECT * from assets 
            NATURAL JOIN description 
            NATURAL JOIN support
            WHERE retire_date IS NULL ";
    $stmt = $dbConn -> prepare ($sql);
    $stmt -> execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $records;
}




function displayEverything()
{
    $records = getRecords();
    
    echo "<table border = 2 >";
    
    echo "<tr>";
    echo"<td><b>Asset Number</b></td>";
    echo"<td><b>Asset ID</b></td>";
    echo"<td><b>Brand</b></td>";
    echo"<td><b>Type</b></td>";
    echo"<td><b>Price</b></td>";
    
    echo"<td><b>Purchase Date</b></td>";
    
    echo"<td><b>Warranty Exp. Date</b></td>";
    echo"<td><b>Comments</b></td>";
    echo"</tr>";
    foreach($records as $record)
    {
        echo"<tr>";
        echo"<td>";
        print_r($record['asset_num']);
        echo"</td>";
        echo"<td>";
        print_r($record['asset_id']);
        echo"</td>";
        echo"<td>";
        print_r($record['manufac_name']);
        echo"</td>";
        echo"<td>";
        print_r($record['device_type']);
        echo"</td>";
        echo"<td>";
        print_r($record['purch_price']);
        echo"</td>";
        echo"<td>";
        print_r($record['purch_date']);
        echo"</td>";
        echo"<td>";
        print_r($record['warranty_exp']);
        echo"</td>";
        echo"<td>";
        print_r($record['comments']);
        echo"</td>";
        echo"</tr>";
        //echo $record;
    }
    echo"</table>";
}


?>




<!DOCTYPE html>
<html>
    <head>
        <title> Admin Section: Login Screen</title>
        
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <style>
            
            .jumbotron {
                text-align:center;
            }
            
        </style>
    </head>
    <body>
        <div style="float:right">
        <form action="logout.php" >
            <input type="submit" class="btn btn-danger" value="Logout" />
        </form>
        </div>
        <div class="jumbotron">
        <h1> Admin Section</h1>
        
        
        
        <nav class="navbar navbar-inverse">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">My Asset Management</a>
            </div>
            <ul class="nav navbar-nav">
              <li class="active"><a href="index.php"id="currentpage">Main Page</a></li>
              <li><a href="search.php">Search</a></li>
              <li><a href="insert.php"> Insert</a></li>
              <li><a href="update.php">Update</a></li>
            </ul>
          </div>
        </nav>
        
        
         
        
        </div>
        <br />
        <?php
        
        
            displayEverything();
        
        
        
        ?>
        
    </body>
</html>