<?php
return [
	'router' => [
	    [
		    'pattern' => '',
		    'route' => temp,
		    // 'defaults' => [/*'type' => 1*/],
		    // 'suffix' => '.html',
		],


		[
		    'pattern' => 'api', //san-pham.html
		    'route' => temp.'/site/api',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '',
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
		    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-11<id:\d+>',
		    'route' => temp.'/danhmuc',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],


		[
		    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-12<id:\d+>',
		    'route' => temp.'/danhmuc/thongso',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],

	],
	'size-image' => [		
		['80','80'],
		['800','300'],
		['380','145'],
		['390','80'],
		['390','390'],
		['100','100'],
		['150','150'],
		['180','180'],
		['250','250'],
		['300','300'],
		['595','100'],
		['800','170'],
		['768','163'],
	]
];


