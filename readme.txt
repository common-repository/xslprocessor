=== Plugin Name ===
Contributors: Chryzo, hakre
Donate link: http://www.wordpress.org/
Tags: xml, xslt, page, shortcode
Requires at least: 2.0.2
Tested up to: 2.7.1
Stable tag: trunk

The plugin aims to allow XML document transformed through XSLT to be outputted in a WordPress Page. It also now allows to pass parameters to the XSL doc, either by a key / value pair, or a key from the get or post methods.

== Description ==

The plugin aims to allow XML document transformed through parametrized XSLT to be outputted in a WordPress Page.

Lots of small (or consolidated) plugins like this one could be usefull to implement inclusion of webservices output, html page and such. But this one is limited at the moment to XML and XSLT.


== Installation ==

1. Upload the CPT_XslProcessor folder to the '/wp-content/plugins/' directory. It should include 'processor.php', 'example.xml', 'example.xsl'
1. Activate the plugin through the 'Plugins' menu in WordPress

usage:

	[XmlProcessor xml="xml_filepath" xslt="xslt_filepath"]

		xml_filepath and xslt_filepath is relative to wordpress root directory

	[XmlProcessor filepath]

		filepath stands for both filenames (.xml and .xsl) without the file extension and relative to wordpress root directory

	[XmlProcessor filepath params="key|key=value|ns=key=value"]
	
	[XmlProcessor xml="filepath.xml" xslt="filepath.xsl" params="key|key=value|ns=key=value"]

		params stands for the parameters that need to be passed to the xsl document. 
		* If only 1 value is present, it is considered a key and it replaces said key by a blank.
		* If 2 values are present, it is considered a key / value pair, and the key is replaced by the value.
		* If 3 values are present, it is considered a ns / key / value set, and replaces accordingly
		You may have more than one parameter set. In this case, each set needs to be separated by a |

	[XmlProcessor filepath get="key|key=value|ns=key=value"]

	[XmlProcessor filepath post="key|key=value|ns=key=value"]

	[XmlProcessor filepath get="key|key=value|ns=key=value" post="key|key=value|ns=key=value"]

		get / post stands for the parameters that need to be passed to the xsl document but are taken from the HTML methods
		* If only one value is present, it is considered a key and it replaces said key with the value in the get or post variables
		* If two values are present, it is considered a key / value pair. key represents the XSL parameter, value, the value to retrieve from the $_GET or $_POST variables
		* If three values are present, it is considered a ns / key / value set, and replaces accordingly
		You may have more than one parameter set. In this case, each set needs to be separated by a |


examples without parameters:

	[XmlProcessor wp-content/plugins/CPT_XslProcessor/example]

	[XmlProcessor xml="wp-content/plugins/CPT_XslProcessor/example.xml" xslt="wp-content/plugins/CPT_XslProcessor/example.xsl"]

		Both Examples work the same, they will process example.xml and example.xsl located in the wordpress plugin directory.	

examples with parameters:

	[XmlProcessor wp-content/plugins/CPT_XslProcessor/example params="default_param=this is the replaced default parameter"]

	[XmlProcessor xml="wp-content/plugins/CPT_XslProcessor/example.xml" xslt="wp-content/plugins/CPT_XslProcessor/example.xsl" params="default_param=this is the replaced default parameter"]

		Both Examples work the same, they will process example.xml and example.xsl located in the wordpress plugin directory and also 	replace the parameters in the xsl document


== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

== Arbitrary section ==

