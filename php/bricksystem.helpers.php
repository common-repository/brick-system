<?php

class brick_system_helpers {
	public static function randomizer ( $length ) {
		$chars = 'abcedfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRTSUVWXYZ0123456789';
		$string = '';
		mt_srand((double) microtime() * 1000000 );
		for ( $i = 0; $i < $length; ++$i ) {
			$string .= $chars{ mt_rand ( 0, strlen( $chars ) ) };
		}
		return $string;
	}
}