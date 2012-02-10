<?php

function pre($stuff)
{
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}

function encodeSeed($seed)
{
	
	return substr(sha1($seed), 0, 5);
	
}

function generateSeed()
{
	
	$seed = 0;
	
	do{	$seed = microtime(true) ;}
	while( strpos( encodeSeed($seed), 'o' ) !== false || strpos( encodeSeed($seed), '0' ) !== false);
	
	return $seed;
	
}

function generateSalt($length)
{
	
    return substr(md5(uniqid(rand(), true)), 0, $length);

}

function encodePassword($password, $salt, $method = 'sha256')
{
	
	$hash = hash($method, $password);
	
	return hash($method, $salt . $hash);
	
}

function hex2rgb($color)
{
	
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);

}

function rgb2hex($r, $g=-1, $b=-1)
{
	
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return '#'.$color;

}

function standardize(&$data)
{

	if( is_array($data) )
	{

		return array_map(__FUNCTION__, $data);
		
	}
	
	if( is_object($data) )
	{

		return $data -> standardize();
		
	}
	
	return $data;
	
}

/**
 * unicode_ord
 * 
 * Returns the unicode value of the string
 *
 * @param string $c The source string
 * @param integer $i The index to get the char from (passed by reference for use in a loop)
 * @return integer The value of the char at $c[$i]
 * @author kerry at shetline dot com
 * @author Dom Hastings - modified to suit my needs
 * @see http://www.php.net/manual/en/function.ord.php#78032
 */
function unicode_ord(&$c, &$i = 0) {
  // get the character length
  $l = strlen($c);
  // copy the offset
  $index = $i;
  
  // check it's a valid offset
  if ($index >= $l) {
    return false;
  }
  
  // check the value
  $o = ord($c[$index]);
  
  // if it's ascii
  if ($o <= 0x7F) {
    return $o;
  
  // not sure what it is...
  } elseif ($o < 0xC2) {
    return false;
  
  // if it's a two-byte character  
  } elseif ($o <= 0xDF && $index < $l - 1) {
    $i += 1;
    return ($o & 0x1F) <<  6 | (ord($c[$index + 1]) & 0x3F);
  
  // three-byte
  } elseif ($o <= 0xEF && $index < $l - 2) {
    $i += 2;
    return ($o & 0x0F) << 12 | (ord($c[$index + 1]) & 0x3F) << 6 | (ord($c[$index + 2]) & 0x3F);
    
  // four-byte
  } elseif ($o <= 0xF4 && $index < $l - 3) {
    $i += 3;
    return ($o & 0x0F) << 18 | (ord($c[$index + 1]) & 0x3F) << 12 | (ord($c[$index + 2]) & 0x3F) << 6 | (ord($c[$index + 3]) & 0x3F);
    
  // not sure what it is...
  } else {
    return false;
  }
}

/**
 * unicode_chr
 *
 * @param string $c 
 * @return string
 * @author Miguel Perez
 * @see http://www.php.net/manual/en/function.chr.php#77911
 */
function unicode_chr(&$c) {
  if ($c <= 0x7F) {
    return chr($c);
    
  } else if ($c <= 0x7FF) {
    return chr(0xC0 | $c >> 6).chr(0x80 | $c & 0x3F);
    
  } else if ($c <= 0xFFFF) {
    return chr(0xE0 | $c >> 12).chr(0x80 | $c >> 6 & 0x3F).chr(0x80 | $c & 0x3F);
    
  } else if ($c <= 0x10FFFF) {
    return chr(0xF0 | $c >> 18) . chr(0x80 | $c >> 12 & 0x3F).chr(0x80 | $c >> 6 & 0x3F).chr(0x80 | $c & 0x3F);
    
  } else {
    return false;
  }
}

/**
 * xmlentities
 * 
 * Makes the specified string XML-safe
 *
 * @param string $s
 * @param boolean $hex Whether or not to make hexadecimal entities (as opposed to decimal)
 * @return string The XML-safe result
 * @author Dom Hastings
 * @dependencies unicode_ord()
 * @see http://www.w3.org/TR/REC-xml/#sec-predefined-ent
 */
