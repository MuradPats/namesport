<?php

function nf($nr){ return number_format($nr, 2, ",", " "); }

function stringtoDB($s){
  $d = str_replace("\r\n", "<br />", $s);
  $d = str_replace("<?","",$d);
  return htmlspecialchars($d,ENT_QUOTES);
}
function stringfromDB($s){
  $d = html_entity_decode($s,ENT_QUOTES);
  return $d;
}
function stringfromDBtoTA($s){
  $d = html_entity_decode($s,ENT_QUOTES);
  return str_replace("<br />","\r\n",$d);
}


function months($m){
	$m = intval($m);
	switch($m){
		case 1: return "jaanuar";
		case 2: return "veebruar";
		case 3: return "märts";
		case 4: return "aprill";
		case 5: return "mai";
		case 6: return "juuni";
		case 7: return "juuli";
		case 8: return "august";
		case 9: return "september";
		case 10: return "oktoober";
		case 11: return "november";
		case 12: return "detsember";
	}
}

function anum($str){ return preg_replace('/\W+/', '', str_replace(array("õ", "ä", "ö", "ü", " ", "?", "!"), array("o", "a", "o", "u", "", "", ""), $str)); }
function checkbox($name,$val){ return "<input type='checkbox' name='".anum($name)."'".($val=="Y"?" checked='checked'":"")." value='Y' class='checkboxcss' />"; }
function textbox($name, $value, $maxlength){ return "<input type='text' name='".anum($name)."' ".(strlen($value)>0?"value='$value'":"")." maxlength='$maxlength' class='inputcss' />"; }
function textarea($name, $value, $width, $height){ return "<textarea name='".anum($name)."' rows='$height' cols='$width' class='inputcss'>".(strlen($value)>0?stringfromDBtoTA($value):"")."</textarea>"; }
//function passwordbox($name, $size, $maxlength){       return "<input type='password' name='".anum($name)."' size='".($size?$size:"50")."' maxlength='$maxlength' class='inputcss' />";}
function hiddenbox($name,$value){return "<input type='hidden' name='".anum($name)."' value='$value' />"; }
function submitbutton($value){return "<input type='submit' name='".anum($value)."' value='$value' class='submitcss' />"; }
function submitbtn($name, $value){return "<input type='submit' name='".anum($name)."' value='$value' class='submitcss' />"; }
function kcombo($name,$array,$selected){ $s = "<select name='".anum($name)."' class='inputcss'>"; foreach($array as $k=>$v) $s .= "<option value='$k'".($selected==$k?" selected='selected'":"").">$v</option>"; return $s .= "</select>"; }

function scombo($name,$array,$selected,$emptyval=false){
        $s = "<select name='".anum($name)."' id='".anum($name)."' class='inputcss'>";
        if($emptyval) $s .= "<option value=''>---</option>";
        foreach($array as $k=>$v)
                $s .= "<option value='$v'".($selected==$v?" selected='selected'":"").">$v</option>";
        return $s . "</select>";
}

function printHeader(){
 	header("Content-Type: text/html; charset=utf-8");
	header('Expires: ' . gmdate('D, d M Y H:i:s') . 'GMT');
	header('Cache-Control: no-cache;');
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	echo "<head>\n<title>...</title>";
	echo "<link rel=\"stylesheet\" href=\"/graphics/reset.css\" type=\"text/css\" media=\"screen\" />";
	echo "<link rel=\"stylesheet\" href=\"/graphics/style.css\" type=\"text/css\" media=\"screen\" />";
	echo "<link rel=\"stylesheet\" href=\"/graphics/intra.css\" type=\"text/css\" media=\"screen\" />";

	echo "<script type='text/javascript' src='https://www.google.com/jsapi'></script></head><body>";
}

function printFooter(){
	echo "</body></html>";

}



?>