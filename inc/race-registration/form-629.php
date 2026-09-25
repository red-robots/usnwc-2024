<?php
/**
 * Race registration experience: 2026 Build Your Own Boat (Gravity Form 629).
 *
 * Frames are the screens the visitor steps through, in order. Each one lists
 * the Gravity Forms field IDs it shows, and the GF page they live on. Any field
 * on a page that no frame claims still gets a screen of its own (titled with
 * its label), so adding a field in the form editor can't make it unreachable.
 *
 * Frame keys:
 *  page     GF page number (1-based)
 *  fields   field IDs shown on this screen
 *  kicker   small label above the title
 *  title    the question
 *  lede     optional sentence under the title
 *  tip      optional callout (HTML allowed)
 *  art      image in assets/race-registration/{theme}/ shown beside the question
 *  widget   optional custom control: cards | slots | shirts | summary
 *  hideDesc hide the GF field descriptions on this screen
 *  solo     hide the field label (the title already asks the question)
 *
 * @package bellaworks
 */

return array(
	// Off for everyone until it's been tried on live with ?regx=1.
	'enabled' => false,

	'theme'   => 'byob',
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@500;600;700;800&display=swap',
	'colors'  => array( 'bg' => '#052124' ),
	'raceUrl' => 'https://whitewater.org/race/build-your-own-boat-competition/',

	'welcome' => array(
		'logo'    => 'byob-logo.webp',
		'logoAlt' => 'Build Your Own Boat — October 10, 2026',
		'title'   => 'Launch your crew.',
		'lede'    => 'Register your team for Build Your Own Boat at the U.S. National Whitewater Center. It takes about five minutes.',
		'list'    => array(
			'A team name and an intro song',
			'Captain and emergency contact info',
			'A card for the $50 team registration',
		),
		'cta'     => "Let's build",
	),

	'frames'  => array(
		array(
			'page'     => 1,
			'fields'   => array( 5 ),
			'kicker'   => 'Your team',
			'title'    => 'Which division are you racing in?',
			'widget'   => 'cards',
			'hideDesc' => true,
			'solo'     => true,
			'art'      => 'raft-a.webp',
			'cards'    => array(
				'Open Division'      => 'Open to the general public.',
				'Corporate Division' => 'For businesses and organizations.',
				'School Division'    => 'For K-12 schools.',
			),
			'tip'      => 'Everyone riding in the boat must be 16 or older.',
		),
		array(
			'page'   => 1,
			'fields' => array( 6 ),
			'kicker' => 'Your team',
			'title'  => "What's your team called?",
			'lede'   => "This is the name the announcer calls when you hit the water.",
			'solo'   => true,
			'art'    => 'canoe-a.webp',
		),
		array(
			'page'     => 1,
			'fields'   => array( 14 ),
			'kicker'   => 'Your team',
			'title'    => 'Pick your launch song.',
			'lede'     => "It plays during your team's run down the whitewater channel.",
			'hideDesc' => true,
			'solo'     => true,
			'art'      => 'raft-b.webp',
			'tip'      => 'Type <strong>Song Title – Artist</strong> or paste a Spotify link. We\'re a family-friendly facility, so keep it clean.',
		),
		array(
			'page'     => 1,
			'fields'   => array( 50 ),
			'kicker'   => 'Float Test',
			'title'    => 'When will your crew do the Float Test?',
			'lede'     => 'The Float Test is mandatory for everyone on your team. It covers a safety briefing, a swim test, and a boat assessment.',
			'widget'   => 'slots',
			'hideDesc' => true,
			'solo'     => true,
			'art'      => 'canoe-b.webp',
		),
		array(
			'page'   => 1,
			'fields' => array( 36, 37, 30, 32, 33, 34, 35, 40 ),
			'kicker' => 'Swag',
			'title'  => 'Want BYOB participant shirts?',
			'lede'   => '$22 + tax each. Pick as many as your crew needs.',
			'widget' => 'shirts',
			'solo'   => true,
			'art'    => 'raft-a.webp',
			'shirts' => array(
				'toggle' => 36,
				'sizes'  => 37,
				'ack'    => 40,
				'map'    => array(
					'Small'    => 30,
					'Medium'   => 32,
					'Large'    => 33,
					'X-Large'  => 34,
					'XX-Large' => 35,
				),
			),
		),
		array(
			'page'   => 2,
			'fields' => array( 8, 9 ),
			'kicker' => 'Team captain',
			'title'  => "Who's captaining this ship?",
			'lede'   => "We'll send your confirmation and race-week updates to the captain.",
			'art'    => 'canoe-a.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 12, 11 ),
			'kicker' => 'Team captain',
			'title'  => 'How do we reach you?',
			'art'    => 'raft-b.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 15 ),
			'kicker' => 'Team captain',
			'title'  => "What's your mailing address?",
			'solo'   => true,
			'art'    => 'canoe-b.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 24, 25 ),
			'kicker' => 'Safety first',
			'title'  => 'Who should we call in an emergency?',
			'lede'   => "Pick someone who won't be in the boat with you.",
			'art'    => 'raft-a.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 17, 18, 19, 21, 41, 20, 42 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'canoe-a.webp',
		),
		array(
			'page'     => 4,
			'fields'   => array( 26, 43, 44, 22 ),
			'kicker'   => 'Last step',
			'title'    => 'Review and pay.',
			'widget'   => 'summary',
			'art'      => 'raft-b.webp',
			'recap'    => array(
				array( 'label' => 'Team', 'field' => 6 ),
				array( 'label' => 'Division', 'field' => 5 ),
				array( 'label' => 'Float Test', 'field' => 50 ),
				array( 'label' => 'Launch song', 'field' => 14 ),
				array( 'label' => 'Captain', 'field' => 8 ),
			),
			'submit'   => 'Register my team',
		),
	),

	'done'    => array(
		'title' => "You're in!",
		'cta'   => 'Back to race info',
	),
);
