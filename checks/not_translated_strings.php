<?php
class NotTranslatedStrings implements themecheck {

	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		global $data, $themename;

		$theme_data = wp_get_theme( $themename );
		$tex_domain = $theme_data->get( 'TextDomain' );

		$ret = true;

		if ( ! $tex_domain ) {
			return $ret;
		}

		$prefix = str_replace( '-', '_', strtolower( $tex_domain ) );

		$checks = array(
			'/[\s][o=>][\s]?[\'\"](?!btn|SELECT|' . $prefix . '|basekit)\w+\s/' => __( 'Not translated string', 'theme-check' )
		);

		foreach ( $php_files as $php_key => $phpfile ) {

			if ( false !== strpos( $php_key, 'class-tgm-plugin-activation' ) ) {
				continue;
			}

			foreach ( $checks as $key => $check ) {
				checkcount();
				if ( preg_match_all( $key, $phpfile, $matches ) ) {
					foreach ($matches[0] as $match ) {
						$error = ltrim( $match, '(' );
						$error = rtrim( $error, '(' );
						$grep = tc_grep( $error, $php_key );
						$this->error[] = sprintf(
							'<span class="tc-lead tc-warning">' . __( 'WARNING','theme-check' ) . '</span>: ' . __( 'Potentialy not translated string %2$s was found in the file %1$s.%3$s', 'theme-check' ),
							'<strong>' . $php_key . '</strong>',
							$check,
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

$themechecks[] = new NotTranslatedStrings;
