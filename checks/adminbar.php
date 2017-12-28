<?php
/**
 * This checks, if the admin bar gets hidden by the theme
 **/
class NoHiddenAdminBar implements themecheck {
	protected $error = array();

		function check( $php_files, $css_files, $other_files ) {
			$ret = true;
			checkcount();
			$php_regex = "/(add_filter(\s*)\((\s*)(\"|')show_admin_bar(\"|')(\s*)(.*))|(([^\S])show_admin_bar(\s*)\((.*))/";
			$css_regex = "/#wpadminbar/";

			//Check php files for filter show_admin_bar and show_admin_bar()
			foreach ( $php_files as $file_path => $file_content ) {

				if ( preg_match( $php_regex, $file_content, $matches ) ) {
					foreach ($matches as $match ) {
						$error = ltrim( $match, '(' );
						$error = rtrim( $error, '(' );
						$grep = tc_grep( $error, $file_path );
						$this->error[] = sprintf('<span class="tc-lead tc-warning">'.__('WARNING','theme-check').'</span>: '.__('%1$s was found in the file %2$s %3$s.%4$s', 'theme-check'),
							'<strong>' . $error. '</strong>',
							'<strong>' . $file_path . '</strong>',
							$php_regex,
							$grep );
						$ret = false;
					}

					$this->error[] = sprintf( '<span class="tc-lead tc-required">' . __( 'REQUIRED', 'theme-check').'</span>: ' . __( 'You are not allowed to hide the admin bar.', 'theme-check' ), 
						'<strong>' . $file_path . '</strong>');
					$ret = false;			
				}
			}

			//Check CSS Files for #wpadminbar
			foreach ( $css_files as $file_path => $file_content ) {
				if ( preg_match( $css_regex, $file_content, $matches ) ) {
					foreach ($matches as $match ) {
						$error = ltrim( $match, '(' );
						$error = rtrim( $error, '(' );
						$grep = tc_grep( $error, $file_path );
						$this->error[] = sprintf('<span class="tc-lead tc-warning">'.__('WARNING','theme-check').'</span>: '.__('The theme is using `#wpadminbar` in %1$s. Hiding the admin bar is not allowed.%2$s', 'theme-check'),
							'<strong>' . $file_path . '</strong>',
							$grep );
					}
				}
			}
		return $ret;
	}

	function getError() { return $this->error; }
}
$themechecks[] = new NoHiddenAdminBar;
