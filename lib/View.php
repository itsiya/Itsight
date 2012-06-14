<?php
class ItsightView {
	
	protected $data = array();
	
	protected $templatesDirectory;

	public function __construct() {}

    public function setData() {
        $args = func_get_args();
		$args = $args[0];
		//print_r($args);
        if ( count($args) === 1 && is_array($args[0]) ) {
            $this->data = $args[0];
        } else if ( count($args) === 2 ) {
            $this->data[(string)$args[0]] = $args[1];
        } else {
            throw new InvalidArgumentException('Cannot set View data with provided arguments. Usage: `View::setData( $key, $value );` or `View::setData([ key => value, ... ]);`');
        }
    }

	public function getData() {
		return $this->data;
	}

	public function appendData( array $data ) {
        $this->data = array_merge($this->data, $data);
    }

	public function getTemplatesDirectory() {
        return $this->templatesDirectory;
    }

    public function setTemplatesDirectory( $dir ) {
        $this->templatesDirectory = rtrim($dir, '/');
    }

    public function display( $template ) {
        echo $this->render($template);
    }

	public function render( $template ) {
        extract($this->data);
        $templatePath = $this->getTemplatesDirectory() . '/' . ltrim($template, '/');
		if (is_dir($templatePath)) {
			throw new RuntimeException('View cannot render template `' . $templatePath . '`. Template does not exist.');
		}
        if ( !file_exists($templatePath) ) {
            throw new RuntimeException('View cannot render template `' . $templatePath . '`. Template does not exist.');
        }
        ob_start();
        require $templatePath;
        return ob_get_clean();
    }
}
?>