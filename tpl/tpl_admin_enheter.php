<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

$is_printing = false;
if(!empty($tpl_params['print'])) {   
    $is_printing = true; 
    echo '
    <form class="nogrid" action="', SITEURL , '/admin/enheter/utskrift" method="post" enctype="multipart/form-data">';
}
else {
    echo '
    <form class="nogrid" action="', SITEURL , '/admin/enheter/filtering" method="post" enctype="multipart/form-data">';
}
echo '
    <section>
        <h2>Enheter ' , !empty($tpl_params['admin_top_content']) ? '<span class="floatright">'.$tpl_params['admin_top_content'].'</span>' : '' , ' </h2>
        <ul class="menytagg">
            ';
$all = 0;
foreach($tpl_params['tagger'] as $tag => $hits) {
    echo '
            <li class="taggbutton" data-target="' . strtolower($tag) . '">' . strtolower($tag) . ' ('.$hits.')</li>'; 
    $all = $all + $hits; 
}

echo '
            <li class="taggbutton" data-target="">Alle ('.$all.')</li>
        </ul>
        <ul class="list machines">
            <li>
                <ul class="headers">
                    <li>';
if(!empty($tpl_params['print'])) {
    echo '
                        <input type="checkbox" name="printall" id="printall">';
}
echo '
                    ID</li>
                    <li>Serienummer</li>
                    <li>PDF</li>
                    <li>Enhet</li>
                    <li>Tagg</li>

                    <li>Type</li>
                    <li>CPU</li>
                    <li>Minne</li>
                    <li>Status</li>
                    <li>Tilstand</li>
                    <li>OS</li>
                    <li>Dato</li>
                </ul>
            </li>
            <li>
                <ul class="headers input">
                    <li> </li>
                    <li><input type="text" class="sinput" id="serial" /></li>
                    <li> </li>
                    <li><input type="text" class="sinput" id="title" /></li>
                    <li> </li>

                    <li> </li>
                    <li><input type="text" class="sinput" id="cpu" /></li>
                    <li><input type="text" class="sinput" id="memory" /></li>
                    <li><input type="text" class="sinput" id="status" /></li> 
                    
                    <li><input type="text" class="sinput" id="healtstate" /></li>
                    <li><input type="text" class="sinput" id="os" /></li>
                    <li> </li>
                </ul>
            </li>';

foreach($tpl_params['machines'] as $id => $b) {
    echo '
            <li class="machs status' . $b['status'] . '">
                <ul>
                    <li class="id_machine q-' . $b['tagg'] . '-q">';
    if(!empty($tpl_params['print'])) {
        echo '
                        <input class="checks" type="checkbox" name="check' , $b['id_machine'] , '" value="1"' , !empty($b['skrivut']) ? ' checked="checked"' : '' , '>
                        <input type="hidden" name="print' , $b['id_machine'] , '" value="' , $b['skrivut'] , '">
                        ';
    } 
    else {
        echo '
                            <a  onclick="return confirm(\'Er du sikker på du vil slette enhet ' . $b['id_machine'] . ' (' . $b['title'] . '/' . $b['serial'] . ')?\');" href="' . SITEURL . '/admin/enheter/slette/'. $b['id_machine'] .'"><img class="half" src="' . SITEURL . '/ext/delete.svg" alt="" /></a>';
    } 
        echo '                                    
                    ', $b['id_machine'] , '</li>
                    <li>' , $b['serial'] , '</li>
                    <li>' , !empty($b['delpdf']) ? '<a href=""><img class="half" src="' . SITEURL . '/ext/pdf.svg" alt="" /></a>' : '' , '</li>
                    <li><a href="' . SITEURL . '/admin/enheter/edit/' . $id . '">' , $b['title'] , '</a></li>
                    <li>' , strtolower($b['tagg']) , '</li>
                    <li><img title="' , $b['pctype'] ,'" class="half" src="' . SITEURL . '/ext/' , $b['pctype']=='Notebook' ? 'laptop.svg' : 'desktop.svg' , '" alt="" /></li>
                    <li>' , $b['cpu'] , '</li>
                    <li>' , $b['memory'] , '</li>
                    <li>' , $tpl_params['status'][$b['status']] , '</li>
                    <li>' , $tpl_params['healthstate'][$b['healthstate']] , '</li>
                    <li>' , !empty($os[$b['os']]) ? $os[$b['os']] : '' , '</li>
                    <li>' , $b['deldate'] , '</li>
                </ul>
            </li>';
}
echo '
        </ul>
    </section>
    ';
 
if(!empty($tpl_params['print'])) {    
    echo '
            <p id="sbuttons" style="padding-top: 1rem; clear: both;">
                <input type="submit" class="btn btn-primary" value="Lagre"> 
                <a class="cancel" href="' . SITEURL . '/admin/enheter">Avbryt</a>
                <a class="submit olink" target="_new" href="' . SITEURL . '/admin/utskrift"><span>Skriv ut</span></a>
            </p>
        </form>
    <script>
        $(document).ready(function() {
            $("#printall").click(function() {
                var checkBoxes = $(".checks");
                checkBoxes.prop("checked", $(this).prop("checked"));
            });                 
        });    
    </script>
        ';
}
else {
    echo '
        </form>';
}
echo '
<script>
$(document).ready(function(){
  $(".sinput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("li.machs").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
$(document).ready(function(){
  $(".taggbutton").on("click", function() {
    $(".taggbutton").removeClass(\'active\');
    var value = $(this).attr(\'data-target\').toLowerCase();
    $("li.machs").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    $(this).addClass(\'active\');
  });
});
</script>
        ';
    
?>