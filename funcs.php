<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

function is_logged_in() {

    global $mysqli, $settings;
    
    // fallback
    if(empty($settings['timeout'])) {
        $settings['timeout'] = 3600; 
    }
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        // check if its timed out.(having an open window without clicking for one hour)
        if(time() - $_SESSION['lastlogin'] > $settings['timeout']) {
            // logout and request to login in again
            header("Location: ". SITEURL . "/logout/auto");
            exit;
        }
        else {
            // update last login
            $_SESSION['lastlogin'] = time();
//            echo '<pre>' , print_r($_SESSION) , '</pre>';
//            die();
            return true;
        }
    }
    else {
        return false;
    }
} 

function render_form($target, $elements, $submit_text, $extra_text = '') {
    
    // render a form, guided by elements, a target and added extra text
    echo '
    <form action="', $target , '" method="post" enctype="multipart/form-data">
        <div class="formelements">';
    $first = true; $trigger = false;
    
    foreach($elements as $o => $e) {

        // a fucntion call?
        if(!empty($e['func'])) {
            $tmp = call_user_func($e['func'], $e['value']);
            $e['value'] = $tmp;
        }
        echo '
            <div class="form-group">
                <label>' , !empty($e['label']) ? $e['label'] : '' , '</label>';
        
        if($e['type'] == 'text') {
            
            if(!empty($e['choices'])) {
                echo '
                <div class="selectbox">
                    <select class="selectbox" data-target="' , $o , '">
                        <option value="">- preset -</option>';
                foreach($e['choices'] as $c => $cb) {
                    echo '
                        <option value="' , $c , '">' , $c , '</option>';
                }
                echo '
                    </select>
                    <input id="' , $o , '" type="text" name="' , $o , '" class="bruker' , !empty($e['err']) ? ' is-invalid' : '' , '" value="' , $e['value'], '">
                </div>';
            }
            else {
                echo '
                <input type="text" name="' , $o , '" class="bruker' , !empty($e['err']) ? ' is-invalid' : '' , '" value="' , $e['value'], '">';
            }
        }
        elseif($e['type'] == 'textarea') {
            echo '
                <textarea name="' , $o , '" class="bruker' , !empty($e['err']) ? ' is-invalid' : '' , '">' , $e['value'], '</textarea>';
        }
        elseif($e['type'] == 'password') {
            echo '
                <input type="password" name="' , $o , '" class="bruker' , !empty($e['err']) ? ' is-invalid' : '' , '">';
        }
        elseif($e['type'] == 'divide') {
            // trigger a split
            if($first) {
                $trigger = true;
            }
        }
        elseif($e['type'] == 'lock') {
            echo '
                <p class="locked">' , $e['value'], '</p>';
        }
        elseif($e['type'] == 'file') {
            echo '
                <div>';
            if(!empty($e['var'])) {
                if(!empty($e['is_img'])) {
                    echo '
                    <a href="'.SITEURL.'/ext/jpg/' . $e['var'] . '" target="_blank"><img src="'.SITEURL.'/ext/jpg/' . $e['var'] . '" alt="" class="thumb" /></a>';
                }
                else {
                    echo '<p class="icon"><img src="'.SITEURL.'/ext/pdf.svg" alt="" />' , $e['var'], '</p>';
                }
            }
            echo '
                    <input type="file" class="box" name="' , $o , '" class="bruker' , !empty($e['err']) ? ' is-invalid' : '' , '" accept="' . $e['accept'] . '">
                </div>';
        }
        elseif($e['type'] == 'checkbox') {
            echo '
                <input type="checkbox" name="' , $o , '" class="bruker" style="justify-self: start;" value="1" ' , empty($e['value']) ? '' : ' checked="checked"' , '>';
        }
        elseif($e['type'] == 'select') {
            echo '
                <select name="' , $o , '" class="bruker">';
            
            foreach($e['options'] as $val => $opt) {
                echo '
                    <option value="' , $val , '"' , $val == $e['value'] ? ' selected="selected"' : '' , '>' , $opt , '</option>';
            }
            echo '
                </select>';
        }
        elseif($e['type'] == 'radio') {
            echo '
                <div class="singleline">';
            foreach($e['options'] as $val => $opt) {
                echo '
                <input type="radio" name="' , $o , '" class="bruker" style="justify-self: start;" value="' . $val . '" ' , $e['value']!=$val ? '' : ' checked="checked"' , '> '. $opt;
            }
            echo '
                </div>'; 
        }
        
        echo '
                <span class="invalid-feedback">', !empty($e['err']) ? $e['err'] : ''  , '</span>
            </div>';
        if($trigger && $first) {
            echo '
        </div><div class="formelements">';
            $first = false;
        }
    }
    echo '
        </div>
        <div class="submit-group">
            <input type="submit" class="btn btn-primary" value="' , $submit_text , '">
            </div>
            ' , $extra_text , '
        </div>
        <script> 
            $(document).ready(function(){
                $(\'.selectbox\').change(function()
                {
                    var this_value = $(this).val();
                    var target = $(this).attr(\'data-target\');
                    $("#" + target).val(this_value);
                    $("#" + target).addClass(\'changedvariable\');

                });
            });
        </script>
    </form>';
}

function xml_rip($fil, $startstring,$endstring, $preprocess = '', $prepreprocess = '') {
    if(!empty($prepreprocess)) {
        $p = explode($prepreprocess,$fil);
        if(!is_array($p)) {
            return;
        }
        $fil = $p[1];
    }

    if(!empty($preprocess)) {
        $p = explode($preprocess,$fil);
        if(!is_array($p)) {
            return;
        }
        $a = explode($startstring,$p[1]);
        if(!is_array($a)) {
            return;
        }
    }
    else {
        $a = explode($startstring,$fil);
        if(!is_array($a)) {
            return;
        }
    }
    if(!isset($a[1])) {
        return;
    }
    $b = explode($endstring,$a[1]);
    if(!is_array($b)) {
        return;
    }
    $c = trim($b[0]);
    return $c;
}

function stripstuff($a , $nospace = false) {
    $b = str_replace(array(",","'"), array(" ","`"), $a);
    $c = htmlspecialchars($b);
    if($nospace) {
        $d = str_replace(array(" "), array("_"), $c);
        return $d;
    }
    else {
        return $c;
    }
}

function convert2timestamp($tid) {
    // check the string
    $split = explode(", ",$tid);
    $d = explode(" ",$split[0]);
    $month = $d[0];
    $day = substr($d[1],0,strlen($d[1]-1));
    $h = explode(" ",trim($split[1]));
    $year = $h[0];
    $a = explode(":",trim($h[1]));
    $hour = $a[0];
    $minute = $a[1];
    $second = $a[2];
    
    if(strlen($month>3)) {
        $mnd = array('January' => 1,'February' => 2,'March' => 3,'April' => 4,'May' => 5,'June' => 6,'July' => 7,'August' => 8,'September' => 9,'October' => 10,'November' => 11, 'December' => 12); 
    }
    else {
        $mnd = array('Jan' => 1,'Feb' => 2,'Mar' => 3,'Apr' => 4,'May' => 5,'Jun' => 6,'Jul' => 7,'Aug' => 8,'Sep' => 9,'Oct' => 10,'Nov' => 11, 'Dec' => 12); 
    }
    $dato = mktime($hour,$minute,$second,$mnd[$month],$day,$year);
  
    return $dato;
}

function filter_letternumber($string) {
    $return = preg_replace('/[^0-9a-z]/', '', $string);
    return $return;
}
function filter_letter($string, $lowercase = true) {
    $nstring = strtolower($string);
    $return = preg_replace('/[^a-z]/', '', $nstring);
    return $return;
}
function filter_number($string) {
    $return = preg_replace('/[^0-9]/', '', $string);
    return $return;
}
function timestamp2date($t) {
    return date("r", $t);
}

?>
