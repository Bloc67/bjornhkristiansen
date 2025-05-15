<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
   <section>
        <h2>Brukere <small class="floatright"><a href="' . SITEURL . '/admin/brukere/ny">Legg til ny bruker</a></small></h2>
        <ul class="list">
            <li>
                <ul class="headers">
                    <li>Bruker</li>
                    <li>Registrert</li>
                    <li>Sist Logget inn</li>
                    <li>Nivå</li>
                    <li>Epost</li>
                    <li></li>
                </ul>
            </li>';

foreach($tpl_params['brukere'] as $id => $b) {
    echo '
            <li>
                <ul>
                    <li><a href="' . SITEURL . '/admin/brukere/edit/' . $id . '">' , $b['username'] , '</a></li>
                    <li>' , $b['created_at'] , '</li>
                    <li>' , date("d.m.Y - H:i",$b['lastlogin']) , '</li>
                    <li>' , $b['level_user'] , '</li>
                    <li>' , $b['email_user'] , '</li>
                    <li>
                        <a  onclick="return confirm(\'Er du sikker på du vil slette bruker ' . $b['username'] . '?\');" href="' . SITEURL . '/admin/brukere/slette/'. $b['id'] .'">
                            <img class="half" src="' . SITEURL . '/ext/delete.svg" alt="" />
                        </a>
                        ' , !empty($b['verified_user']) ? '<img src="' .SITEURL. '/ext/checked.svg" alt="ja" />' : '' , '
                     
                    </li>
                </ul>
            </li>';
}
echo '
        </ul>
    </section>';

?>