<?php
namespace Template;
final class Twig {
	private $data = array();
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($filename, $code = '') {
		if (!$code) {
			$file = DIR_TEMPLATE . $filename . '.twig';

			if (is_file($file)) {
				$code = file_get_contents($file);
			} else {
				throw new \Exception('Error: Could not load template ' . $file . '!');
				exit();
			}
		}

		// initialize Twig environment
		$config = [
			'charset'     => 'utf-8',
			'autoescape'  => false,
			'debug'       => false,
			'auto_reload' => true,
			'cache'       => DIR_CACHE . 'template/'
		];
		
		try {
			$refixed = new \Twig_Loader_Array(array($filename . '.twig' => $code));
            		$template = new \Twig_Loader_Filesystem(array(DIR_TEMPLATE));
            		$loader = new \Twig_Loader_Chain(array($refixed, $template));

			$twig = new \Twig\Environment($loader, $config);
			
			return $twig->render($filename . '.twig', $this->data);
		} catch (Exception $e) {
			trigger_error('Error: Could not load template ' . $filename . '!');
			exit();	
		}	
	}
}
