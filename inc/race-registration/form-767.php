<?php
/**
 * Race registration experience: 26-27 Pond Hockey Winter League (Gravity Form 767).
 *
 * See form-629.php for what the frame keys mean. One player per registration; GF's own
 * conditional logic does the branching, and a frame whose fields are all hidden is skipped:
 *  - Team vs Free Agent (46): free agents get a division (47) and skip page 2 entirely.
 *  - Captain (61): captains pick the division (62) and free-agent policy (48);
 *    everyone else names their captain (8).
 *  - Hoodie (57): the size (58) only appears for Yes.
 * The $150 Registration Fee (5) is a hidden product: never shown, still in the total.
 *
 * @package bellaworks
 */

return array(
	'enabled' => true,

	'theme'   => 'hockey',
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@700;800;900&family=Figtree:wght@500;600;700;800&family=Josefin+Sans:wght@600;700&display=swap',
	'colors'  => array( 'bg' => '#e7e1d1' ),
	'raceUrl' => 'https://whitewater.org/race/pond-hockey/',

	'welcome' => array(
		'logo'    => 'pond-hockey-logo.webp',
		'logoAlt' => 'Pond Hockey, presented by Lexus',
		'title'   => 'Hit the ice.',
		'lede'    => 'Register for the Whitewater Pond Hockey winter league: 3v3 games from November 30 to February 4. Every player registers on their own, and your whole roster needs to sign up by November 18.',
		'list'    => array(
			'Your team name (and your captain\'s name, if it isn\'t you)',
			'Your contact info and mailing address',
			'A card for the $150 registration fee',
		),
		'cta'     => 'Start registration',
	),

	'frames'  => array(
		array(
			'page'   => 1,
			'fields' => array( 46 ),
			'kicker' => 'The league',
			'title'  => 'Are you joining a team?',
			'widget' => 'chips',
			'labels' => array(
				'Team'       => 'Yes, I\'m on a team',
				'Free Agent' => 'I\'m a free agent',
			),
			'solo'   => true,
			'art'    => 'shield.webp',
		),
		array(
			'page'   => 1,
			'fields' => array( 47 ),
			'kicker' => 'The league',
			'title'  => 'Which division would you like to play in?',
			'widget' => 'chips',
			'solo'   => true,
			'art'    => 'shield.webp',
		),
		array(
			'page'     => 1,
			'fields'   => array( 50, 54, 53 ),
			'kicker'   => 'About you',
			'title'    => "Who's playing?",
			'lede'     => 'Players must be 18 or older.',
			'hideDesc' => true,
			'art'      => 'shield.webp',
		),
		array(
			'page'   => 1,
			'fields' => array( 51, 52 ),
			'kicker' => 'About you',
			'title'  => 'How do we reach you?',
			'art'    => 'shield.webp',
		),
		array(
			'page'   => 1,
			'fields' => array( 55 ),
			'kicker' => 'About you',
			'title'  => "What's your mailing address?",
			'solo'   => true,
			'art'    => 'shield.webp',
		),

		array(
			'page'   => 2,
			'fields' => array( 61 ),
			'kicker' => 'Your team',
			'title'  => 'Are you the team captain?',
			'solo'   => true,
			'art'    => 'shield.webp',
		),
		array(
			'page'     => 2,
			'fields'   => array( 62 ),
			'kicker'   => 'Your team',
			'title'    => 'Which division is your team playing in?',
			'widget'   => 'cards',
			'cards'    => array(
				'Competitive'  => 'Games on Tuesdays or Thursdays.',
				'Recreational' => 'Games on Mondays or Wednesdays.',
			),
			'solo'     => true,
			'hideDesc' => true,
			'art'      => 'shield.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 48 ),
			'kicker' => 'Your team',
			'title'  => 'Will your team take free agents?',
			'widget' => 'chips',
			'solo'   => true,
			'art'    => 'shield.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 8 ),
			'kicker' => 'Your team',
			'title'  => "Who's your team captain?",
			'lede'   => 'So we can put you on the right roster.',
			'solo'   => true,
			'art'    => 'shield.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 64 ),
			'kicker' => 'Your team',
			'title'  => "What's your team name?",
			'lede'   => 'Use the same name as the rest of your team.',
			'solo'   => true,
			'art'    => 'shield.webp',
		),

		array(
			'page'   => 3,
			'fields' => array( 57 ),
			'kicker' => 'League gear',
			'title'  => 'Want the Pond Hockey hoodie?',
			'widget' => 'cards',
			'cards'  => array(
				'Yes' => 'This season\'s league hoodie, $45 plus tax.',
				'No'  => 'Skip the hoodie.',
			),
			'solo'   => true,
			'art'    => 'hoodie.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 58, 71 ),
			'kicker' => 'League gear',
			'title'  => 'What size hoodie?',
			'widget' => 'chips',
			'solo'   => true,
			'art'    => 'hoodie.webp',
		),

		array(
			'page'   => 4,
			'fields' => array( 17, 18, 72, 19, 21, 41, 20, 42 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'shield.webp',
		),

		array(
			'page'   => 5,
			'fields' => array( 26, 43, 23, 22 ),
			'kicker' => 'Last step',
			'title'  => 'Review and pay.',
			'widget' => 'summary',
			'art'    => 'shield.webp',
			'submit' => 'Register',
		),
	),

	// Order summary lines ({58} = the hoodie size picked). $0 picks like the size itself are left out.
	'lineItems' => array(
		5  => 'League registration',
		57 => 'Pond Hockey hoodie ({58})',
	),

	'done'    => array(
		'title' => "You're on the ice!",
		'cta'   => 'Back to league info',
	),
);
