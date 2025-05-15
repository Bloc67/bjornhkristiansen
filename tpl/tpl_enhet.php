<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
<div id="single_enhet">
   <section class="sidebar">
        <h3>Av samme type</h3>
        <ul class="menu">';

foreach($tpl_params['similar'] as $id => $m) {
    echo '
            <li class="minienhet' , $id == $tpl_params['machine']['id_machine'] ? ' active' : '' , '">
                <a class="link" href="' . SITEURL . '/enhet/' . $id . '">' , $m['title'] , '</a> -
                ' , $m['memory'] , ' / ' , $m['disk0'] , '
            </li>';
}
echo '
        </ul>
    </section>
    <section class="machine">';

$m = $tpl_params['machine'];
echo '
        <a class="thickbox" target="_blank" href="' . SITEURL . '/ext/jpg/' . $m['picture'] .'" style="background-image: url(' . SITEURL . '/ext/jpg/' , strtolower($m['tagg']).'.jpg);"></a>
        <h2>' , $m['title'] , '</h2>
        <div class="data">
            <dl class="details">
                <dt>Minne</dt><dd>' , $m['memory'] , '</dd>
                <dt>Disk</dt><dd>' , $m['disk0'] , ' (', $m['disk0type'] , ')</dd>
                <dt>OS</dt><dd>Win10</dd>
                <dt>CPU</dt><dd>' , $m['cpu'] , '</dd>
                <dt>Tilstand</dt><dd>' , $m['healthtext'] , '</dd>
            </dl>
        </div>
    </section>
 </div>';
 
?>