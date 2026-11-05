<?php
/**
 * Race registration experience: 2027 Rocky Road 5K, 10K & Half Marathon (Gravity Form 694).
 *
 * Shared GP Nested Forms template, same shape as Shot in the Dark (see form-757.php for the
 * 'nested' keys): runners are entries of child form 692, then agreements, then payment.
 * Runners can add a hoodie in the child form; 'addons' names it on the roster cards and in
 * the order summary ("Half Marathon + hoodie"), since a runner's line is their entry total.
 *
 * @package bellaworks
 */

return array(
	'enabled' => true,

	'theme'   => 'rocky',
	'light'   => true,
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Rubik+Dirt&family=Oswald:wght@500;600;700&family=Nunito+Sans:wght@500;600;700;800&display=swap',
	'colors'  => array( 'bg' => '#fce8c5' ),
	'raceUrl' => 'https://whitewater.org/race/rocky-road/',

	'welcome' => array(
		'logo'    => 'rocky-road-logo.webp',
		'logoAlt' => 'Rocky Road road-to-trail race: Half Marathon, 10K and 5K, January 9, 2027, presented by Lexus',
		'title'   => 'Road first. Then rocks.',
		'lede'    => 'Register for Rocky Road on January 9: start on the Whitewater Center Parkway, then head onto the trails. Sign up just yourself or your whole crew. Pre-registration closes January 7.',
		'list'    => array(
			'Each runner\'s age, pace and shirt size',
			'An emergency contact for each runner',
			'A card: $42 for the 5K, $50 for the 10K, $75 for the half',
		),
		'cta'     => 'Start registration',
	),

	'nested'  => array(
		'field'        => 161,
		'formId'       => 692,
		'controlled'   => array( 172 ),
		'nameField'    => 18,
		'productField' => 131,
		'addons'       => array( 164 => 'hoodie' ),
		'singular'     => 'runner',
		'min'          => 1,
		'addFirst'     => 'Add your first runner',
		'addAnother'   => 'Add another runner',
		'addLabel'     => 'Add runner',
		'saveLabel'    => 'Save runner',
		'minMessage'   => 'Add at least one runner to keep going.',
		'art'          => 'signpost.webp',
		'frames'       => array(
			array(
				'fields' => array( 18, 123, 8 ),
				'kicker' => '{who}',
				'title'  => "Who's running?",
				'lede'   => 'Awards go to the top three men and women in each age group.',
			),
			array(
				'fields' => array( 131 ),
				'kicker' => '{who}',
				'title'  => 'Which distance is {name} running?',
				'widget' => 'cards',
				'cards'  => array(
					'5k'            => '3.1 miles, from the Parkway onto the trails.',
					'10k'           => '6.2 miles, from the Parkway onto the trails.',
					'Half Marathon' => '13.1 miles, from the Parkway onto the trails.',
				),
				'solo'   => true,
			),
			array(
				'fields' => array( 166 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s pace per mile?",
				'widget' => 'chips',
				'solo'   => true,
			),
			array(
				'fields' => array( 14, 13 ),
				'kicker' => '{who}',
				'title'  => 'How do we reach {name}?',
				'lede'   => 'The pre-race email with race-week details goes here.',
			),
			array(
				'fields' => array( 15 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s mailing address?",
				'solo'   => true,
			),
			array(
				'fields' => array( 17, 16 ),
				'kicker' => '{who} · Safety',
				'title'  => "Who's {name}'s emergency contact?",
			),
			array(
				'fields' => array( 21 ),
				'kicker' => '{who}',
				'title'  => 'What shirt size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
				'tip'    => 'Race shirts are only guaranteed for runners who register on or before <strong>December 28, 2026</strong>.',
			),
			array(
				'fields' => array( 164 ),
				'kicker' => '{who}',
				'title'  => 'Add a race hoodie for {name}?',
				'widget' => 'cards',
				'cards'  => array(
					'Yes' => 'This year\'s Rocky Road hoodie, $45 plus tax.',
					'No'  => 'Skip the hoodie.',
				),
				'solo'   => true,
				'art'    => 'hoodie.webp',
			),
			array(
				'fields' => array( 163, 165 ),
				'kicker' => '{who}',
				'title'  => 'What hoodie size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
				'art'    => 'hoodie.webp',
			),
		),
	),

	'frames'  => array(
		array(
			'page'   => 1,
			'fields' => array( 161, 172 ),
			'kicker' => 'Your crew',
			'title'  => "Who's running?",
			'lede'   => 'Add each runner you\'re registering. You can register just yourself or a group.',
			'widget' => 'roster',
			'art'    => 'scene.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 139, 140, 22, 122, 25, 129 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'signpost.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 173, 149, 150, 151, 69 ),
			'kicker' => 'Last step',
			'title'  => 'Review and pay.',
			'widget' => 'summary',
			'art'    => 'signpost.webp',
			'submit' => 'Register',
		),
	),

	'done'    => array(
		'title' => 'See you on the trail!',
		'cta'   => 'Back to race info',
	),
);
