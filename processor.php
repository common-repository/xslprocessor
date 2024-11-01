<?php
/*
Plugin Name: Xslt Processor
Plugin URI: no uri at the moment
Description: The plugin aims to allow XML document transformed through XSLT to be outputted in a WordPress Page. It also now allows to pass parameters to the XSL doc
Version: 0.5
Author: Chryzo, Hakre
Author URI: http://www.chryzo.net

Copyright 2009  Chryzo  (email : ChryzoPhylax@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
usage:

	[XmlProcessor xml="xml_filepath" xslt="xslt_filepath"]
	
		xml_filepath and xslt_filepath is relative to wordpress root directory

	[XmlProcessor filepath]
	
		filepath stands for both filenames (.xml and .xsl) without the
		file extension and relative to wordpress root directory

	[XmlProcessor filepath get="key|ns=key" post="key|ns=key"]

		get / post stands for the parameters that need to be passed to the xsl document but are taken from the HTML methods

examples without parameters:

	[XmlProcessor wp-content/plugins/CPT_XslProcessor/example]
	
	[XmlProcessor xml="wp-content/plugins/CPT_XslProcessor/example.xml" xslt="wp-content/plugins/CPT_XslProcessor/example.xsl"]
	
		Both Examples work the same, they will process example.xml and
		example.xsl located in the wordpress root directory.

examples with parameters:

	[XmlProcessor wp-content/plugins/CPT_XslProcessor/example]
	
	[XmlProcessor xml="wp-content/plugins/CPT_XslProcessor/example.xml" xslt="wp-content/plugins/CPT_XslProcessor/example.xsl"]
	
		Both Examples work the same, they will process example.xml and
		example.xsl located in the wordpress root directory.	
		

*/

add_option("CPT_Path", "");
add_option("CPT_Separator", ",");

/**
 * XmlProcessor Option retrieval
 *
 *	XML - XSLT Processor
 *
 *
 * @return array list of path to look inside to retrieve files
 */
function cpt_retrieve_option() {
	$paths = get_option("CPT_Path");
	$separator = get_option("CPT_Separator");
}
 

/**
 * XmlProcessor Shortcode Hook
 * 
 * XML - XSLT Processor
 * 
 * @param  array $atts shortcode attributes
 * @return string output of shortcode(processed xml/xslt or error message on failure)
 */
function cpt_process_xml($atts) {
	
	$options = null;
	$get =null;
	$post = null;
	$namespace = null;
	$xml = false;
	$xslt = false;
	$paramSep = "|";
	$keyvalSep = "=";
	
	if (isset($atts[0])) {						// one parameter for naming both files
		$xml  = sprintf('%s.xml', $atts[0]);
		$xslt = sprintf('%s.xsl', $atts[0]);
	} elseif (isset($atts["xml"]) && isset($atts["xslt"])) {	// name xml and xslt files with an attribute on it's own.
		$xml = $atts["xml"];
		$xslt = $atts["xslt"];
	}
	
	// This is a separate section because we can't use the short format of the filepath in this case since the parameters may have
	// some blanks in them which would screw around with the parsing
	if (isset($atts["params"])){				// parameter for the parameters of the xsl file
		$options = $atts["params"];
	}
	if (isset($atts["get"])){
		$get = $atts["get"];
	}
	
	if (isset($atts["post"])){
		$get = $atts["post"];
	}
		
	if ($xml !== false)
	{	
		// this will include relative to this file
		//$path = dirname(__FILE__);
		// this will include relative to this wordpress root folder
		$path = ABSPATH;
		
		$xml  = $path . $xml;
		$xslt = $path . $xslt;
			
		if (file_exists($xml) && file_exists($xslt))
		{
			$xslDoc = new DOMDocument();
			$xslDoc->load($xslt);
			
			$xmlDoc = new DOMDocument();
			$xmlDoc->load($xml);
	
			$proc = new XSLTProcessor();
			// setting parameters section
			// /*
			if ($options != null) {
				$options = explode($paramSep,$options);
				//print_r(count($options));
				for ($i = 0; $i< count($options); $i++){
					$pair = explode($keyvalSep, $options[$i]);
					if ( count($pair) == 1 ) {
						$proc->setParameter(null, $pair[0], "");			// just a key, then we blank the value of the parameter
					} elseif ( count($pair) == 2 ) {
						//print_r($pair);
						//$proc->setParameter(null, 'default_param', 'test');		// just a key/value pair, we replace
						$proc->setParameter(null, $pair[0], $pair[1]);		// just a key/value pair, we replace
					} elseif (count($pair) == 3 ) {
						$proc->setParameter($pair[0], $pair[1], $pair[2]);	// a namespace/key/value set, we replace
					}
				}
			}
			if ($get != null) {
				$get = explode($paramSep,$get);
				for ($i = 0; $i< count($get); $i++){
					$pair = explode($keyvalSep, $get[$i]);
					if ( count($pair) == 1 ) {
						$proc->setParameter(null, $pair[0], $_GET[$pair[0]]);	// just a key, then we blank the value of the parameter
					} elseif ( count($pair) == 2 ) {
						$proc->setParameter(null, $pair[0], $_GET[$pair[1]]);	// just a key/value pair, we replace
					} elseif (count($pair) == 3 ) {
						$proc->setParameter($pair[0], $pair[1], $_GET[$pair[2]]);// a namespace/key/value set, we replace
					}
				}
			}
			if ($post != null) {
				$post = explode($paramSep,$post);
				for ($i = 0; $i< count($post); $i++){
					$pair = explode($keyvalSep, $post[$i]);
					if ( count($pair) == 1 ) {
						$proc->setParameter(null, $pair[0], $_POST[$pair[0]]);	// just a key, then we blank the value of the parameter
					} elseif ( count($pair) == 2 ) {
						$proc->setParameter(null, $pair[0], $_POST[$pair[1]]);	// just a key/value pair, we replace
					} elseif (count($pair) == 3 ) {
						$proc->setParameter($pair[0], $pair[1], $_POST[$pair[2]]);// a namespace/key/value set, we replace
					}
				}
			}
			// */
			// end of parameter section
			$proc->importStylesheet($xslDoc);			
			return $proc->transformToXML($xmlDoc);		
		}
	}
	
	return sprintf('<div class="error"><strong>Error processing XML and XSLT. Configuration was: %s</strong></div>', htmlspecialchars(print_r($atts, true)));	
}

if (function_exists("add_shortcode")){
	add_shortcode("XmlProcessor", "cpt_process_xml");
}

?>