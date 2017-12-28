<?php
class BlankPrefixes implements themecheck {

	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		$ret = true;

		global $themename;

		if ( 'basekit' === $themename ) {
			return $ret;
		}

		$deprected_prefixes = array(
			'basekit',
		);

		foreach ( $php_files as $php_key => $phpfile ) {

			foreach ( $deprected_prefixes as $prefix ) {
				checkcount();

				if ( false !== preg_match_all( '/' . $prefix . '/', $phpfile, $matches ) ) {
					foreach ($matches[0] as $match ) {
						$error = ltrim( $match, '(' );
						$error = rtrim( $error, '(' );
						$grep = tc_grep( $error, $php_key );
						$this->error[] = sprintf(
							'<span class="tc-lead tc-warning">' . __( 'WARNING','theme-check' ) . '</span>: ' . __( 'Prefix %1$s was found in the file %2$s.%3$s', 'theme-check' ),
							'<strong>' . $match . '</strong>',
							'<strong>' . $php_key . '</strong>',
							$grep
						);
						$ret = false;
					}


				}
			}
		}
		return $ret;
	}

	function getError() { return $this->error; }
}

$themechecks[] = new BlankPrefixes;
