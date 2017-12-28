<?php
class ScriptStyleTags implements themecheck {

	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		$ret = true;

		$checks = array(
			'/(\<script(.|\n)*?\<\/script\>)|(\<style(.|\n)*?\<\/style\>)/' => __( 'Do not use <b>script</b> and <b>style</b> tags in template files', 'theme-check' )
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
						$grep = tc_grep_multiline( $error, $php_key );

						$this->error[] = sprintf(
							'<span class="tc-lead tc-warning">' . __( 'WARNING','theme-check' ) . '</span>: ' . __( 'Script or Style tags was found in the file %1$s.%2$s', 'theme-check' ),
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

$themechecks[] = new ScriptStyleTags;
