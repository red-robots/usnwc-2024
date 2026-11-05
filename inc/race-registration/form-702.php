<?php
/**
 * Race registration experience: 2027 Dog Days of Winter Dog Jog 5K & 1 Mile Walk (Gravity Form 702).
 *
 * Shared GP Nested Forms template (see form-757.php for the 'nested' keys): runners are entries
 * of child form 701, each with a dog (and optionally a second dog), a shirt and an optional hoodie.
 *
 * The child form asks "Pace Per Mile" twice (fields 101 and 119, both required, no logic):
 * 'mirror' asks it once and copies the answer into 119. Delete 119 in Gravity Forms and this
 * can go.
 *
 * @package bellaworks
 */

return array(
	'enabled' => true,

	'theme'   => 'dogdays',
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Jost:wght@500;600;700&display=swap',
	'colors'  => array( 'bg' => '#112b22' ),
	'raceUrl' => 'https://whitewater.org/race/dog-days-of-winter-dog-jog/',

	'welcome' => array(
		'logo'    => 'dogdays-logo.webp',
		'logoAlt' => 'Dog Days of Winter Dog Jog 5K and 1 Mile Walk, February 6, 2027',
		'title'   => 'Run with your pup.',
		'lede'    => 'Register for the Dog Jog 5K or 1 Mile Walk at Dog Days of Winter on February 6, then stick around for adoption events, clinics and doggy vendors. Pre-registration closes February 4.',
		'list'    => array(
			'Each runner\'s age, pace and shirt size',
			'Your dog\'s name (and a second dog\'s, if you\'re bringing two)',
			'A card: $42 for the 5K, $25 for the walk',
		),
		'cta'     => 'Start registration',
	),

	'nested'  => array(
		'field'        => 106,
		'formId'       => 701,
		'controlled'   => array( 107 ),
		'nameField'    => 20,
		'productField' => 12,
		'addons'       => array( 114 => 'hoodie' ),
		'mirror'       => array( 119 => 101 ),
		'singular'     => 'runner',
		'min'          => 1,
		'addFirst'     => 'Add your first runner',
		'addAnother'   => 'Add another runner',
		'addLabel'     => 'Add runner',
		'saveLabel'    => 'Save runner',
		'minMessage'   => 'Add at least one runner to keep going.',
		'art'          => 'dog-hat.webp',
		'frames'       => array(
			array(
				'fields' => array( 20, 27, 29 ),
				'kicker' => '{who}',
				'title'  => "Who's running?",
				'lede'   => 'The top three men and women overall in the 5K get awards.',
				'art'    => 'dog-hat.webp',
			),
			array(
				'fields' => array( 12 ),
				'kicker' => '{who}',
				'title'  => 'Which race is {name} doing?',
				'widget' => 'cards',
				'cards'  => array(
					'5k Dog Jog'      => '3.1 miles of trail with your dog. Mass start at 10am.',
					'1 Mile Dog Walk' => 'One mile on the trail with your dog.',
				),
				'solo'   => true,
				'art'    => 'dog-flag.webp',
			),
			array(
				'fields' => array( 69, 95, 115, 116 ),
				'kicker' => '{who} · The pup',
				'title'  => "Who's {name} bringing?",
				'lede'   => 'Dogs stay on a leash the whole time. Bringing two? Add the second one too.',
				'art'    => 'dog-scarf.webp',
			),
			array(
				'fields' => array( 101 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s pace per mile?",
				'lede'   => 'On trail, which runs slower than road.',
				'widget' => 'chips',
				'solo'   => true,
				'art'    => 'dog-bone.webp',
			),
			array(
				'fields' => array( 117, 33 ),
				'kicker' => '{who}',
				'title'  => 'How do we reach {name}?',
				'lede'   => 'The pre-race email with race-week details goes here.',
				'art'    => 'dog-hat.webp',
			),
			array(
				'fields' => array( 41 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s mailing address?",
				'solo'   => true,
				'art'    => 'dog-flag.webp',
			),
			array(
				'fields' => array( 51, 118 ),
				'kicker' => '{who} · Safety',
				'title'  => "Who's {name}'s emergency contact?",
				'art'    => 'dog-scarf.webp',
			),
			array(
				'fields' => array( 53 ),
				'kicker' => '{who}',
				'title'  => 'What shirt size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
				'tip'    => 'Race shirts are only guaranteed for runners who register on or before <strong>January 25, 2027</strong>.',
				'art'    => 'dog-bone.webp',
			),
			array(
				'fields' => array( 114 ),
				'kicker' => '{who}',
				'title'  => 'Add a race hoodie for {name}?',
				'widget' => 'cards',
				'cards'  => array(
					'Yes' => 'This year\'s Dog Days hoodie, $45 plus tax.',
					'No'  => 'Skip the hoodie.',
				),
				'solo'   => true,
				'tip'    => 'Hoodies are only guaranteed for runners who register on or before <strong>January 25, 2027</strong>.',
				'art'    => 'dog-hat.webp',
			),
			array(
				'fields' => array( 112, 113 ),
				'kicker' => '{who}',
				'title'  => 'What hoodie size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
				'art'    => 'dog-hat.webp',
			),
		),
	),

	'frames'  => array(
		array(
			'page'   => 1,
			'fields' => array( 106, 107 ),
			'kicker' => 'Your pack',
			'title'  => "Who's running?",
			'lede'   => 'Add each person you\'re registering. Their dogs come along on each runner\'s screens.',
			'widget' => 'roster',
			'art'    => 'dog-flag.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 57, 58, 59, 60, 61 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'dog-scarf.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 113, 63, 99, 111, 62 ),
			'kicker' => 'Last step',
			'title'  => 'Review and pay.',
			'widget' => 'summary',
			'art'    => 'dog-bone.webp',
			'submit' => 'Register',
		),
	),

	'done'    => array(
		'title' => 'Good dog. You\'re in!',
		'cta'   => 'Back to race info',
	),
);
