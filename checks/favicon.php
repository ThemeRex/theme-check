<?php

class FaviconCheck implements themecheck {
	protected $error = array();

		function check( $php_files, $css_files, $other_files ) {

		$ret = true;
		
		checkcount();

		foreach ( $php_files as $file_path => $file_content ) {

			if ( preg_match( '/(<link rel=[\'"]icon[\'"])|(<link rel=[\'"]apple-touch-icon-precomposed[\'"])|(<meta name=[\'"]msapplication-TileImage[\'"])/', $file_content, $matches ) ) {
				$error = ltrim( rtrim( $matches[0], '(' ) );
				$grep = tc_grep( $error, $file_path );
				$this->error[] = sprintf( '<span class="tc-lead tc-info">' . __('INFO','theme-check') . '</span>: ' . __( 'Possible Favicon found in %1$s. Favicons are handled by the Site Icon setting in the customizer since version 4.3.%2$s', 'theme-check' ),
					'<strong>' . $file_path . '</strong>',
					$grep
				);
			}
		}
		return $ret;
	}

	function getError() { return $this->error; }
}
$themechecks[] = new FaviconCheck;