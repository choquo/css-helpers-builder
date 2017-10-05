<?php 
//CONFIGURATION FOR THE SCRIPT
//==================================

if( !isset($_GET['limit']) && !isset($_GET['step_by']) ){
	//Defaults
	$limit = 10;
	$step_by = 2;
	$show_line_numbers = false; //Debug
}else{
	$limit = $_GET['limit'];
	$step_by = $_GET['step_by'];
	$show_line_numbers = $_GET['show_line_numbers']; //Debug
} 


$wildcard_lg = isset($_GET['wildcard_lg']) ? $_GET['wildcard_lg'] : '.{classname}${s}{ {property}: ${u} !important; }';
$wildcard_md = isset($_GET['wildcard_md']) ? $_GET['wildcard_md'] : '.{classname}${s}-md{ {property}: ${u} !important; }';
$wildcard_sm = isset($_GET['wildcard_sm']) ? $_GET['wildcard_sm'] : '.{classname}${s}-sm{ {property}: ${u} !important; }';
$wildcard_xs = isset($_GET['wildcard_xs']) ? $_GET['wildcard_xs'] : '.{classname}${s}-xs{ {property}: ${u} !important; }';
$wildcard_xsl = isset($_GET['wildcard_xsl']) ? $_GET['wildcard_xsl'] : '.{classname}${s}-xsl{ {property}: ${u} !important; }';

//Option label for <select>
$wildcard_lg_option_label = isset($_GET['wildcard_lg_option_label']) ? $_GET['wildcard_lg_option_label'] : '{u}';
$wildcard_md_option_label = isset($_GET['wildcard_md_option_label']) ? $_GET['wildcard_md_option_label'] : '{u}';
$wildcard_sm_option_label = isset($_GET['wildcard_sm_option_label']) ? $_GET['wildcard_sm_option_label'] : '{u}';
$wildcard_xs_option_label = isset($_GET['wildcard_xs_option_label']) ? $_GET['wildcard_xs_option_label'] : '{u}';
$wildcard_xsl_option_label = isset($_GET['wildcard_xsl_option_label']) ? $_GET['wildcard_xsl_option_label'] : '{u}';

//Option values for <select>
$wildcard_lg_option = isset($_GET['wildcard_lg_option']) ? $_GET['wildcard_lg_option'] : '{classname}${s}';
$wildcard_md_option = isset($_GET['wildcard_md_option']) ? $_GET['wildcard_md_option'] : '{classname}${s}-md';
$wildcard_sm_option = isset($_GET['wildcard_sm_option']) ? $_GET['wildcard_sm_option'] : '{classname}${s}-sm';
$wildcard_xs_option = isset($_GET['wildcard_xs_option']) ? $_GET['wildcard_xs_option'] : '{classname}${s}-xs';
$wildcard_xsl_option = isset($_GET['wildcard_xsl_option']) ? $_GET['wildcard_xsl_option'] : '{classname}${s}-xsl';

//Unit value for <select>
$string_in_classname = isset($_GET['string_in_classname']) ? $_GET['string_in_classname'] : 'px'; //On the name of the class
$unit_for_value = isset($_GET['unit_for_value']) ? $_GET['unit_for_value'] : 'px'; //Inside the CSS rule

//Allow specific lines
$allow_lines = isset($_GET['allow_lines'])?$_GET['allow_lines']:'';

//Disallow specific lines
$disallow_lines = isset($_GET['disallow_lines'])?$_GET['disallow_lines']:'';

$classname = isset($_GET['classname'])?$_GET['classname']:'mt';
$property_name = isset($_GET['property_name'])?$_GET['property_name']:'margin-top';
//==================================
?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CSS Helpers Builder - by Carlos Maldonado @choquo</title>

	<!-- Bootstrap 3.0.1  -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css">
	<!-- jQuery 1.11.2 -->
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<!-- Prettify -->
	<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
	<!--<link rel="stylesheet" type="text/css" href="prettify.css">-->

	<style>
		pre{
			font-size: 12px;
		}
	</style>

	<script>
	    $(function(){
	        $("#clear").click(function(){
	        	$("form input, form textarea").val('');
	        	$("form input[type=checkbox]").prop('checked', false);
	        	return false;
	        });
	    });
	</script>
