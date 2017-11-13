<?php
session_start();
if (!isset($_SESSION["username"])) //Check whether the admin has logged in
{  
    header("Location: login.html"); 
}
include '../inc/dbConn.php';
include 'functions.php';
$dbConn = getDBConnection("assets_management");

$name = $_GET['name'];
$address = $_GET['address'];
$phone = $_GET['phone'];
$url =$_GET['url'];





function insertIntoAssetsTable()
{
    global $dbConn;   //asset_num is auto increment
    $sql = "insert into assets (asset_id,manufac_id,purch_date,purch_price,warranty_exp,comments) 
            values ('".$_SESSION['asset_id']."','".$_SESSION['manufac_id']."','".$_SESSION['purchaseDate']."','".
            $_SESSION['purchasePrice']."','".$_SESSION['warrantyExpDate']."','".$_SESSION['comments']."')";
    
        
    $stmt = $dbConn -> prepare ($sql);
    $stmt -> execute();
    //session_destroy();
    clearSession("insert");
    header("Location: index.php");
}


function getAssetId()
{
    global $dbConn;
    $sql = "SELECT asset_id FROM description GROUP BY timestamp DESC limit 1";
    $stmt = $dbConn -> prepare ($sql);
    $stmt -> execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    print_r($record);
    $_SESSION['asset_id']=$record['asset_id'];
    
    //echo $_SESSION['asset_id'];
    
    insertIntoAssetsTable();
}
function insertIntoDescription()
{
    global $dbConn;
    $sql = "insert into description (device_type,manufac_name,model_num) 
            values ('".$_SESSION['deviceType']."','".$_SESSION['name']."','".$_SESSION['model']."')";
    $stmt = $dbConn -> prepare ($sql);
    $stmt -> execute();
    
    getAssetId();
}   
    
function getManufcId()
{
    global $dbConn,$name,$address,$phone,$url;
    
    $sql = "SELECT manufac_id FROM support WHERE manufac_name = '".$name."' and web_page = '".$url."'";
    $stmt = $dbConn -> prepare ($sql);
    $stmt -> execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['manufac_id']=$record['manufac_id'];
    //print_r($record);
    //print_r($_SESSION['manufac_id']);
    
    insertIntoDescription();
    
}






function insertIntoSupport()
{
    global $dbConn,$name,$address,$phone,$url;
    
    $sql = "insert into support (manufac_name,address,phone,web_page) values
            ('".$name."','".$address."','".$phone."','".$url."')";
    
    $stmt = $dbConn -> prepare ($sql);
    $stmt -> execute();
    getManufcId();
    //print_r($sql);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title> Insert Manf. Info.</title>
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
        <div class="jumbotron">
        <h1>Enter Manufacturer Information</h1>
        </div>
        <table>
            <form>
                
                <div class="form-group">
                    <tr><td>
                    Name: </td><td><input type="text" name="name" value="<?=$_SESSION['name']?>"/></td></tr>
                    <tr><td>
                    Address: </td><td><input type="text" name="address"/></td></tr>
                    <tr><td>
                    phone:</td><td><input type="phone" name="phone"/></td></tr>
                    <tr><td>
                    URL:</td><td><input type="text" name="url"/></td></tr>
                    <tr><td>
                    <input type="submit" name="submit" value="Submit" id="submit"/>
                    </td></tr>
                </div>
            </form>
        </table>
        <?php
            if(isset($_GET['submit']))
            {
                //echo "Hello from if";
                insertIntoSupport();
            }
        ?>
    </body>
</html>