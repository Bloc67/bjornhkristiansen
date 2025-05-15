<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
<main id="frontpage">
    <section class="filters">
        <h2>Filter</h2>
        <ul class="menu">';
foreach($tpl_params['filters'] as $type => $hits) {
    echo '
            <li' , $type == $tpl_params['filter_active'] ? ' class="active"' : '' , '><a href="' . SITEURL . '/filter/'.$type.'">' . $tpl_params['filters_labels'][$type] . ' <span class="pc_hits"><b></b>' . $hits . '</span></a></li>';    
}
echo '
            <li class="last"><a href="' . SITEURL . '">Vis alle</a></li>  
        </ul>
        <h2>Sortering</h2>
        <ul class="menu">';
foreach($tpl_params['sort'] as $sort => $label) {
    echo '
            <li' , $sort == $tpl_params['sort_active'] , '><a href="' . SITEURL . '/sort/' . $sort . '">' . $label . '</a></li>';
}
echo '
            <li class="last"><a href="' . SITEURL . '">Dato</a></li> 
        </ul>
    </section>

    <section class="machines">
        <ul class="menu">';

foreach($tpl_params['machines'] as $id_machine => $m) {
    echo '
            <li>
                <a class="image" href="' . SITEURL . '/enhet/' . $id_machine . '" style="background-image: url(' . SITEURL . '/ext/jpg/' , strtolower($m['tagg']).'.jpg);">
                    <h3>
                        <span>' , $m['title'] , '</span>
                        <small>' , $m['memory'] , ' / ' , $m['disk0'] , ' / Win10</small>                   
                        <small>' , $m['cpu'] , '</small>
                    </h3>
                </a>
            </li>';
}
echo '
        </ul>
    </section>
</main>';
 
?>