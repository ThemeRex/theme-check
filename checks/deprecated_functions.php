<?php
class DeprecatedFunctions implements themecheck {

	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		$ret = true;

		$deprecated_functions = array(
			'extract',
			'eval',
			'var_dump',
			'print_r',
		);

		foreach ( $php_files as $php_key => $phpfile ) {

			foreach ( $deprecated_functions as $function ) {
				checkcount();
				if ( false !== preg_match_all( '/' . $function . '\s?\(/', $phpfile, $matches ) ) {

					foreach ($matches[0] as $match ) {
						$error = ltrim($match, '(');
						$error = rtrim($error, '(');
						$grep = tc_grep($error, $php_key);
						$this->error[] = sprintf(
							'<span class="tc-lead tc-warning">' . __('WARNING', 'theme-check') . '</span>: ' . __('Function %1$s found %2$s. %1$s not allowed to use.%3$s', 'theme-check'),
							'<strong>' . $function . '</strong>',
							'<strong>' . $php_key . '</strong>',
							$grep
						);
					}
				}
			}
		}
		return $ret;
	}

	function getError() { return $this->error; }
}

$themechecks[] = new DeprecatedFunctions;
