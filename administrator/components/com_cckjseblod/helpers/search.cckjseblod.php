<?php
/**
* @version 			1.8.5
* @author       	http://www.seblod.com
* @copyright		Copyright (C) 2011 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
* @package			SEBLOD 1.x (CCK for Joomla!)
**/

// No Direct Access
defined( '_JEXEC' ) or die( 'Restricted access' );

//todo class !!

function generateRange($min, $max)
{
    $digit = '[0-9]';
    $lenMax = strlen($max);
    $lenMin = strlen($min);
    $lenDiff = $lenMax - $lenMin;
    $min = str_pad($min, $lenMax, 0, STR_PAD_LEFT);
    $max = (string)$max;
	
    // find length of common prefix
    for ($i = 0; $i < $lenMin && $min[$i] == $max[$i]; $i++);
    $prefixLength = $i;
    // add non-conflicting ranges from each end
    for ($i = $lenMax, $j = 0; $i-- > 1 + $prefixLength; $j++) {
        $lower = $min[$i];
        $upper = $max[$i];
        // correct bounds if not final range
        if ($j) {
            ++$lower;
            --$upper;
        }
        // lower bound
        if ($lower < 10) {
            $char = $lower == 9 ? 9 : '[' . $lower . '-9]';
            $pattern[] =
                ($j >= $lenMin ? '' : substr($min, $lenDiff, $i - $lenDiff))
                . $char . str_repeat($digit, $j);
        }
        // upper bound
        if ($upper >= 0) {
            $char = $upper ? '[0-' . $upper . ']' : 0;
            $pattern[] = substr($max, 0, $i) . $char . str_repeat($digit, $j);
        }
    }
    // add middle range
    if (!$j || $max[$prefixLength] - $min[$prefixLength] > 1) {
        $prefix = substr($min, 0, $prefixLength);
        $lower = @$min[$prefixLength];
        $upper = @$max[$prefixLength];
        // correct bounds if not final range
        if ($j && $i == $prefixLength) {
            ++$lower;
            --$upper;
        }
        $char = $lower == $upper ? $lower : '[' . $lower . '-' . $upper . ']';
        $pattern[] = $prefix . $char . @str_repeat($digit, $lenMax - $prefixLength - 1);
    }
 
    return join('|', $pattern);
}

/**
 * Get Query [GENERIC]
 **/
function getQueryMatchGeneric( $match, $name, $value, $helper, $helper2, $content, $userId, $alf = 'a' )
{
	$query			=	'';
	$excluded		=	0;
	$wheres3_cut	=	array();
	
	/*******************
	 ***** GENERIC *****
	 *******************/
	switch ( $match ) {
		// --------------
		//      TRUE
		// --------------
		case 'alpha':
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					$wheres3_cut[]	=	$alf.'.'.$content.' LIKE "'.$value.'%"';
				}
			}
			break;
		case 'all':
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					$wheres3_cut[]	=	$alf.'.'.$content.' LIKE "%'.$value.'%"';
				}
			}
			break;
		case 'any':
			$divider		=	( $helper ) ? $helper : ' ';
			$multiples		=	explode( $divider, $value );							
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					if ( sizeof( $multiples ) ) {
						$wheres3_cuts	=	array();
						foreach( $multiples as $multi ) {
							if ( strlen( $multi ) > $helper2 ) {
								$wheres3_cuts[] 	=	$alf.'.'.$content.' LIKE "%'.$multi.'%"';
							}
						}
						if ( count( $wheres3_cuts ) ) {
							$wheres3_cut[]	=	'((' . implode( ') OR (', $wheres3_cuts ) . '))';
						}
					}
				}
			}
			break;
		case 'any_exact':
			$divider		=	( $helper ) ? $helper : ' ';
			$multiples		=	explode( $divider, $value );							
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					if ( sizeof( $multiples ) ) {
						$wheres3_cuts	=	array();
						foreach( $multiples as $multi ) {
							if ( strlen( $multi ) > $helper2 ) {
								$wheres3_cuts[] 	=	$alf.'.'.$content.' = "'.$multi.'"';
							}
						}
						if ( count( $wheres3_cuts ) ) {
							$wheres3_cut[]	=	'((' . implode( ') OR (', $wheres3_cuts ) . '))';
						}
					}
				}
			}
			break;
		case 'each':
			$divider		=	( $helper ) ? $helper : ' ';
			$multiples		=	explode( $divider, $value );							
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					if ( sizeof( $multiples ) ) {
						$wheres3_cuts	=	array();
						foreach( $multiples as $multi ) {
							if ( strlen( $multi ) > $helper2 ) {
								$wheres3_cuts[] 	=	$alf.'.'.$content.' LIKE "%'.$multi.'%"';
							}
						}
						if ( count( $wheres3_cuts ) ) {
							$wheres3_cut[]	=	'(' . implode( ') AND (', $wheres3_cuts ) . ')';
						}
					}
				}
			}
			break;
		case 'exact':
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					if ( $content == 'parent_id' ) {
						$wheres3_cut[]	=	'b.'.$content.' = "'.$value.'"';
					} else {
						$wheres3_cut[]	=	$alf.'.'.$content.' = "'.$value.'"';
					}
				}
			}
			break;
		case 'user_any_exact':
			$query	=	$alf.'.'.$content.' IN ( '.implode( ',', $value ).' )';
			break;
		case 'num_lower':
			if ( $content == 'publish_up' || $content == 'publish_down' ) {
				$value	=	str_replace( '/', '-', $value );
				$value	=	date( 'Y-m-d', strtotime( $value ) );
				$value	.=	' 23:59:59';
			}
			$query		=	$alf.'.'.$content.' <= \''.$value.'\'';
			break;
		case 'num_higher':
			if ( $content == 'publish_up' || $content == 'publish_down' ) {
				$value	=	str_replace( '/', '-', $value );
				$value	=	date( 'Y-m-d', strtotime( $value ) );
				$value	.=	' 00:00:00';
			}
			$query		=	$alf.'.'.$content.' >= \''.$value.'\'';
			break;
		// --------------
		//      FALSE
		// --------------
		case 'exact_excluded':
			$excluded		=	1;
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					$wheres3_cut[]	=	$alf.'.'.$content.' != "'.$value.'"';
				}
			}
			break;
		case 'all_excluded':
			$excluded		=	1;
			$searchContent	=	explode( ',', $content );
			if ( sizeof( $searchContent ) ) {
				foreach( $searchContent as $content ) {
					$wheres3_cut[]	=	$alf.'.'.$content.' NOT LIKE "%'.$value.'%"';
				}
			}
			break;
		case 'none':
			break;
	}
	if ( $wheres3_cut ) {
		if ( $excluded ) {
			$query	=	'(' . implode( ') AND (', $wheres3_cut ) . ')';
		} else {
			$query	=	'(' . implode( ') OR (', $wheres3_cut ) . ')';
			$query	=	'(' . $query . ')';
		}
	}
	
	return $query;
}

