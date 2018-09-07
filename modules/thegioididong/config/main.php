<?php
return [

	'loginUrl' => ['dang-nhap.html'],


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
		    'pattern' => 'dang-nhap', //login
		    'route' => temp.'/site/dang-nhap',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],

		[
		    'pattern' => 'dang-ky', //login
		    'route' => temp.'/site/dang-ky',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],


		[
		    'pattern' => 'thong-tin-thanh-toan', //login
		    'route' => temp.'/thanhtoan',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],




		[
		    'pattern' => 'barcode', //cart.html
		    'route' => temp.'/barcode/index',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],


		[
		    'pattern' => 'update-cart', //cart.html
		    'route' => temp.'/cart/update',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],


		[
		    'pattern' => 'add-cart', //cart.html
		    'route' => temp.'/cart/add',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],


		[
		    'pattern' => 'add-cart-aj', //cart.html
		    'route' => temp.'/cart/add-aj',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
		],

		[
		    'pattern' => 'cart', //cart.html
		    'route' => temp.'/cart/index',
		    'defaults' => [/*'type' => 1*/],
		    'suffix' => '.html',
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


		['100','64'], //Thumb open album

		['590','500'], //Anh thong so

		['780','430'], //slide cove san pham noi bat
		['100','60'], //trang san pham: anh bai viet
		
	]
];


