<?php

return [
	'accounts' =>  [
		[
			'id'        => '1',
			'firstName' => 'Ruth',
			'lastName'  => 'Mccoy',
			'email'     => 'rmccoy0@topsy.com',
			'createdDt' => '2016-12-11 00:09:22',
		],
		[
			'id'        => '2',
			'firstName' => 'Walter',
			'lastName'  => 'Stone',
			'email'     => 'wstone1@intel.com',
			'createdDt' => '2016-02-20 08:04:42',
		],
		[
			'id'        => '3',
			'firstName' => 'Mark',
			'lastName'  => 'Bell',
			'email'     => 'mbell2@blinklist.com',
			'createdDt' => '2016-05-15 15:32:39',
		],
		[
			'id'        => '4',
			'firstName' => 'Bobby',
			'lastName'  => 'Lewis',
			'email'     => 'blewis3@google.com',
			'createdDt' => '2016-02-02 14:10:23',
		],
		[
			'id'        => '5',
			'firstName' => 'Anthony',
			'lastName'  => 'Taylor',
			'email'     => 'ataylor4@mlb.com',
			'createdDt' => '2015-12-19 04:20:51',
		],
	],

	'categories' => [
		[
			'id'        => '1',
			'name'      => 'Automotive',
			'createdDt' => '2016-07-18 23:47:36',
		],
		[
			'id'        => '2',
			'name'      => 'Sports',
			'createdDt' => '2016-03-19 21:58:23',
		],
		[
			'id'        => '3',
			'name'      => 'Computers',
			'createdDt' => '2016-05-06 14:04:25',
		],
	],

	'posts' => [
		[
			'id'         => '1',
			'title'      => 'Nec sem duis aliquam convallis nunc proin at',
			'body'       => 'Maecenas leo odio, condimentum id, luctus nec, molestie sed, justo. Pellentesque viverra pede ac diam. Cras pellentesque volutpat dui.',
			'ownerId'    => '1',
			'categoryId' => '1',
			'createdDt'  => '2016-07-07 11:36:40',
		],
		[
			'id'         => '2',
			'title'      => 'Aliquam quis turpis eget elit sodales scelerisque mauris sit amet',
			'body'       => 'In congue. Etiam justo. Etiam pretium iaculis justo.',
			'ownerId'    => '3',
			'categoryId' => '1',
			'createdDt'  => '2016-05-25 00:58:18',
		],
		[
			'id'         => '3',
			'title'      => 'Erat nulla tempus vivamus in felis eu sapien cursus vestibulum',
			'body'       => 'In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus. Nulla ut erat id mauris vulputate elementum. Nullam varius. Nulla facilisi.',
			'ownerId'    => '2',
			'categoryId' => '2',
			'createdDt'  => '2016-03-30 15:29:43',
		],
		[
			'id'         => '4',
			'title'      => 'Dolor quis odio consequat varius integer ac leo pellentesque ultrices',
			'body'       => 'Mauris enim leo, rhoncus sed, vestibulum sit amet, cursus id, turpis. Integer aliquet, massa id lobortis convallis, tortor risus dapibus augue, vel accumsan tellus nisi eu orci. Mauris lacinia sapien quis libero.',
			'ownerId'    => '5',
			'categoryId' => '3',
			'createdDt'  => '2016-08-31 04:02:20',
		],
		[
			'id'         => '5',
			'title'      => 'Pellentesque at nulla suspendisse potenti cras in purus eu magna',
			'body'       => 'Praesent id massa id nisl venenatis lacinia. Aenean sit amet justo. Morbi ut odio.',
			'ownerId'    => '1',
			'categoryId' => '1',
			'createdDt'  => '2016-06-08 18:49:45',
		],
		[
			'id'         => '6',
			'title'      => 'Posuere metus vitae ipsum aliquam non mauris morbi non lectus',
			'body'       => 'Praesent id massa id nisl venenatis lacinia. Aenean sit amet justo. Morbi ut odio.',
			'ownerId'    => '1',
			'categoryId' => '2',
			'createdDt'  => '2016-06-27 01:03:31',
		],
		[
			'id'         => '7',
			'title'      => 'Pede posuere nonummy integer non velit donec diam neque vestibulum',
			'body'       => 'Aenean lectus. Pellentesque eget nunc. Donec quis orci eget orci vehicula condimentum.',
			'ownerId'    => '2',
			'categoryId' => '3',
			'createdDt'  => '2016-05-16 06:16:38',
		],
	]
];