/**
 * Get Query 
 **/
function getQueryMatch( $match, $name, $value, $helper, $helper2, $location, $BOF, $EOF, $extended )
{
	$query	=	'';
	
	/*******************
	 ***** COMMON ******
	*******************/
	switch ( $match ) {
		// --------------
		//      TRUE
		// --------------
		case 'alpha':
			$query 		=	$location.' REGEXP "'.$BOF.$value.'.*'.$EOF.'"';
			$query		=	'@@';
			break;
		case 'all':
			$query 		=	$location.' REGEXP "'.$BOF.'.*'.$value.'.*'.$EOF.'"';
			break;
		case 'any':
			$divider	=	( $helper ) ? $helper : ' ';
			$multiples	=	explode( $divider, $value );
			if ( sizeof( $multiples ) ) {
				$wheres2_cut	=	array();
				foreach( $multiples as $multi ) {
					if ( strlen( $multi ) > $helper2 ) {
						$wheres2_cut[]	=	$location.' REGEXP "'.$BOF.'.*'.$multi.'.*'.$EOF.'"';
					}
				}
				if ( count( $wheres2_cut ) ) {
					$query	=	'((' . implode( ') OR (', $wheres2_cut ) . '))';
				}
			}
			break;
		case 'any_exact':
			$divider	=	( $helper ) ? $helper : ' ';
			$multiples	=	explode( $divider, $value );
			
			if ( sizeof( $multiples ) ) {
				$wheres2_cut	=	array();
				foreach( $multiples as $multi ) {
					if ( strlen( $multi ) > $helper2 ) {
						$wheres2_cut[]	=	( !$BOF ) ? $location.' = "'.$BOF.$multi.$EOF.'"' : $location.' REGEXP "'.$BOF.$multi.$EOF.'"';
					}
				}
				if ( count( $wheres2_cut ) ) {
					$query	=	'((' . implode( ') OR (', $wheres2_cut ) . '))';
				}
			}
			break;
		case 'each':
			$divider	=	( $helper ) ? $helper : ' ';
			$multiples	=	explode( $divider, $value );
			if ( sizeof( $multiples ) ) {
				$wheres2_cut	=	array();
				foreach( $multiples as $multi ) {
					if ( strlen( $multi ) > $helper2 ) {
						$wheres2_cut[] 	=	$location.' REGEXP "'.$BOF.'.*'.$multi.'.*'.$EOF.'"';
					}
				}
				if ( count( $wheres2_cut ) ) {
					$query	= '((' . implode( ') AND (', $wheres2_cut ) . '))';
				}
			}
			break;
		case 'exact':
			$query 		=	( !$BOF ) ? $location.' = "'.$BOF.$value.$EOF.'"' : $location.' REGEXP "'.$BOF.$value.$EOF.'"';
			break;
		case 'any_exact_index':
			$divider		=	( $helper ) ? $helper : ' ';
			$where_in		=	'"' . str_replace( $divider, '","', $value ) . '"';
			$wheres2_cut	=	CCK_DB_ResultArray( 'SELECT DISTINCT(id) FROM #__jseblod_cck_extra_index_'.$name.' WHERE indexid IN ( '.$where_in. ')' );
			if ( $wheres2_cut ) {
				$query		=	'a.id IN ( '.implode( ',', $wheres2_cut ).' )';
			} else {
				$query		=	'a.id IN ( 0 )';
			}
			break;
		case 'exact_index':
			$wheres2_cut	=	CCK_DB_ResultArray( 'SELECT DISTINCT(id) FROM #__jseblod_cck_extra_index_'.$name.' WHERE indexid = "'.$value.'"' );
			if ( $wheres2_cut ) {
				$query		=	'a.id IN ( '.implode( ',', $wheres2_cut ).' )';
			} else {
				$query		=	'a.id IN ( 0 )';
			}
			break;
		case 'index_any_exact':
			$where_in		=	'"' . str_replace( ' ', '","', $value ) . '"';
			$wheres2_cut	=	CCK_DB_ResultArray( 'SELECT DISTINCT(id) FROM #__jseblod_cck_extra_index_'.$extended.' WHERE indexid IN ( '.$where_in. ')' );
			$query			=	'a.id IN ( '.implode( ',', $wheres2_cut ).' )';
			break;
		case 'num_lower':
			$range		=	'';
			$min		=	( $helper ) ? $helper : 0;
			if ( $value >= $min ) {
				$range	=	generateRange( $min, $value );
			}
			$range		=	'[[:<:]]('.$range.')[[:>:]]';
			$query 		=	$location.' REGEXP "'.$BOF.$range.$EOF.'"';
			break;
		case 'num_higher':
			$range		=	'';
			$max		=	( $helper ) ? $helper : 9999;
			if ( $value <= $max ) {
				$range	=	generateRange( $value, $max );
			}
			$range		=	'[[:<:]]('.$range.')[[:>:]]';
			$query 		=	$location.' REGEXP "'.$BOF.$range.$EOF.'"';
			break;
		// --------------
		//      FALSE
		// --------------
		case 'all_excluded':
			$query		=	$location.' NOT REGEXP "'.$BOF.'.*'.$value.'.*'.$EOF.'"';
			break;
		case 'exact_excluded':
			$query 		=	( !$BOF ) ? $location.' != "'.$BOF.$value.$EOF.'"' : $location.' NOT REGEXP "'.$BOF.$value.$EOF.'"';
			break;
		case 'none':
			break;
	}
	
	return $query;
}