</head>
<body>

	<form action="." method="get">
		
		<div class="container-fluid">
	        <div class="col-md-12">
				<h2 style="margin-bottom: 15px;">CSS Helpers Builder</h2>
				<p>By Carlos Maldonado (@choquo)</p>
				<br>
				<strong>Lines</strong>
				<input name="limit" type="number" min="1" value="<?=isset($_GET['limit'])?$_GET['limit']:$limit?>">
				&nbsp;&nbsp;
				<strong>Step by</strong>
				<input name="step_by" type="number" min="1" value="<?=isset($_GET['step_by'])?$_GET['step_by']:$step_by?>">
				&nbsp;&nbsp;
				<strong>Filename <small>(optional)</small></strong>
				<input name="filename" type="text" value="<?=isset($_GET['filename'])?$_GET['filename']:''?>">
				&nbsp;&nbsp;
				<label>
				<input <?=isset($_GET['show_line_numbers'])?'checked':''?> type="checkbox" name="show_line_numbers" value="1">
				(Debug) Show line numbers</label>
				<br><br>

				<div class="clearfix"></div>
				<strong>Allow step number <small>(separate by space)</small></strong>
				<input type="text" name="allow_lines" value="<?=$allow_lines?>">
				&nbsp;&nbsp;
				<strong>Disallow step number <small>(separate by space)</small></strong>
				<input type="text" name="disallow_lines" value="<?=$disallow_lines?>">
				<br><br>
				<div class="clearfix"></div>

				<button class="btn btn-default btn-success">Build</button> &nbsp;&nbsp; 
				<button id="clear" class="btn btn-default">Clear</button> &nbsp;&nbsp;
				<a class="btn btn-default" href="./">Restart</a>
				<br><br>
				<h3>Templates</h3>
				Wildcard <strong style="font-size: 20px;">$</strong> will be replaced for the numeric value of each iteration, use <strong style="font-size: 20px;">{s}</strong> to add a string into a classname, and <strong style="font-size: 20px;">{u}</strong> to replace string values inside the css declaration.
				<br><br>
				<input type="text" name="classname" value="<?=$classname?>"> <strong>Classname</strong><br>
				<input type="text" name="property_name" value="<?=$property_name?>"> <strong>Property name</strong>
				<br><br>
				<input type="text" name="string_in_classname" value="<?=$string_in_classname?>"> <strong>{s} String to use in classname</strong><br>
				<input type="text" name="unit_for_value" value="<?=$unit_for_value?>"> <strong>{u} String to use in value inside css rule</strong>
				<br><br>
	        </div>
		</div>
		
		<div class="container-fluid">
		    
	        <div class="col-md-3" style="margin-bottom: 20px;">
	        	<strong>LG</strong> <small>CSS Rule</small>
	        	<input style="width: 100%;" name="wildcard_lg" value="<?=$wildcard_lg?>">
	        	<small>Label for the option (on select component)</small>
	        	<input style="width: 100%;" name="wildcard_lg_option_label" value="<?=$wildcard_lg_option_label?>"><br>
	        	<small>Value for the option (on select component)</small>
	        	<input style="width: 100%;" name="wildcard_lg_option" value="<?=$wildcard_lg_option?>">
	        </div>
	        <div class="col-md-3" style="margin-bottom: 20px;">
				<strong>MD</strong> <small>CSS Rule</small>
				<input style="width: 100%;" name="wildcard_md" value="<?=$wildcard_md?>">
	        	<small>Label for the option (on select component)</small>
	        	<input style="width: 100%;" name="wildcard_md_option_label" value="<?=$wildcard_md_option_label?>"><br>
				<small>Value for the option (on select component)</small>
				<input style="width: 100%;" name="wildcard_md_option" value="<?=$wildcard_md_option?>">
	        </div>
	        <div class="col-md-3" style="margin-bottom: 20px;">
				<strong>SM</strong> <small>CSS Rule</small>
				<input style="width: 100%;" name="wildcard_sm" value="<?=$wildcard_sm?>">
	        	<small>Label for the option (on select component)</small>
	        	<input style="width: 100%;" name="wildcard_sm_option_label" value="<?=$wildcard_sm_option_label?>"><br>
				<small>Value for the option (on select component)</small>
				<input style="width: 100%;" name="wildcard_sm_option" value="<?=$wildcard_sm_option?>">
	        </div>
	        <div class="col-md-3" style="margin-bottom: 20px;">
				<strong>XS</strong> <small>CSS Rule</small>
				<input style="width: 100%;" name="wildcard_xs" value="<?=$wildcard_xs?>">
	        	<small>Label for the option (on select component)</small>
	        	<input style="width: 100%;" name="wildcard_xs_option_label" value="<?=$wildcard_xs_option_label?>"><br>
				<small>Value for the option (on select component)</small>
				<input style="width: 100%;" name="wildcard_xs_option" value="<?=$wildcard_xs_option?>">
	        </div>
	        <div class="clearfix"></div>
	        <div class="col-md-3" style="margin-bottom: 20px;">
				<strong>XSL</strong> <small>CSS Rule</small>
				<input style="width: 100%;" name="wildcard_xsl" value="<?=$wildcard_xsl?>">
	        	<small>Label for the option (on select component)</small>
	        	<input style="width: 100%;" name="wildcard_xsl_option_label" value="<?=$wildcard_xsl_option_label?>"><br>
				<small>Value for the option (on select component)</small>
				<input style="width: 100%;" name="wildcard_xsl_option" value="<?=$wildcard_xsl_option?>">
	        </div>
		</div>

	</form>

	<div class="clearfix"></div>

	<div class="container-fluid">
		<div class="col-md-12">
		<?php
		//LG or DEFAULT
		//.fs2px{ font-size: 2px !important; }
		echo '<h3>LG or DEFAULT</h3>';
		echo "<p>Note that classnames on default doesn't have suffix <strong>-lg</strong>, usually i don't write for LG because this classes
		      can be placed at the top of your css (so, it's the default size), in some cases other helper classes may need declare the -lg suffix, 
		      if this is your case just modify your LG templates adding -lg suffix and rebuild.</p>";
		echo '<pre class="prettyprint">

';
$lg_output .= '/* '.$_GET['filename'].'
-----------------------------------------------------------------
This file was generated automatically by CSS Helpers Builder Tool
URI: https://github.com/choquo/css-helpers-builder
Licence: https://github.com/choquo/css-helpers-builder/LICENSE
About Licence: MIT (https://opensource.org/licenses/MIT)
Author: Carlos Maldonado @choquo
Build date: '.date("Y-m-d").'
*/'.PHP_EOL.PHP_EOL;
$lg_output .= '';
echo $lg_output;
		$counter=0;
		$select_option='';
		for ($i=0; $i < $limit; $i++) { 

			//Allow or disallow numbers			
			//Allow
			$allow_ok = false;
			if( $allow_lines!='' ){
				$allow_explode = explode(" ",$allow_lines);
				foreach($allow_explode as $allowed_number){
					if( $allowed_number == ($i+1) ){
						$allow_ok = true; //Tun on the flag
					}
					if( $allowed_number==0 ){
						$allow_zero=true;
					}
				}
			}
			//Disallow
			$disallow_ok = false;
			if( $disallow_lines!='' ){
				$disallow_explode = explode(" ",$disallow_lines);
				foreach($disallow_explode as $disallowed_number){
					if( $disallowed_number == ($i+1) ){
						$disallow_ok = true; //Tun off the flag
					}
				}
			}

			//Only when 0 is allowed
			if( $allow_zero && $i==0 ){
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( $i, $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo ($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_lg).'<br>'; //Escribir CSS
				$lg_output .= ($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_lg).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_lg_option).'">'.$i.' '.str_replace($a, $b, $wildcard_lg_option_label).'</option>'; //Almacenar option para select
			}
			$allow_zero = false;

			//Any other numbers
			if( (($i+1) % $step_by == 0 or $allow_ok) && !$disallow_ok ){
				$counter++;
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( ($i+1), $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo ($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_lg).'<br>'; //Escribir CSS
				$lg_output .= ($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_lg).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_lg_option).'">'.($i+1).' '.str_replace($a, $b, $wildcard_lg_option_label).'</option>'; //Almacenar option para select
			}
		}
$lg_output .= PHP_EOL;
		echo '
</pre>';
		echo '<br><select><option value=""></option>'.$select_option.'</select>'; //Escribir lista select
		$select_option='';
		echo '<br><br>';





		//MD
		echo '<hr>';
		echo '<h3>MD</h3>';
		//.fs2px-md{ font-size: 2px !important; }
		echo '<pre class="prettyprint">';
		$md_output .= '/* MD Tablet, ipad portrait*/
/*@media only screen and (max-width : 992px){*/
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) { 
';
echo $md_output;
		$counter=0;
		$select_option='';
		for ($i=0; $i < $limit; $i++) { 

			//Allow or disallow numbers			
			//Allow
			$allow_ok = false;
			if( $allow_lines!='' ){
				$allow_explode = explode(" ",$allow_lines);
				foreach($allow_explode as $allowed_number){
					if( $allowed_number == ($i+1) ){
						$allow_ok = true; //Tun on the flag
					}
					if( $allowed_number==0 ){
						$allow_zero=true;
					}
				}
			}
			//Disallow
			$disallow_ok = false;
			if( $disallow_lines!='' ){
				$disallow_explode = explode(" ",$disallow_lines);
				foreach($disallow_explode as $disallowed_number){
					if( $disallowed_number == ($i+1) ){
						$disallow_ok = true; //Tun off the flag
					}
				}
			}

			//Only when 0 is allowed
			if( $allow_zero && $i==0 ){
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( $i, $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_md).'<br>'; //Escribir CSS
				$md_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_md).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_md_option).'">'.$i.' '.str_replace($a, $b, $wildcard_md_option_label).'</option>'; //Almacenar option para select
			}
			$allow_zero = false;
			
			//Any other numbers
			if( (($i+1) % $step_by == 0 or $allow_ok) && !$disallow_ok ){
				$counter++;
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( ($i+1), $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_md).'<br>'; //Escribir CSS
				$md_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_md).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_md_option).'">'.($i+1).' '.str_replace($a, $b, $wildcard_md_option_label).'</option>'; //Almacenar option para select
			}
		}
