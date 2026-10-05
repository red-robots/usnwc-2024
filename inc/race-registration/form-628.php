<?php
/**
 * Race registration experience: 2026 Eastbound Half Marathon + 5K (Gravity Form 628).
 *
 * See form-629.php for what the frame keys mean. This form registers one to
 * four runners, each on their own Gravity Forms page (2-5) that GF skips when
 * the party is smaller, so the runner screens are built in a loop and two more
 * frame keys come into play:
 *
 *  needs      array( 'field' => ID, 'min' => N ): the frame only counts toward
 *             progress and the order summary when that field's value is N or more
 *  nameField  a Name field; {name} in the title/lede becomes its first name, and
 *             the order summary labels this frame's product with the full name
 *
 * @package bellaworks
 */

// Field IDs for each runner, in page order. GF page = runner number + 1.
$regx_runners = array(
	1 => array( 'name' => 18, 'distance' => 131, 'pace' => 109, 'age' => 123, 'gender' => 8, 'phone' => 14, 'email' => 13, 'address' => 15, 'ecName' => 17, 'ecPhone' => 16, 'shirt' => 21 ),
	2 => array( 'name' => 72, 'distance' => 132, 'pace' => 110, 'age' => 124, 'gender' => 113, 'phone' => 94, 'email' => 97, 'address' => 100, 'ecName' => 103, 'ecPhone' => 106, 'shirt' => 90 ),
	3 => array( 'name' => 31, 'distance' => 133, 'pace' => 111, 'age' => 125, 'gender' => 114, 'phone' => 95, 'email' => 98, 'address' => 101, 'ecName' => 104, 'ecPhone' => 107, 'shirt' => 92 ),
	4 => array( 'name' => 82, 'distance' => 134, 'pace' => 112, 'age' => 126, 'gender' => 115, 'phone' => 96, 'email' => 99, 'address' => 102, 'ecName' => 105, 'ecPhone' => 108, 'shirt' => 93 ),
);

$regx_frames = array(
	array(
		'page'   => 1,
		'fields' => array( 130 ),
		'kicker' => 'Your group',
		'title'  => 'How many runners are you registering?',
		'lede'   => 'Sign up just yourself, or up to four people at once.',
		'widget' => 'chips',
		'labels' => array(
			'1' => 'Just me',
			'2' => '2 runners',
			'3' => '3 runners',
			'4' => '4 runners',
		),
		'solo'   => true,
		'art'    => 'climb-1.webp',
	),
);

foreach ( $regx_runners as $n => $f ) {
	$shared = array(
		'page'      => $n + 1,
		'kicker'    => 'Runner ' . $n,
		'nameField' => $f['name'],
		'needs'     => array( 'field' => 130, 'min' => $n ),
	);

	$regx_frames[] = $shared + array(
		'fields' => array( $f['name'], $f['age'], $f['gender'] ),
		'title'  => 1 === $n ? "Who's running?" : "Who's runner {$n}?",
		'lede'   => 'Awards are given by age group.',
		'art'    => 'climb-1.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['distance'] ),
		'title'  => 'Which distance is {name} running?',
		'widget' => 'cards',
		'cards'  => array(
			'5k'            => '3.1 miles of singletrack trail.',
			'Half Marathon' => '13.1 miles: two laps of East Main, with over 1,000 ft of climbing.',
		),
		'solo'   => true,
		'art'    => 'climb-1.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['pace'] ),
		'title'  => "What's {name}'s pace per mile?",
		'lede'   => 'On singletrack trail, which runs slower than road.',
		'widget' => 'chips',
		'solo'   => true,
		'art'    => 'climb-2.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['phone'], $f['email'] ),
		'title'  => 'How do we reach {name}?',
		'art'    => 'climb-2.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['address'] ),
		'title'  => "What's {name}'s mailing address?",
		'solo'   => true,
		'art'    => 'climb-2.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['ecName'], $f['ecPhone'] ),
		'kicker' => 'Runner ' . $n . ' · Safety',
		'title'  => "Who's {name}'s emergency contact?",
		'art'    => 'climb-3.webp',
	);
	$regx_frames[] = $shared + array(
		'fields' => array( $f['shirt'] ),
		'title'  => 'What shirt size for {name}?',
		'widget' => 'chips',
		'solo'   => true,
		'art'    => 'climb-3.webp',
		'tip'    => 'Race shirts are only guaranteed for runners who register on or before <strong>October 19, 2026</strong>.',
	);
}

$regx_frames[] = array(
	'page'   => 6,
	'fields' => array( 139, 140, 22, 122, 25, 129 ),
	'kicker' => 'The fine print',
	'title'  => 'A few things to agree to.',
	'lede'   => 'Check each one to confirm you understand it.',
	'art'    => 'climb-3.webp',
);
$regx_frames[] = array(
	'page'   => 7,
	'fields' => array( 149, 150, 151, 69, 27 ),
	'kicker' => 'Last step',
	'title'  => 'Review and pay.',
	'widget' => 'summary',
	'art'    => 'climb-3.webp',
	'submit' => 'Register',
);

return array(
	// Off for everyone until it's been tried on live with ?regx=1.
	'enabled' => false,

	'theme'   => 'eastbound',
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Anton&family=Jost:wght@500;600;700&display=swap',
	'colors'  => array( 'bg' => '#463c45' ),
	'raceUrl' => 'https://whitewater.org/race/eastbound/',

	'welcome' => array(
		'logo'    => 'eastbound-logo.webp',
		'logoAlt' => 'Eastbound Half Marathon + 5K Trail Race, October 31, 2026',
		'title'   => 'Earn the climb.',
		'lede'    => 'Register for the Eastbound Half Marathon or 5K at the U.S. National Whitewater Center. It takes about three minutes per runner.',
		'list'    => array(
			'Each runner\'s age, pace and shirt size',
			'An emergency contact for each runner',
			'A card: $42 for the 5K, $75 for the half',
		),
		'cta'     => 'Start registration',
	),

	'frames'  => $regx_frames,

	'done'    => array(
		'title' => "You're in!",
		'cta'   => 'Back to race info',
	),
);
