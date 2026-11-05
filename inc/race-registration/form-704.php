<?php
/**
 * Race registration experience: 2027 Frigid Trail Race & Polar Plunge (Gravity Form 704).
 *
 * Shared GP Nested Forms template (see form-757.php for the 'nested' keys): runners are
 * entries of child form 703, then agreements, then payment. Each runner can add a hoodie
 * (with a size) and a Frigid mug; 'addons' names both on roster cards and summary lines.
 *
 * @package bellaworks
 */

return array(
	'enabled' => true,

	'theme'   => 'frigid',
	'light'   => true,
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Shrikhand&family=Saira+Condensed:wght@500;600;700&family=Barlow:wght@500;600;700;800&display=swap',
	'colors'  => array( 'bg' => '#e9dfd3' ),
	'raceUrl' => 'https://whitewater.org/race/frigid-trail-race-and-plunge/',

	'welcome' => array(
		'logo'    => 'frigid-logo.webp',
		'logoAlt' => 'Frigid 5K + 10K Trail Race and Polar Plunge, January 30, 2027, presented by Lexus',
		'title'   => 'Brave the cold.',
		'lede'    => 'Register for Frigid on January 30: a 5K or 10K trail race, then a plunge into the Deep Water Solo pool. Sign up just yourself or your whole crew. Pre-registration closes January 27.',
		'list'    => array(
			'Each runner\'s age, pace and shirt size',
			'An emergency contact for each runner',
			'A card: $45 for the 5K, $55 for the 10K',
		),
		'cta'     => 'Start registration',
	),

	'nested'  => array(
		'field'        => 105,
		'formId'       => 703,
		'controlled'   => array( 110 ),
		'nameField'    => 20,
		'productField' => 12,
		'addons'       => array(
			109 => 'hoodie',
			110 => 'mug',
		),
		'singular'     => 'runner',
		'min'          => 1,
		'addFirst'     => 'Add your first runner',
		'addAnother'   => 'Add another runner',
		'addLabel'     => 'Add runner',
		'saveLabel'    => 'Save runner',
		'minMessage'   => 'Add at least one runner to keep going.',
		'art'          => 'splash.webp',
		'frames'       => array(
			array(
				'fields' => array( 20, 27, 29 ),
				'kicker' => '{who}',
				'title'  => "Who's running?",
				'lede'   => 'Awards go to the top three men and women in each age group.',
			),
			array(
				'fields' => array( 12 ),
				'kicker' => '{who}',
				'title'  => 'Which distance is {name} running?',
				'widget' => 'cards',
				'cards'  => array(
					'5k Run & Plunge'  => '3.1 miles of trail, then the plunge into the Deep Water Solo pool. Starts at 10am.',
					'10k Run & Plunge' => '6.2 miles of trail, then the plunge into the Deep Water Solo pool. Starts at 9am.',
				),
				'solo'   => true,
			),
			array(
				'fields' => array( 116 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s pace per mile?",
				'lede'   => 'On trail, which runs slower than road.',
				'widget' => 'chips',
				'solo'   => true,
			),
			array(
				'fields' => array( 114, 33 ),
				'kicker' => '{who}',
				'title'  => 'How do we reach {name}?',
				'lede'   => 'The pre-race email with race-week details goes here.',
			),
			array(
				'fields' => array( 41 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s mailing address?",
				'solo'   => true,
			),
			array(
				'fields' => array( 51, 115 ),
				'kicker' => '{who} · Safety',
				'title'  => "Who's {name}'s emergency contact?",
			),
			array(
				'fields' => array( 53 ),
				'kicker' => '{who}',
				'title'  => 'What shirt size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
				'tip'    => 'Race shirts are only guaranteed for runners who register on or before <strong>January 18, 2027</strong>.',
			),
			array(
				'fields' => array( 109 ),
				'kicker' => '{who}',
				'title'  => 'Add a race hoodie for {name}?',
				'widget' => 'cards',
				'cards'  => array(
					'Yes' => 'This year\'s Frigid hoodie, $45 plus tax.',
					'No'  => 'Skip the hoodie.',
				),
				'solo'   => true,
				'tip'    => 'Hoodies are only guaranteed for runners who register on or before <strong>January 18, 2027</strong>.',
			),
			array(
				'fields' => array( 83, 112 ),
				'kicker' => '{who}',
				'title'  => 'What hoodie size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
			),
			array(
				'fields' => array( 110, 113 ),
				'kicker' => '{who}',
				'title'  => 'Warm up with a Frigid mug?',
				'widget' => 'cards',
				'cards'  => array(
					'Yes' => 'A Frigid mug that comes with a serving of hot chocolate, $20 plus tax.',
					'No'  => 'Skip the mug.',
				),
				'solo'   => true,
				'art'    => 'mug.webp',
			),
		),
	),

	'frames'  => array(
		array(
			'page'   => 1,
			'fields' => array( 105, 110 ),
			'kicker' => 'Your crew',
			'title'  => "Who's running?",
			'lede'   => 'Add each runner you\'re registering. You can register just yourself or a group.',
			'widget' => 'roster',
			'art'    => 'splash.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 57, 58, 59, 60, 61 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'splash.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 112, 63, 103, 109, 111 ),
			'kicker' => 'Last step',
			'title'  => 'Review and pay.',
			'widget' => 'summary',
			'art'    => 'splash.webp',
			'submit' => 'Register',
		),
	),

	'done'    => array(
		'title' => 'See you at the plunge!',
		'cta'   => 'Back to race info',
	),
);