$md_output .= '}'.PHP_EOL.PHP_EOL;
		echo '
}</pre>';
		echo '<br><select><option value=""></option>'.$select_option.'</select>'; //Escribir lista select
		$select_option='';
		echo '<br><br>';





		//SM
		echo '<hr>';
		echo '<h3>SM</h3>';
		//.fs2px-sm{ font-size: 2px !important; }
		echo '<pre class="prettyprint">';
		$sm_output .= '/* SM Tablet, ipad landscape*/ 
/*@media only screen and (max-width : 768px){*/
@media only screen and (min-width: 768px) and (max-width: 959px) {
';
echo $sm_output;
		$counter=0;
		$select_option='';
		for ($i=0; $i < $limit; $i++) { 

			//Allow or disallow numbers			
			//Allow
			$allow_ok = false;
			if( $allow_lines!='' ){
				$allow_explode = explode(" ",$allow_lines);
				foreach($allow_explode as $allowed_number){
					if( $allowed_number == ($i+1) ){
						$allow_ok = true; //Tun on the flag
					}
					if( $allowed_number==0 ){
						$allow_zero=true;
					}
				}
			}
			//Disallow
			$disallow_ok = false;
			if( $disallow_lines!='' ){
				$disallow_explode = explode(" ",$disallow_lines);
				foreach($disallow_explode as $disallowed_number){
					if( $disallowed_number == ($i+1) ){
						$disallow_ok = true; //Tun off the flag
					}
				}
			}

			//Only when 0 is allowed
			if( $allow_zero && $i==0 ){
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( $i, $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_sm).'<br>'; //Escribir CSS
				$sm_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_sm).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_sm_option).'">'.$i.' '.str_replace($a, $b, $wildcard_sm_option_label).'</option>'; //Almacenar option para select
			}
			$allow_zero = false;
			
			//Any other numbers
			if( (($i+1) % $step_by == 0 or $allow_ok) && !$disallow_ok ){
				$counter++;
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( ($i+1), $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_sm).'<br>'; //Escribir CSS
				$sm_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_sm).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_sm_option).'">'.($i+1).' '.str_replace($a, $b, $wildcard_sm_option_label).'</option>'; //Almacenar option para select
			}
		}