/**
 * Get Target Location
 **/
function getTargetLocation( $name, $target )
{	
	$namelen		=	strlen( $name ) + 4;
	$bov			=	'(POSITION("::'.$name.'::" IN a.introtext)+'.$namelen.')';
	$eov			=	'(POSITION("::/'.$name.'::" IN a.introtext))';
	$lov			=	$eov.'-'.$bov;
	$target			=	explode( '~', $target );
	$target[0]		=	( $target[0] ) ? $target[0] : 0;
	$target[1]		=	( $target[1] ) ? $target[1] : 0;
	$location		=	'SUBSTRING(a.introtext, '.$bov.', '.$lov.')';
	$delimlen		=	'';
	
	if ( $target[0] ) {
		$ope	=	'';
		if ( preg_match('/^[-]?[0-9]+$/', $target[0]) ) {
			$bov		=	strpos( $target[0], '-' ) !== false ? '('.$eov.$target[0].')' : '('.$bov.'+'.$target[0].')';
			$lov		=	'('.$eov.'-'.$bov.')';
			$location	=	'SUBSTRING(a.introtext, '.$bov.', '.$lov.')';
		} else {							
			$delimlen	=	strlen( $target[0] );
			$bov		=	'(POSITION("/" IN ('.$location.')))';
			$lov		=	'('.$lov.'-'.$bov.')';
			$location	=	'SUBSTRING('.$location.' ,POSITION("/" IN '.$location.')+'.$delimlen.' )';
		}
	}
	if ( $target[1] ) {
		$ope	=	'';
		if ( preg_match('/^[-]?[0-9]+$/', $target[1]) ) {
			if ( $delimlen != '' ) {
				$location	=	strpos( $target[1], '-' ) !== false ? substr( $location, 0, -1 ).', ('.$lov.$target[1].'))' : substr( $location, 0, -1 ).', '.$target[1].')'; 
			} else {
				$lov		=	strpos( $target[1], '-' ) !== false ? '('.$lov.$target[1].')' : '('.$target[1].')';
				$location	=	'SUBSTRING(a.introtext, '.$bov.', '.$lov.')';
			}
		} else {
			if ( $target[1][0] == '-' ) {
				$ope		=	'-';
				$target[1]	=	substr( $target[1], 1 );
			}
			if ( $target[1][0] >= Chr(49) && $target[1][0] <= Chr(57) ) {
				$num		=	$target[1][0];
				$target[1]	=	substr( $target[1], 1 );
			} else {
				$num		=	1;
			}
			$location	=	'SUBSTRING_INDEX('.$location.', "'.$target[1].'", '.$ope.$num.')';
		}		
	}
						
	return $location;
}
?>