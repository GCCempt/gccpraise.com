<?php
if ($_GET['randomId'] != "Y3B9tHZXpD9ZkLSUjiclIurPIzuOJmcou6L8aOIlNfTohYzpSOHDBoPSkeAWSN6d") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
