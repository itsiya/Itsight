<?php
function generate_xml_from_array($array) {
	$xml = '';

	if (is_array($array) || is_object($array)) {
		foreach ($array as $key=>$value) {
			if ($key == 'root_name') continue;
			$xml .= '<' . $key . '>' . "\n" . generate_xml_from_array($value) . '</' . $key . '>' . "\n";
		}
	} else {
		$xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
	}

	return $xml;
}

function generate_valid_xml_from_array($array, $node_block='nodes') {
	$xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";

	$xml .= '<' . $node_block . '>' . "\n";
	$xml .= generate_xml_from_array($array);
	$xml .= '</' . $node_block . '>' . "\n";

	return $xml;
}
?>
<? echo generate_valid_xml_from_array($this->data, $root_name == null ? 'nodes': $root_name) ?>