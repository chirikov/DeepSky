<?php
        setcookie ("ngpe_id", "", time()-30);
        setcookie ("ngpe_access", "",  time()-30);
		header("Location:index.php");
?>