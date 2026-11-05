<?php
/**
 * Race registration experience: 2027 Run of the Mill 5K Trail Race, Huger, SC (Gravity Form 723).
 *
 * Shared GP Nested Forms template (see form-757.php for the 'nested' keys): runners are
 * entries of child form 722. The $45 is a hidden product (74); "Select a Race" (73) is a
 * GF 3 multi_choice field with one choice, and runners also pick the post-race property tour.
 *
 * @package bellaworks
 */

return array(
	'enabled' => true,

	'theme'   => 'mill',
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Holtwood+One+SC&family=Zilla+Slab:wght@600;700&family=Josefin+Sans:wght@600;700&family=Libre+Franklin:wght@500;600;700;800&display=swap',
	'colors'  => array( 'bg' => '#174946' ),
	'raceUrl' => 'https://whitewater.org/race/run-of-the-mill-5k-trail-race/',

	'welcome' => array(
		'logo'    => 'mill-logo.webp',
		'logoAlt' => 'Run of the Mill 5K Trail Race, March 6, 2027, Middleburg, Huger, SC',
		'title'   => 'Run under the oaks.',
		'lede'    => 'Register for Run of the Mill on March 6: a 5K on the trails of the historic Middleburg property in Huger, SC, with two post-race pancakes for every runner. Pre-registration closes March 5.',
		'list'    => array(
			'Each runner\'s age, pace and shirt size',
			'An emergency contact for each runner',
			'A card: $45 per runner',
		),
		'cta'     => 'Start registration',
	),

	'nested'  => array(
		'field'        => 80,
		'formId'       => 722,
		'controlled'   => array( 81 ),
		'nameField'    => 12,
		'productField' => 73,
		'productNames' => array( '5k' => '5K' ),
		'singular'     => 'runner',
		'min'          => 1,
		'addFirst'     => 'Add your first runner',
		'addAnother'   => 'Add another runner',
		'addLabel'     => 'Add runner',
		'saveLabel'    => 'Save runner',
		'minMessage'   => 'Add at least one runner to keep going.',
		'art'          => 'oak.webp',
		'frames'       => array(
			array(
				'fields' => array( 12, 23, 33 ),
				'kicker' => '{who}',
				'title'  => "Who's running?",
				'lede'   => 'Awards go to the top three men and women overall.',
			),
			array(
				'fields' => array( 73 ),
				'kicker' => '{who}',
				'title'  => 'Confirm {name}\'s race.',
				'widget' => 'cards',
				'cards'  => array(
					'5k ($45)' => '3.1 miles on the trails of the historic Middleburg property. The course opens at 8:30am.',
				),
				'solo'   => true,
			),
			array(
				'fields' => array( 24 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s pace per mile?",
				'lede'   => 'On singletrack trail, which runs slower than road.',
				'widget' => 'chips',
				'solo'   => true,
			),
			array(
				'fields' => array( 16, 37 ),
				'kicker' => '{who}',
				'title'  => 'How do we reach {name}?',
				'lede'   => 'The pre-race email with race-week details goes here.',
			),
			array(
				'fields' => array( 42 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s mailing address?",
				'solo'   => true,
			),
			array(
				'fields' => array( 46, 47 ),
				'kicker' => '{who} · Safety',
				'title'  => "Who's {name}'s emergency contact?",
			),
			array(
				'fields' => array( 75 ),
				'kicker' => '{who}',
				'title'  => 'Join the property tour after the race?',
				'lede'   => 'Guided tours of the historic Middleburg property start at 10am.',
				'widget' => 'chips',
				'labels' => array(
					'No Tour' => 'No thanks',
					'10 AM'   => 'Yes, the 10am tour',
				),
				'solo'   => true,
			),
			array(
				'fields' => array( 61 ),
				'kicker' => '{who}',
				'title'  => 'What shirt size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
				'tip'    => 'Race shirts are only guaranteed for runners who register on or before <strong>February 22, 2027</strong>.',
			),
		),
	),

	'frames'  => array(
		array(
			'page'   => 1,
			'fields' => array( 80, 81 ),
			'kicker' => 'Your crew',
			'title'  => "Who's running?",
			'lede'   => 'Add each runner you\'re registering. You can register just yourself or a group.',
			'widget' => 'roster',
			'art'    => 'oak.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 65, 63, 66, 64, 76 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'oak.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 83, 36, 79, 82, 35 ),
			'kicker' => 'Last step',
			'title'  => 'Review and pay.',
			'widget' => 'summary',
			'art'    => 'oak.webp',
			'submit' => 'Register',
		),
	),

	'done'    => array(
		'title' => 'See you at the mill!',
		'cta'   => 'Back to race info',
	),
);
