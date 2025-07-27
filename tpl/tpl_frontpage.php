<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <section>
        <h2>' , $tpl_params['title'] ,'</h2>
        <ul id="works">';
$y = date("Y",time());
foreach($tpl_params['machines'] as $id => $b) {
    if(!empty($b['tittel'])) {
        if($y!=$b['aar']) {
          echo '
          <li class="divy"><h3>' , $b['aar'] , '</h3></li>';
          $y = $b['aar'];
        }

        echo '
          <li>
            ' , !empty($b['jpg']) ? '<img class="thumb" data-target="#b'.$id.'" src="' . SITEURL . '/ext/jpg'. (file_exists(SITEDIR.'/ext/jpg_thumb/'.$b['jpg']) ? '_thumb' : '') . '/'.$b['jpg'].'" /> ' : '' , '
            <img id="b'.$id.'" class="full" src="' . SITEURL . '/ext/jpg/'.$b['jpg'].'" alt="" />
            <div>
              <h3>' , $b['tittel'] , '</h3>
              <p>';

        foreach($b['tagg'] as $tag) {
            echo '<a href="' . SITEURL . '/tagg/' . $tag . '">'.$tag.'</a> ';
        }
        echo '</p>', $b['tekst'] , '<hr class="soft">' , $b['materialer'] , '
            </div>
          </li>';

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