function xmlentities($s, $hex = true) {
  // if the string is empty
  if (empty($s)) {
    // just return it
    return $s;
  }
  
  // create the return string
  $r = '';
  // get the length
  $l = strlen($s);
  
  // iterate the string
  for ($i = 0; $i < $l; $i++) {
    // get the value of the character
    $o = unicode_ord($s, $i);
    
    // valid cahracters
    $v = (
      // \t \n <vertical tab> <form feed> \r
      ($o >= 9 && $o <= 13) || 
      // <space> !
      ($o == 32) || ($o == 33) || 
      // # $ %
      ($o >= 35 && $o <= 37) || 
      // ( ) * + , - . /
      ($o >= 40 && $o <= 47) || 
      // numbers
      ($o >= 48 && $o <= 57) ||
      // : ;
      ($o == 58) || ($o == 59) ||
      // = ?
      ($o == 61) || ($o == 63) ||
      // @
      ($o == 64) ||
      // uppercase
      ($o >= 65 && $o <= 90) ||
      // [ \ ] ^ _ `
      ($o >= 91 && $o <= 96) || 
      // lowercase
      ($o >= 97 && $o <= 122) || 
      // { | } ~
      ($o >= 123 && $o <= 126)
    );
    
    // if it's valid, just keep it
    if ($v) {
      $r .= $s[$i];
    
    // &
    } elseif ($o == 38) {
      $r .= '&amp;';
    
    // <
    } elseif ($o == 60) {
      $r .= '&lt;';
    
    // >
    } elseif ($o == 62) {
      $r .= '&gt;';
    
    // '
    } elseif ($o == 39) {
      $r .= '&apos;';
    
    // "
    } elseif ($o == 34) {
      $r .= '&quot;';
    
    // unknown, add it as a reference
    } elseif ($o > 0) {
      if ($hex) {
        $r .= '&#x'.strtoupper(dechex($o)).';';
        
      } else {
        $r .= '&#'.$o.';';
      }
    }
  }
  
  return $r;
}

/**
 * xmlentity_decode
 * 
 * Converts XML entity encoded data back to a unicode string
 *
 * @param string $s The XML encoded string
 * @param array $entities Additional entities to decode (optional)
 * @return string
 * @dependencies unicode_chr()
 * @author Dom Hastings
 */
function xml_entity_decode($s, $entities = array()) {
  // if the string is empty, just return it
  if (empty($s)) {
    return $s;
  }
  
  // check that entities is an array
  if (!is_array($entities)) {
    throw new Exception('xmlentity_decode expects argument 2 to be array.');
  }
  
  // initialise vars
  $r = '';
  $l = strlen($s);
  
  // merge the entities with the defaults (amp, lt, gt, apos and quot MUST take precedence)
  $entities = array_merge($entities, array(
    'amp' => '&',
    'lt' => '<',
    'gt' => '>',
    'apos' => '\'',
    'quot' => '"'
  ));
  
  // loop through the string
  for ($i = 0; $i < $l; $i++) { 
    // if it looks like an entity
    if ($s[$i] == '&') {
      // initialise some vars
      $e = '';
      $c = '';
      
      // loop until we find a semi-colon
      for ($j = ++$i; ($c != ';' && $j < $l); $j++) {
        // get the char
        $c = $s[$j];
        
        // if it's not a semi-colon
        if ($c != ';') {
          // add it to the temporary entity string
          $e .= $c;
        }
      }
      
      // update the index
      $i = ($j - 1);
      
      // if the first char is a #, it's a numeric entity
      if ($e[0] == '#') {
        // if the second char is x it's a hexadecimal entity
        if ($e[1] == 'x') {
          // store the number
          $e = hexdec(substr($e, 2));
          
        } else {
          // store the number
          $e = substr($e, 1);
        }
      }
      
      // if we got a number
      if (is_numeric($e)) {
        // get the unicode char from it
        $r .= unicode_chr($e);
      
      // otherwise
      } else {
        // if it's in our array (which it should be)
        if (array_key_exists($e, $entities)) {
          // append the character
          $r .= $entities[$e];
        
        // otherwise
        } else {
          // throw an exception, we don't know what to do with this
          throw new Exception('Unknown entity "'.$e.'"');
        }
      }
    
    // if it's just a regular char
    } else {
      // append it
      $r .= $s[$i];
    }
  }
  
  return $r;
}