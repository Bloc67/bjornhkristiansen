<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <section>
        <h2>' , $tpl_params['title'] ,'</h2>
        <div class="mm-columns" id="artlist">';

foreach($tpl_params['machines'] as $id => $b) {
    if(!empty($b['tittel'])) {
        echo '
        <div class="mm-columns__item">
                ' , !empty($b['jpg']) ? '<img class="mm-columns__img thumb" data-target="#b'.$id.'" src="' . SITEURL . '/ext/jpg'. (file_exists(SITEDIR.'/ext/jpg_thumb/'.$b['jpg']) ? '_thumb' : '') . '/'.$b['jpg'].'" alt="" />
                <img id="b'.$id.'" class="full" src="' . SITEURL . '/ext/jpg/'.$b['jpg'].'" alt="" />' : '' , '
                <span class="imgspan"><b>' , $b['tittel'] , '</b> ';
        foreach($b['tagg'] as $tag) {
            echo '<a href="' . SITEURL . '/tagg/' . $tag . '">'.$tag.'</a>';
        }
                    
        echo '</span>
        </div>';

    }
}
echo '
        </ul>
    </section>
        <script>            
          $(document).ready(function(){
            $("img.thumb").on("click", function() {
              var value = $(this).attr(\'data-target\');
              $(value).addClass("showit");
              $("#artlist").addClass("showit");
            });
          });
          $(document).ready(function(){
            $("img.full").on("click", function() {
              $(this).removeClass("showit");
              $("#artlist").removeClass("showit");
            });
          });
        </script>
        ';
    
?>