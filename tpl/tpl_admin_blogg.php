<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <form class="nogrid" action="', SITEURL , '/admin/blogg/filtering" method="post" enctype="multipart/form-data">
    <section>
        <h2>Blogg ' , !empty($tpl_params['admin_top_content']) ? '<span class="floatright">'.$tpl_params['admin_top_content'].'</span>' : '' , ' </h2>
         <ul class="art">';

foreach($tpl_params['machines'] as $id => $b) {
    $b['jpg'] = isset($b['jpg']) ? $b['jpg'] : '';
    $is_thumb = file_exists(SITEDIR . '/ext/jpg_thumb/'.$b['jpg']) ? '_thumb' : '';

    echo '
            <li class="machs status' , !empty($b['status']) ? $b['status'] : '' , empty($is_thumb) ? ' nothumb' : '' , '">
                ' , !empty($b['jpg']) ? '<a href="' . SITEURL . '/admin/blogg/edit/' . $id . '"><img class="thumb" src="' . SITEURL . '/ext/jpg' . $is_thumb . '/'.$b['jpg'].'" alt="" /></a>' : '' , '
                <dl>
                    <dt>Navn</dt><dd><a href="' . SITEURL . '/admin/blogg/edit/' . $id . '">' , $b['tittel'] , '</a></dd>
                    <dt>Slette</dt><dd><a onclick="return confirm(\'Er du sikker på du vil slette enhet ' . $b['id'] . ' (' . $b['tittel'] . '?\');" href="' . SITEURL . '/admin/blogg/slette/'. $b['id'] .'">bilde</a></dd>
                    <dt>Dato</dt><dd>' , date("Y-m-d H:i",$id), '</dd>
                </dl>
            </li>';
}
echo '
        </ul>
    </section>
        ';
    
?>