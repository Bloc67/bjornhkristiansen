<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <section>
        <h2>Siste fra Galleriet</h2>
         <ul class="art">';

foreach($tpl_params['machines'] as $id => $b) {
    echo '
            <li class="machs status' , !empty($b['status']) ? $b['status'] : '' , '">
                ' , !empty($b['jpg']) ? '<a href="' . SITEURL . '/admin/enheter/edit/' . $id . '"><img class="thumb" src="' . SITEURL . '/ext/jpg/'.$b['jpg'].'" alt="" /></a>' : '' , '
                <dl>
                    <dt>Navn</dt><dd><a href="' . SITEURL . '/admin/enheter/edit/' . $id . '">' , $b['tittel'] , '</a></dd>
                    <dt>Tagger</dt><dd>' , !empty($b['tagg']) ? strtolower($b['tagg']) : '' , '</dd>
                </dl>
            </li>';
}
echo '
        </ul>
        ';
    
?>