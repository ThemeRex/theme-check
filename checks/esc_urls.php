<?php
class EscUrls implements themecheck {

	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		global $data, $themename;

		$ret = true;

		$checks = array(
			'/[\=\,\.\>][\s]?(home_url|admin_url)/' => __( 'Not escaped home_url() or admin_url()', 'theme-check' ),
		);

		foreach ( $php_files as $php_key => $phpfile ) {

			if ( false !== strpos( $php_key, 'class-tgm-plugin-activation' ) ) {
				continue;
			}

			foreach ( $checks as $key => $check ) {
				checkcount();
				if ( preg_match_all( $key, $phpfile, $matches ) ) {

					foreach ($matches[1] as $match ) {
						$error = ltrim( $match, '(' );
						$error = rtrim( $error, '(' );
						$grep = tc_grep( $error, $php_key );
						$this->error[] = sprintf(
							'<span class="tc-lead tc-warning">' . __( 'WARNING','theme-check' ) . '</span>: ' . __( '%2$s in %1$s.%3$s', 'theme-check' ),
							'<strong>' . $php_key . '</strong>',
							$check,
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

$themechecks[] = new EscUrls;
