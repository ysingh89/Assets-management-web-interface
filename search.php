<?php
session_start();
include '../inc/dbConn.php';
if (!isset($_SESSION["username"])) //Check whether the admin has logged in
{  
    header("Location: login.html"); 
}
$dbConn = getDBConnection("assets_management");



function getDeviceType()
{
    $types = array("SmartPhone","Laptop","Desktop","Printer","Tablet","Router","Switch","Server");
    return $types;
}


function printResult($records)
{
    echo"<table border =1>";
    echo"<tr>";
    
    echo"<td><b>Asset #</b></td>";
    
    echo"<td><b>Brand</b></td>";
    
    echo"<td><b>Model</b></td>";
    
    echo"<td><b>Type</b></td>";
    
    echo"<td><b>Purchase Date</b></td>";
    
    echo"<td><b>Purchase Price</b></td>";
    
    echo"<td><b>Warranty Exp. Date</b></td>";
    
    echo"<td><b>Retired Date</b></td>";
    
    echo"<td><b>Retired Comments</b></td>";
    
    echo"<td><b>Commnets</b></td>";
    
    echo"</tr>";
    
    foreach($records as $record)
    {
        echo"<tr>";
    
    echo"<td>";
    echo$record['asset_num'];
    echo"</td>";
    
    echo"<td>";
    echo$record['manufac_name'];
    echo"</td>";
    
    echo"<td>";
    echo$record['model_num'];
    echo"</td>";
    
    echo"<td>";
    echo$record['device_type'];
    echo"</td>";
    
    echo"<td>";
    echo$record['purch_date'];
    echo"</td>";
    
    echo"<td>";
    echo$record['purch_price'];
    echo"</td>";
    
    echo"<td>";
    echo$record['warranty_exp'];
    echo"</td>";
    
    echo"<td>";
    echo$record['retire_date'];
    echo"</td>";
    
    echo"<td>";
    echo$record['retire_descrip'];
    echo"</td>";
    
    echo"<td>";
    echo$record['comments'];
    echo"</td>";
    
    echo"</tr>";
    }
}


function getRecords()
{
    global $dbConn;
    
    $sql = "SELECT * 
            FROM assets
            NATURAL JOIN description
            NATURAL JOIN support
            WHERE 1";
    
    /*$sql = "SELECT * FROM admin WHERE username = :username AND password = :password";

    $namedParameters[':username'] = $username;
    $namedParameters[':password'] = $password;*/
    
    if(!empty($_GET['brand']))
    {
        //$sql .= " and manufac_name = '".$_GET['brand']."'";
        
        
        $sql .= " AND manufac_name = :manufac_name";
        $namedParameters[':manufac_name'] = $_GET['brand'];
    }
    
    
    if(!empty($_GET['model']))
    {
        //$sql .= " and model_num = '".$_GET['model']."'";
        
        $sql .= " AND model_num = :model_num";
        $namedParameters[':model_num'] = $_GET['model'];
    }
    
    
    if(!empty($_GET['deviceType']))
    {
        $sql .= " and device_type = '".$_GET['deviceType']."'";
    }
    if(!empty($_GET['purchaseDate']))
    {
        $sql .= " and purch_date = '".$_GET['purchaseDate']."'";
    }
    
    if($_GET['status'] == "notRetired")
    {
        $sql .=" and retire_date is NULL";
    }
    else if($_GET['status'] == "retired")
    {
        $sql .=" and retire_date is not NULL";
    }
    
    echo "<br />";
    $stmt = $dbConn -> prepare ($sql);
    $stmt -> execute($namedParameters);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($records);
    
    if(empty($records))
    {
        echo "Can't find any device with the entered information.";
    }
    else
    {
        printResult($records);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Search for Item</title>
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
            <h1>Search for Asset(s)</h1>
            <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                <div class="navbar-header">
                  <a class="navbar-brand" href="#">My Asset Management</a>
                </div>
                <ul class="nav navbar-nav">
                  <li><a href="index.php">Main Page</a></li>
                  <li class="active"><a href="search.php" id="currentpage">Search</a></li>
                  <li><a href="insert.php"> Insert</a></li>
                  <li><a href="update.php">Update</a></li>
                </ul>
              </div>
            </nav>
        
        </div>
        <table>
        <form>
            <tr>
                <td>
            Manufacturer Name: </td><td><input type="text" name="brand" value=""/></td>
            </tr>
            <tr><td>
            Model: </td><td><input type="text" name="model" value=""/></td>
            </tr>
            <tr><td>
            Device Type: </td><td><select name="deviceType" value=""/>
                       <option value="">Select One</option>
                       <?php
                         $deviceTypes = getDeviceType();
                          foreach ($deviceTypes as $deviceType) 
                          {
                              echo "<option>" . $deviceType. "</option>";
                          }
                       ?>
                       </select></td>
            </tr>
            <tr><td>
            Purchase Date: </td><td><input type="date" name="purchaseDate"/></td>
            </tr>
            <tr><td>
                <input type="radio" name="status" value="retired">Retired
                <input type="radio" name="status" value="notRetired">Not Retired
            </td></tr>
            <tr><td>
            <input type="submit" name="submit" value="Submit" id="submit"/></td>
            </tr>
        </form>
        </table>
        <?php
        
            if(isset($_GET['submit']))
            {
                getRecords();
            }
        
        
        ?>
        
        
        
    </body>
</html>