$sm_output .= '}'.PHP_EOL.PHP_EOL;
		echo '
}</pre>';
		echo '<br><select><option value=""></option>'.$select_option.'</select>'; //Escribir lista select
		$select_option='';
		echo '<br><br>';





		//XS
		echo '<hr>';
		echo '<h3>XS</h3>';
		//.fs2px-xs{ font-size: 2px !important; }
		echo '<pre class="prettyprint">';
		$xs_output .= '/* XS Mobile portrait*/
/*@media only screen and (max-width : 480px){*/
@media only screen and (max-width: 767px) {
';
echo $xs_output;
		$counter=0;
		$select_option='';
		for ($i=0; $i < $limit; $i++) { 

			//Allow or disallow numbers			
			//Allow
			$allow_ok = false;
			if( $allow_lines!='' ){
				$allow_explode = explode(" ",$allow_lines);
				foreach($allow_explode as $allowed_number){
					if( $allowed_number == ($i+1) ){
						$allow_ok = true; //Tun on the flag
					}
					if( $allowed_number==0 ){
						$allow_zero=true;
					}
				}
			}
			//Disallow
			$disallow_ok = false;
			if( $disallow_lines!='' ){
				$disallow_explode = explode(" ",$disallow_lines);
				foreach($disallow_explode as $disallowed_number){
					if( $disallowed_number == ($i+1) ){
						$disallow_ok = true; //Tun off the flag
					}
				}
			}

			//Only when 0 is allowed
			if( $allow_zero && $i==0 ){
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( $i, $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xs).'<br>'; //Escribir CSS
				$xs_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xs).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_xs_option).'">'.$i.' '.str_replace($a, $b, $wildcard_xs_option_label).'</option>'; //Almacenar option para select
			}
			$allow_zero = false;
			
			//Any other numbers
			if( (($i+1) % $step_by == 0 or $allow_ok) && !$disallow_ok ){
				$counter++;
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( ($i+1), $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xs).'<br>'; //Escribir CSS
				$xs_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xs).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_xs_option).'">'.($i+1).' '.str_replace($a, $b, $wildcard_xs_option_label).'</option>'; //Almacenar option para select
			}
		}
