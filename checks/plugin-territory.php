<?php
/**
 * Checks for Plugin Territory Guidelines.
 *
 * See http://make.wordpress.org/themes/guidelines/guidelines-plugin-territory/
 */

class Plugin_Territory implements themecheck {
	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {
		$ret = true;

		// Functions that are required to be removed from the theme.
		$forbidden_functions = array(
			'register_post_type',
			'register_taxonomy',
		);

		foreach ( $php_files as $php_key => $phpfile ) {
			foreach ( $forbidden_functions as $function ) {
				checkcount();
				if ( preg_match('/[\s?]' . $function . '\s?\(/', $phpfile, $matches) ) {
					$error = ltrim( rtrim( $matches[0], '(' ) );
					$grep = tc_grep( $error, $php_key );
					$this->error[] = '<span class="tc-lead tc-required">' . __( 'REQUIRED', 'theme-check').'</span>: ' . sprintf( __( 'The theme uses the %1$s function in %2$s, which is plugin-territory functionality.%3$s', 'theme-check' ),
							'<strong>' . esc_html( $function ) . '()</strong>',
							'<strong>' . $php_key . '</strong>',
							$grep
						);
					$ret = false;
				}
			}

			// Shortcodes can't be used in the post content, so warn about them.
			if ( preg_match( '/\s?add_shortcode\s?\(/', $phpfile, $matches ) ) {
				checkcount();
				$error = ltrim( rtrim( $matches[0], '(' ) );
				$grep = tc_grep( $error, $php_key );
				$this->error[] = '<span class="tc-lead tc-warning">' . __( 'WARNING', 'theme-check').'</span>: ' . sprintf( __( 'The theme uses the %1$s function in %2$s. Custom post-content shortcodes are plugin-territory functionality.%3$s', 'theme-check' ),
						'<strong>add_shortcode()</strong>',
						'<strong>' . $php_key . '</strong>',
						$grep
					) ;
				$ret = false;
			}
		}


		return $ret;
	}

	function getError() { return $this->error; }
}
$themechecks[] = new Plugin_Territory;
