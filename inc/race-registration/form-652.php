<?php
/**
 * Race registration experience: 2026 Channel The Holidays 5K, 1 Mile & Kids Run (Gravity Form 652).
 *
 * Same shape as Eastbound (form-628.php): "How many people?" (74) on page 1, then one GF page per
 * runner (pages 2-5) that GF shows by party size, so the runner screens are built in a loop with
 * 'needs'. Each runner also picks a long-sleeve shirt and may add a hoodie (size only for Yes).
 *
 * @package bellaworks
 */

// Field IDs for each runner, in page order. GF page = runner number + 1.
$regx_runners = array(
	1 => array( 'name' => 12, 'distance' => 8, 'pace' => 24, 'age' => 23, 'gender' => 33, 'phone' => 16, 'email' => 37, 'address' => 42, 'ecName' => 46, 'ecPhone' => 47, 'shirt' => 61, 'hoodie' => 87, 'hoodieSize' => 99 ),
	2 => array( 'name' => 13, 'distance' => 9, 'pace' => 26, 'age' => 22, 'gender' => 32, 'phone' => 17, 'email' => 38, 'address' => 43, 'ecName' => 48, 'ecPhone' => 53, 'shirt' => 107, 'hoodie' => 88, 'hoodieSize' => 59 ),
	3 => array( 'name' => 14, 'distance' => 10, 'pace' => 27, 'age' => 21, 'gender' => 31, 'phone' => 18, 'email' => 39, 'address' => 44, 'ecName' => 49, 'ecPhone' => 52, 'shirt' => 60, 'hoodie' => 89, 'hoodieSize' => 109 ),
	4 => array( 'name' => 15, 'distance' => 11, 'pace' => 28, 'age' => 20, 'gender' => 30, 'phone' => 19, 'email' => 40, 'address' => 45, 'ecName' => 50, 'ecPhone' => 51, 'shirt' => 57, 'hoodie' => 90, 'hoodieSize' => 111 ),
);

$regx_frames = array(
	array(
		'page'   => 1,
		'fields' => array( 74 ),
		'kicker' => 'Your group',
		'title'  => 'How many people are you registering?',
		'lede'   => 'Sign up just yourself, or up to four people at once.',
		'widget' => 'chips',
		'labels' => array(
			'1' => 'Just me',
			'2' => '2 people',
			'3' => '3 people',
			'4' => '4 people',
		),
		'solo'   => true,
		'art'    => 'wordmark.webp',
	),
);

$regx_line_items = array();

foreach ( $regx_runners as $n => $f ) {
	$shared = array(
		'page'      => $n + 1,
		'kicker'    => 'Runner ' . $n,
		'nameField' => $f['name'],
		'needs'     => array( 'field' => 74, 'min' => $n ),
	);

	$regx_frames[] = $shared + array(
		'fields' => array( $f['name'], $f['age'], $f['gender'] ),
		'title'  => 1 === $n ? "Who's running?" : "Who's runner {$n}?",
		'art'    => 'wordmark.webp',
	);
	$regx_frames[] = $shared + array(
		'fields'   => array( $f['distance'] ),
		'title'    => 'Which race is {name} running?',
		'widget'   => 'cards',
		'cards'    => array(
			'5k'               => '3.1 miles of trail and channel.',
			'1 Mile'           => 'One mile of trail and channel.',
			'1 mile'           => 'One mile of trail and channel.',
			'Kids 1 Mile Run'  => 'One mile for kids 9 and under.',
			"Kid's 1 Mile Run" => 'One mile for kids 9 and under.',
		),
		'solo'     => true,
		'hideDesc' => true,
		'art'      => 'wordmark.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['pace'] ),
		'title'  => "What's {name}'s pace per mile?",
		'lede'   => 'On trail, which runs slower than road.',
		'widget' => 'chips',
		'solo'   => true,
		'art'    => 'wordmark.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['phone'], $f['email'] ),
		'title'  => 'How do we reach {name}?',
		'lede'   => 'The pre-race email with race-week details goes here.',
		'art'    => 'wordmark.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['address'] ),
		'title'  => "What's {name}'s mailing address?",
		'solo'   => true,
		'art'    => 'wordmark.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['ecName'], $f['ecPhone'] ),
		'kicker' => 'Runner ' . $n . ' · Safety',
		'title'  => "Who's {name}'s emergency contact?",
		'art'    => 'wordmark.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['shirt'] ),
		'title'  => 'What long-sleeve shirt size for {name}?',
		'widget' => 'chips',
		'solo'   => true,
		'art'    => 'shirt.webp',
		'tip'    => 'Race shirts are only guaranteed for runners who register on or before <strong>November 30, 2026</strong>.',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['hoodie'] ),
		'title'  => 'Add a race hoodie for {name}?',
		'widget' => 'cards',
		'cards'  => array(
			'Yes' => 'This year\'s Channel The Holidays hoodie, $45 plus tax.',
			'No'  => 'Skip the hoodie.',
		),
		'solo'   => true,
		'art'    => 'hoodie.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['hoodieSize'] ),
		'title'  => 'What hoodie size for {name}?',
		'widget' => 'chips',
		'solo'   => true,
		'art'    => 'hoodie.webp',
	);

	// "Jordan Smith · Hoodie (Medium)" in the order summary.
	$regx_line_items[ $f['hoodie'] ] = '{' . $f['name'] . '} · Hoodie ({' . $f['hoodieSize'] . '})';
}

$regx_frames[] = array(
	'page'   => 6,
	'fields' => array( 65, 63, 66, 64, 76 ),
	'kicker' => 'The fine print',
	'title'  => 'A few things to agree to.',
	'lede'   => 'Check each one to confirm you understand it.',
	'art'    => 'wordmark.webp',
);
$regx_frames[] = array(
	'page'   => 7,
	'fields' => array( 36, 77, 78, 35 ),
	'kicker' => 'Last step',
	'title'  => 'Review and pay.',
	'widget' => 'summary',
	'art'    => 'wordmark.webp',
	'submit' => 'Register',
);

return array(
	'enabled'   => true,

	'theme'     => 'holidays',
	'fonts'     => 'https://fonts.googleapis.com/css2?family=Rye&family=Alfa+Slab+One&family=Work+Sans:wght@500;600;700;800&display=swap',
	'colors'    => array( 'bg' => '#2c555b' ),
	'raceUrl'   => 'https://whitewater.org/race/channeltheholidays/',

	'welcome'   => array(
		'logo'    => 'cth-logo.webp',
		'logoAlt' => 'Channel The Holidays 5K and 1 Mile Race, December 12, 2026',
		'title'   => 'Run the channels.',
		'lede'    => 'Run through the Whitewater Center\'s channels, without the rapids, on December 12. Register up to four people for the 5K, the 1 mile or the Kids 1 Mile Run. Online registration closes December 10.',
		'list'    => array(
			'Each runner\'s age, pace and shirt size',
			'An emergency contact for each runner',
			'A card: $45 for the 5K, $37 for the mile, $20 for the Kids Run',
		),
		'cta'     => 'Start registration',
	),

	'frames'    => $regx_frames,

	'lineItems' => $regx_line_items,

	'done'      => array(
		'title' => 'See you in the channels!',
		'cta'   => 'Back to race info',
	),
);
