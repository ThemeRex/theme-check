<?php
class GetTextFunctions implements themecheck {

	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		$ret = true;

		// [\s\w]*(?!wp_kses_data)\W\s*_(_|e)\(\s*('|\")[\w\s]+
		$checks = array(
			'/(wp_kses_data)*\W\s*_(_|e)\(\s*(\'|\")[\w\s]+/' => __( 'Use only <strong>esc_html__</strong> or <strong>esc_html_e</strong> translation function', 'theme-check' )
			);

		foreach ( $php_files as $php_key => $phpfile ) {

			if ( false !== strpos( $php_key, 'class-tgm-plugin-activation' ) ) {
				continue;
			}

			foreach ( $checks as $key => $check ) {
				checkcount();
				if ( preg_match_all( $key, $phpfile, $matches ) ) {
					$this->add_errors( $matches, $check, $php_key );
				}
			}
		}
		return $ret;
	}

	/**
	 * Adds all breadcrums errors
	 */
	function add_errors( $matches, $check, $php_key ) {

		if ( empty( $matches[0] ) ) {
			return;
		}

		foreach ( $matches[0] as $match ) {
			if (strpos($match, 'wp_kses_data') !== false) {
				continue;
			}
			$error = $match;
			$grep = tc_grep( $error, $php_key );
			$this->error[] = sprintf('<span class="tc-lead tc-warning">'.__('WARNING','theme-check').'</span>: '.__('%1$s was found in the file %2$s %3$s.%4$s', 'theme-check'), '<strong>' . $error . '</strong>', '<strong>' . $php_key . '</strong>', $check, $grep ) ;
		}

	}

	function getError() { return $this->error; }
}

$themechecks[] = new GetTextFunctions;
