<?php
return [
	'link' => [
		'sanpham' => 's1',
		'danhmuc' => '1',
		'thongso' => 't1',
	],

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

		[
		    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-s1<id:\d+>',
		    'route' => temp.'/sanpham',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],

		[
		    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-1<id:\d+>',
		    'route' => temp.'/danhmuc',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],


		[
		    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-t1<id:\d+>',
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
		['450','300'], //Cove chi tiet san pham
		['320','320'], //Cove chi tiet san pham

		['780','430'], //slide cove san pham noi bat
		['100','60'], //trang san pham: anh bai viet
	]
];


