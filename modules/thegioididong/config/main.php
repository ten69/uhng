<?php
use common\cont\D;
return [
    [
	    'pattern' => '',
	    'route' => temp,
	    // 'defaults' => [/*'type' => 1*/],
	    // 'suffix' => '.html',
	],

	[
	    'pattern' => 'san-pham', //san-pham.html
	    'route' => temp.'/sanpham',
	    'defaults' => [/*'type' => 1*/],
	    'suffix' => '.html',
	],


	// [
	//     'pattern' => 'danh-muc', //san-pham.html
	//     'route' => temp.'/danhmuc',
	//     'defaults' => [/*'type' => 1*/],
	//     'suffix' => '.html',
	// ],

	[
	    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_dm.'<id:\d+>',
	    'route' => temp.'/danhmuc',
	    'defaults' => [/*'type' => 1*/],
	    'suffix' => '.html',
	],
];


