<?php
if ($_GET['randomId'] != "9_SQPVt2XngxuvVVwMn0GptqtYbmNiC_sK754RkCRR56L2XMitC5o7fxazTWLbf5") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
