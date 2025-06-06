<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <aside>
		<h1><a href="' , SITEURL , '">Bjørn H Kristiansen</a></h1>
        <ul>
            <li><a href="'.SITEURL.'/siste">' , date("Y", time()) , '</a></li>
            <li><a href="'.SITEURL.'/tidligere">Tidligere</a></li>
        </ul>
    </aside>
    <main>
        <ul id="bilder">';

if(!empty($tpl_params['machines'])) {
    echo '
        <ul id="bilder">';
    foreach($tpl_params['machines'] as $timestamp => $bilde) {
        if(!empty($bilde['jpg']))
           echo '
            <li>
                <a href="'.SITEURL.'/bilde/'.$timestamp.'">
                    <img src="' , SITEURL , '/ext/jpg/' , $bilde['jpg'] , '" alt="" />
                    <span>' , $bilde['tittel'] , '</span>
                <a>
            </li>';
    }
    echo '
        </ul>';
}
else {
    echo '
        <h3>Ingen funnet.</h3>';
}

echo '
    </main>
        ';
    
?>