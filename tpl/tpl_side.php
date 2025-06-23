<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

if($subaction == 'om') {
    echo '
    Om
    ';
}
elseif($subaction == 'kontakt') {
    echo '
    Kontakt
    ';
}
else {
  	header("location: ". SITEURL);
}

?>