$xs_output .= '}'.PHP_EOL.PHP_EOL;
		echo '
}</pre>';
		echo '<br><select><option value=""></option>'.$select_option.'</select>'; //Escribir lista select
		$select_option='';
		echo '<br><br>';





		//XSL
		echo '<hr>';
		echo '<h3>XSL</h3>';
		//.fs2px-xsl{ font-size: 2px !important; }
		echo '<pre class="prettyprint">';
		$xsl_output .= '/* XSL Mobile landscape*/
@media only screen and (min-width: 480px) and (max-width: 767px) {
';
echo $xsl_output;
		$counter=0;
		$select_option='';
		for ($i=0; $i < $limit; $i++) { 

			//Allow or disallow numbers			
			//Allow
			$allow_ok = false;
			if( $allow_lines!='' ){
				$allow_explode = explode(" ",$allow_lines);
				foreach($allow_explode as $allowed_number){
					if( $allowed_number == ($i+1) ){
						$allow_ok = true; //Tun on the flag
					}
					if( $allowed_number==0 ){
						$allow_zero=true;
					}
				}
			}
			//Disallow
			$disallow_ok = false;
			if( $disallow_lines!='' ){
				$disallow_explode = explode(" ",$disallow_lines);
				foreach($disallow_explode as $disallowed_number){
					if( $disallowed_number == ($i+1) ){
						$disallow_ok = true; //Tun off the flag
					}
				}
			}

			//Only when 0 is allowed
			if( $allow_zero && $i==0 ){
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( $i, $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xsl).'<br>'; //Escribir CSS
				$xsl_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xsl).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_xsl_option).'">'.$i.' '.str_replace($a, $b, $wildcard_xsl_option_label).'</option>'; //Almacenar option para select
			}
			$allow_zero = false;
			
			//Any other numbers
			if( (($i+1) % $step_by == 0 or $allow_ok) && !$disallow_ok ){
				$counter++;
				//Wildcards
				$a = array('$','{s}','{u}','{classname}','{property}');
				$b = array( ($i+1), $string_in_classname, $unit_for_value, $classname, $property_name);
				//Concatenate
				echo chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xsl).'<br>'; //Escribir CSS
				$xsl_output .= chr(9).($show_line_numbers?$counter:''). str_replace($a, $b, $wildcard_xsl).PHP_EOL; //Escribir CSS en variable
				$select_option .= '<option value="'.str_replace($a, $b, $wildcard_xsl_option).'">'.($i+1).' '.str_replace($a, $b, $wildcard_xsl_option_label).'</option>'; //Almacenar option para select
			}
		}
$xsl_output .= '}'.PHP_EOL;
		echo '
}</pre>';
		echo '<br><select><option value=""></option>'.$select_option.'</select>'; //Escribir lista select
		$select_option='';
		echo '<br><br>';
		?>			
		</div>    
	</div>

</body>
</html>



<?php 
if( isset($_GET['filename']) && @$_GET['filename']!='' ){
    $cadenaInside = $lg_output . $md_output . $sm_output . $xs_output . $xsl_output;
	$cadenaInside = trim($cadenaInside, "[\n|\r|\n\r]"); //Quitar el primer enter del documento php
	$theFile = fopen('output/'.$_GET['filename'], "w+");
	fwrite($theFile, $cadenaInside);
	fclose($theFile);
} 
?>