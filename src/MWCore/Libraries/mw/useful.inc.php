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

function encodePassword($password, $seed, $method = 'sha256')
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