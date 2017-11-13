<?php
session_start();

function getDeviceType()
{
    $types = array("SmartPhone","Laptop","Desktop","Printer","Tablet","Router","Switch","Server");
    return $types;
}
function clearSession($forWhich)
{
    if($forWhich == "insert")
    {
        unset($_SESSION['name']);
        unset($_SESSION['model']);
        unset($_SESSION['deviceType']);
        unset($_SESSION['purchasePrice']);
        unset($_SESSION['purchaseDate']);
        unset($_SESSION['warrantyExpDate']);
        unset($_SESSION['comments']);
    }
     else
    {
        unset($_SESSION['asset_id']);
        unset($_SESSION['manufac_name']);
        unset($_SESSION['model']);
        unset($_SESSION['deviceType']);
        unset($_SESSION['purch_price']);
        unset($_SESSION['purch_date']);
        unset($_SESSION['warranty_exp']);
        unset($_SESSION['comments']);
        unset($_SESSION['retire_date']);
        unset($_SESSION['retire_descrip']);
    }
}
?>