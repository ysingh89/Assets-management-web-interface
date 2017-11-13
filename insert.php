<?php
session_start();
if (!isset($_SESSION["username"])) //Check whether the admin has logged in
{  
    header("Location: login.html"); 
}
    include '../inc/dbConn.php';
    include 'functions.php';
    $dbConn = getDBConnection("assets_management");
     
     $_SESSION['name']= $_GET['brand'];
     $_SESSION['model']= $_GET['model'];
     //$_SESSION['deviceType']= $_GET['deviceType'];
     $_SESSION['deviceType'] = strtolower($_GET['deviceType']);
     $_SESSION['purchasePrice']= $_GET['purchasePrice'];
     $_SESSION['purchaseDate']= $_GET['purchaseDate'];
     $_SESSION['warrantyExpDate']= $_GET['warrantyExpDate'];
     $_SESSION['comments']= $_GET['comments'];
    
    
    
    function insertIntoAssetsTable()
    {
        global $dbConn;   //asset_num is auto increment
                
        $sql = "insert into assets (asset_id,manufac_id,purch_date,purch_price,warranty_exp,comments) 
                values ('".$_SESSION['asset_id']."','".
                $_SESSION['manufac_id']."','".
                $_SESSION['purchaseDate']."',
                :purchasePrice,'".
                $_SESSION['warrantyExpDate']."',
                :comments)";
        $namedParameters[':purchasePrice'] = $_SESSION['purchasePrice'];
        $namedParameters[':comments'] = $_SESSION['comments'];
        $stmt = $dbConn -> prepare ($sql);
        $stmt -> execute($namedParameters);
        clearSession("insert");
        //session_destroy();
        //header("Location: insert.php");
    }
    
    
    function getAssetId()
    {
        global $dbConn;
        
        $sql = "SELECT asset_id FROM description GROUP BY timestamp DESC limit 1";
        $stmt = $dbConn -> prepare ($sql);
        $stmt -> execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['asset_id']=$record['asset_id'];

        insertIntoAssetsTable();
    }
    function insertIntoDescription()
    {
        global $dbConn;
        
        //echo $_SESSION['deviceType'];
        
        /*$sql = "insert into description (device_type,manufac_name,model_num) 
                values ('".$_SESSION['deviceType']."','".$_SESSION['name']."','".$_SESSION['model']."')";*/
        
        $sql = "insert into description (device_type,manufac_name,model_num) 
                values ('".$_SESSION['deviceType']."',:brand,:model)";        
        $namedParameters[':brand'] = $_SESSION['name'];
        $namedParameters[':model'] = $_SESSION['model'];
        
                
        $stmt = $dbConn -> prepare ($sql);
        $stmt -> execute($namedParameters);
        
        getAssetId();
    }
    
    
    function insertIntoSupportTable()
    {
        global $dbConn; //manufac_id, manufac_name, address, phone, webpage
        
        //$sql = "select manufac_id from support where manufac_name = '".$brand."'";
        
        $sql = "select manufac_id from support where manufac_name = :brand";
        $namedParameters[':brand']= $_SESSION['name'];
        $stmt = $dbConn -> prepare ($sql);
        $stmt -> execute($namedParameters);
        $records = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($records);
        echo"<br />";
        if(empty($records))
        {
            header("Location: manf.php");
        }
        else
        {
            $_SESSION['manufac_id'] = $records['manufac_id'];
            insertIntoDescription();
        }
    }    
    
    function insertAsset()
    {
        if(empty($_SESSION['name']))
        {
           echo "Manufacturer name Can't be empty!!!"; 
           echo"<br />";
           return;
        }
        if(empty($_SESSION['model']))
        {
           echo "Model number can't be empty!!!"; 
           echo"<br />";
           return;
        }
        if(empty($_SESSION['deviceType']))
        {
           echo "Must choose device type!!!"; 
           echo"<br />";
           return;
        }
        if(empty($_SESSION['purchasePrice']))
        {
           echo "Purchase price can't be empty!!!"; 
           echo"<br />";
           return;
        }
        if(empty($_SESSION['purchaseDate']))
        {
           echo "Select purchase date!!!"; 
           echo"<br />";
           return;
        }
        if(empty($_SESSION['warrantyExpDate']))
        {
           echo "Select warranty expiration date!!!"; 
           echo"<br />";
           return;
        }
        insertIntoSupportTable();
        echo"<br />";
        echo "Record Inserted Successfully!!";
    }
    
    
function selectType($type)
{
    //echo $type;
    if (strtoupper($_SESSION['deviceType']) == strtoupper($type)) 
    {
        return "selected";
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title> Assets Website </title>
        
        
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
    
        <h1>Insert a new record</h1>
            <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                <div class="navbar-header">
                  <a class="navbar-brand" href="#">My Asset Management</a>
                </div>
                <ul class="nav navbar-nav">
                  <li><a href="index.php">Main Page</a></li>
                  <li><a href="search.php">Search</a></li>
                  <li class="active"><a href="insert.php" id="currentpage"> Insert</a></li>
                  <li><a href="update.php">Update</a></li>
                </ul>
              </div>
            </nav>
        
        </div>
        
        <div class="table-responsive">
          <table>
            <form>
                <div class="form-group">
                    <tr><td>
                    Manufacturer Name: </td><td><input type="text" name="brand" value="<?=$_SESSION['name']?>"/>
                    </td></tr>
                    <tr><td>
                    Model: </td><td><input type="text" name="model" value="<?=$_SESSION['model']?>"/>
                    </td></tr>
                    <tr><td>
                    Device Type: </td><td><select name="deviceType">
                               <option value="">Select One</option>
                               <?php
                                 $deviceTypes = getDeviceType();
                                  foreach ($deviceTypes as $deviceType) 
                                  {
                                      echo "<option ".selectType($deviceType).">" . $deviceType. "</option>";
                                  }
                               ?>
                               </select>
                    </td></tr>
                    <tr><td>
                    Purchase Price:</td><td><input type="price" name="purchasePrice" value="<?=$_SESSION['purchasePrice']?>"/>
                    </td></tr>
                    <tr><td>
                    Date of Purchase: </td><td><input type="date" name="purchaseDate" value="<?=$_SESSION['purchaseDate']?>"/>
                    </td></tr>
                    <tr><td>
                    Warranty Expiration Date: </td><td><input type="date" name="warrantyExpDate" value="<?=$_SESSION['warrantyExpDate']?>"/>
                    </td></tr>
                    <tr><td>
                    Comments: </td><td><input type="text" name="comments" value="<?=$_SESSION['comments']?>"/>
                    </td></tr>
                    <tr><td>
                        </div>
                    <input type="submit" name="submit" value="Submit" id="submit"/>
                    </td></tr>
                </div>
            </form>
        </table>
        
        <?php
       
       if(isset($_GET['submit']))
            {
                //echo"from if";
                //print_r($brand);
                insertAsset();
            }
            //getDevices();
            //insertAsset();
            //deleteRecord();
            //updateRecord()
        ?>
    </body>
</html>