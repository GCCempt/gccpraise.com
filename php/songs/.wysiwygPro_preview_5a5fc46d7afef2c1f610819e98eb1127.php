<?php
if ($_GET['randomId'] != "NS7ENxVSx4R0gpCUDpIYqAWX8_C6Mu9v4tC4RjTP7iNtQHxD0WZgzlmgVPd0JJna") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
