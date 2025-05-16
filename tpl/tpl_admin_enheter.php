<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <form class="nogrid" action="', SITEURL , '/admin/enheter/filtering" method="post" enctype="multipart/form-data">
    <section>
        <h2>Enheter ' , !empty($tpl_params['admin_top_content']) ? '<span class="floatright">'.$tpl_params['admin_top_content'].'</span>' : '' , ' </h2>
         <ul class="list machines">
            <li>
                <ul class="headers">
                    <li>ID</li>
                    <li>Tittel</li>
                    <li>Tagg</li>
                    <li>Status</li>
                    <li>Dato</li>
                </ul>
            </li>
            <li>
                <ul class="headers input">
                    <li> </li>
                    <li><input type="text" class="sinput" id="tittel" /></li>
                    <li><input type="text" class="sinput" id="tagg" /> </li>
                    <li><input type="text" class="sinput" id="status" /></li> 
                    <li> </li>
                </ul>
            </li>';

foreach($tpl_params['machines'] as $id => $b) {
    echo '
            <li class="machs status' , !empty($b['status']) ? $b['status'] : '' , '">
                <ul>
                    <li class="id_machine q-' , !empty($b['tagg']) ? $b['tagg'] : '' , '-q">';
    echo '
                            <a  onclick="return confirm(\'Er du sikker på du vil slette enhet ' . $b['id'] . ' (' . $b['tittel'] . '?\');" href="' . SITEURL . '/admin/enheter/slette/'. $b['id'] .'"><img class="half" src="' . SITEURL . '/ext/delete.svg" alt="" /></a>';
    echo '                                    
                    ', $b['id'] , '</li>
                    <li><a href="' . SITEURL . '/admin/enheter/edit/' . $id . '">' , $b['tittel'] , '</a></li>
                    <li>' , !empty($b['tagg']) ? strtolower($b['tagg']) : '' , '</li>
                    <li>' , !empty($b['status']) ? $tpl_params['status'][$b['status']] : '' , '</li>
                    <li>' , date("Y-m-d H:i",$id), '</li>
                </ul>
            </li>';
}
echo '
        </ul>
    </section>
            <p id="sbuttons" style="padding-top: 1rem; clear: both;">
                <input type="submit" class="btn btn-primary" value="Lagre"> 
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