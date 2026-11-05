<?php
/**
 * Race registration experience: 2026 A Shot in the Dark, Charlotte (Gravity Form 757).
 *
 * See form-629.php for what the frame keys mean. This is the first race built on the
 * shared GP Nested Forms template: the parent form (757) only holds the runners field,
 * the agreements and payment, and each runner is an entry of the child form (654),
 * added through GPNF's modal.
 *
 * 'nested' describes that field:
 *  field         the Nested Form field on the parent (its page-1 frame uses widget 'roster')
 *  formId        the child form; read from the field at runtime, so this is only a fallback
 *                (a duplicated parent pointing at a duplicated child still works)
 *  controlled    parent fields the roster stands in for (the calculated Sub-Total product);
 *                parked, never shown, and left out of the order summary
 *  nameField     child Name field: roster cards, {name} in child frames, summary lines
 *  productField  child product the summary and the cards show next to each name
 *  singular      what one entry is called ("runner")
 *  min           entries needed before Continue works (match the field's minimum)
 *  art           image beside the questions in the modal
 *  frames        the child form's screens; same keys as page frames, without 'page'.
 *                {who} in a kicker becomes "Runner 2" (or "Editing runner 2")
 *
 * @package bellaworks
 */

return array(
	'enabled' => true,

	'theme'   => 'sitd',
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Saira+Stencil+One&family=Barlow+Condensed:wght@600;700&family=Barlow:wght@500;600;700&display=swap',
	'colors'  => array( 'bg' => '#070109' ),
	'raceUrl' => 'https://whitewater.org/race/a-shot-in-the-dark-night-trail-race/',

	'welcome' => array(
		'logo'    => 'sitd-logo.webp',
		'logoAlt' => 'Shot in the Dark: Half Marathon, 10K and 5K, November 13, 2026, Charlotte, NC',
		'title'   => 'Charge your headlamp.',
		'lede'    => 'Register for A Shot in the Dark, the night trail race at the U.S. National Whitewater Center. Sign up just yourself or your whole crew; it takes about two minutes per runner.',
		'list'    => array(
			'Each runner\'s age, pace and shirt size',
			'An emergency contact for each runner',
			'A card: $45 for the 5K, $55 for the 10K, $80 for the half',
		),
		'cta'     => 'Start registration',
	),

	'nested'  => array(
		'field'        => 139,
		'formId'       => 654,
		'controlled'   => array( 140 ),
		'nameField'    => 5,
		'productField' => 4,
		'singular'     => 'runner',
		'min'          => 1,
		'addFirst'     => 'Add your first runner',
		'addAnother'   => 'Add another runner',
		'addLabel'     => 'Add runner',
		'saveLabel'    => 'Save runner',
		'minMessage'   => 'Add at least one runner to keep going.',
		'art'          => 'disco-ball.webp',
		'frames'       => array(
			array(
				'fields' => array( 5, 7, 6 ),
				'kicker' => '{who}',
				'title'  => "Who's running?",
				'lede'   => 'Awards go to the top three men and women in each age group.',
			),
			array(
				'fields'    => array( 4 ),
				'kicker'    => '{who}',
				'title'     => 'Which distance is {name} running?',
				'widget'    => 'cards',
				'cards'     => array(
					'5k'            => '3.1 miles of trail by headlamp.',
					'10k'           => '6.2 miles of trail by headlamp.',
					'Half Marathon' => '13.1 miles of trail by headlamp.',
				),
				'solo'      => true,
				'tip'       => 'Every runner needs a headlamp or other light on course.',
			),
			array(
				'fields' => array( 129 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s pace per mile?",
				'lede'   => 'On singletrack trail, which runs slower than road.',
				'widget' => 'chips',
				'solo'   => true,
			),
			array(
				'fields' => array( 9, 8 ),
				'kicker' => '{who}',
				'title'  => 'How do we reach {name}?',
				'lede'   => 'The pre-race email with race-week details goes here.',
			),
			array(
				'fields' => array( 10 ),
				'kicker' => '{who}',
				'title'  => "What's {name}'s mailing address?",
				'solo'   => true,
			),
			array(
				'fields' => array( 11, 12 ),
				'kicker' => '{who} · Safety',
				'title'  => "Who's {name}'s emergency contact?",
				'lede'   => 'Someone who isn\'t running with {name}.',
			),
			array(
				'fields' => array( 13 ),
				'kicker' => '{who}',
				'title'  => 'What shirt size for {name}?',
				'widget' => 'chips',
				'solo'   => true,
				'tip'    => 'Race shirts are only guaranteed for runners who registered on or before <strong>November 2, 2026</strong>, so sizes may run out.',
			),
		),
	),

	'frames'  => array(
		array(
			'page'   => 1,
			'fields' => array( 139, 140 ),
			'kicker' => 'Your crew',
			'title'  => "Who's running?",
			'lede'   => 'Add each runner you\'re registering. You can register just yourself or a group.',
			'widget' => 'roster',
			'art'    => 'disco-ball.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 102, 101, 100, 99, 118, 138 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'disco-ball.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 120, 136, 137, 119 ),
			'kicker' => 'Last step',
			'title'  => 'Review and pay.',
			'widget' => 'summary',
			'art'    => 'disco-ball.webp',
			'submit' => 'Register',
		),
	),

	'done'    => array(
		'title' => "You're in!",
		'cta'   => 'Back to race info',
	),
);
