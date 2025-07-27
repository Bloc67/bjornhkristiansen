<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

if($subaction == 'om') {
    echo '
    Autodidakt amatørmaler fra Fredrikstad, bodd i Molde siden 1998. 
    ';
}
elseif($subaction == 'kontakt') {
    echo '
    Henvendelser via <a href="mailto:post@bhkristiansen.com">epost</a> eller <a href="https://www.facebook.com/bjoernhkristiansen/">Facebook</a>. 
    ';
}
else {
  	header("location: ". SITEURL);
}

?>