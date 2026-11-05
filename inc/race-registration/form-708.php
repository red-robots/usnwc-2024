<?php
/**
 * Race registration experience: 2027 Ride & Seek MTB Adventure Race (Gravity Form 708).
 *
 * Shared GP Nested Forms template (see form-757.php for the 'nested' keys), but each entry of
 * child form 682 is a TEAM of two riders: race, team name and division, then both riders'
 * details. The roster lists teams by team name; each rider's screens set their own
 * 'nameField' so "{name}" is that rider. Rider 2's phone and email are hidden by GF for
 * youth teams, and GF hides the division for youth teams, so those screens drop out.
 *
 * @package bellaworks
 */

$regx_rider = function ( $n, $f ) {
	$who = '{who} · Rider ' . $n;
	return array(
		array(
			'fields'       => array( $f['name'], $f['age'], $f['gender'] ),
			'kicker'       => $who,
			'title'        => 1 === $n ? "Who's the first rider?" : "Who's the second rider?",
			'lede'         => 1 === $n ? 'Two riders per team, and they ride together the whole race.' : 'On a youth team, one rider must be 12 or younger and one must be 18 or older.',
			'nameField'    => $f['name'],
			'nameFallback' => 'this rider',
			'art'          => 'panel-trail.webp',
		),
		array(
			'fields'       => array( $f['phone'], $f['email'] ),
			'kicker'       => $who,
			'title'        => 'How do we reach {name}?',
			'lede'         => 1 === $n ? 'The pre-race email with race-week details goes here.' : '',
			'nameField'    => $f['name'],
			'nameFallback' => 'this rider',
			'art'          => 'panel-bike.webp',
		),
		array(
			'fields'       => array( $f['address'] ),
			'kicker'       => $who,
			'title'        => "What's {name}'s mailing address?",
			'solo'         => true,
			'nameField'    => $f['name'],
			'nameFallback' => 'this rider',
			'art'          => 'panel-bike.webp',
		),
		array(
			'fields'       => array( $f['ecName'], $f['ecPhone'] ),
			'kicker'       => $who . ' · Safety',
			'title'        => "Who's {name}'s emergency contact?",
			'nameField'    => $f['name'],
			'nameFallback' => 'this rider',
			'art'          => 'panel-trail.webp',
		),
		array(
			'fields'       => array( $f['shirt'] ),
			'kicker'       => $who,
			'title'        => 'What shirt size for {name}?',
			'widget'       => 'chips',
			'solo'         => true,
			'nameField'    => $f['name'],
			'nameFallback' => 'this rider',
			'tip'          => 'Race shirts are only guaranteed for riders who register on or before <strong>February 15, 2027</strong>.',
			'art'          => 'panel-seek.webp',
		),
	);
};

$regx_team_frames = array_merge(
	array(
		array(
			'fields'   => array( 1 ),
			'kicker'   => '{who}',
			'title'    => 'Which race is this team entering?',
			'widget'   => 'cards',
			'cards'    => array(
				'Ride & Seek MTB Adventure Race'       => 'Two riders hunt hidden checkpoints across the trail system and race back to HQ. Course closes at 1pm.',
				'Ride and Seek Youth Team Registration' => 'One rider 12 or younger and one 18 or older. The youth division ends at 11am.',
			),
			'solo'     => true,
			'hideDesc' => true,
			'art'      => 'panel-seek.webp',
		),
		array(
			'fields'       => array( 3, 23 ),
			'kicker'       => '{who}',
			'title'        => 'Name your team.',
			'lede'         => 'Awards go to the top three teams in each division.',
			'art'          => 'panel-bike.webp',
		),
	),
	$regx_rider( 1, array( 'name' => 29, 'age' => 8, 'gender' => 12, 'phone' => 32, 'email' => 33, 'address' => 18, 'ecName' => 35, 'ecPhone' => 38, 'shirt' => 37 ) ),
	$regx_rider( 2, array( 'name' => 5, 'age' => 30, 'gender' => 31, 'phone' => 10, 'email' => 15, 'address' => 34, 'ecName' => 19, 'ecPhone' => 39, 'shirt' => 25 ) )
);

return array(
	'enabled' => true,

	'theme'   => 'seek',
	'light'   => true,
	'fonts'   => 'https://fonts.googleapis.com/css2?family=Passion+One:wght@400;700&family=Josefin+Sans:wght@600;700&family=Nunito+Sans:wght@500;600;700;800&display=swap',
	'colors'  => array( 'bg' => '#faf2df' ),
	'raceUrl' => 'https://whitewater.org/race/ride-and-seek-mtb-adventure-race/',

	'welcome' => array(
		'logo'    => 'seek-logo.webp',
		'logoAlt' => 'Ride & Seek MTB Adventure Race, February 27, 2027',
		'title'   => 'Find every checkpoint.',
		'lede'    => 'Register your two-person team for Ride & Seek on February 27: bike the Whitewater Center trails hunting hidden checkpoints, then get back to HQ before time runs out. Pre-registration closes February 26.',
		'list'    => array(
			'A team name',
			'Both riders\' ages, contact info and shirt sizes',
			'A card: $80 per team, or $60 for a youth team',
		),
		'cta'     => 'Start registration',
	),

	'nested'  => array(
		'field'        => 40,
		'formId'       => 682,
		'controlled'   => array( 46 ),
		'nameField'    => 3,
		'nameFallback' => 'your team',
		'productField' => 1,
		'productNames' => array(
			'Ride & Seek MTB Adventure Race'        => 'Adventure race',
			'Ride and Seek Youth Team Registration' => 'Youth team',
		),
		'singular'     => 'team',
		'min'          => 1,
		'addFirst'     => 'Add your team',
		'addAnother'   => 'Add another team',
		'addLabel'     => 'Add team',
		'saveLabel'    => 'Save team',
		'minMessage'   => 'Add at least one team to keep going.',
		'art'          => 'panel-bike.webp',
		'frames'       => $regx_team_frames,
	),

	'frames'  => array(
		array(
			'page'   => 1,
			'fields' => array( 40, 46 ),
			'kicker' => 'Your teams',
			'title'  => "Who's racing?",
			'lede'   => 'Ride & Seek is raced in teams of two. Add each team you\'re registering; you\'ll enter both riders for each.',
			'widget' => 'roster',
			'art'    => 'panel-seek.webp',
		),
		array(
			'page'   => 2,
			'fields' => array( 31, 30, 32, 33, 34 ),
			'kicker' => 'The fine print',
			'title'  => 'A few things to agree to.',
			'lede'   => 'Check each one to confirm you understand it.',
			'art'    => 'panel-trail.webp',
		),
		array(
			'page'   => 3,
			'fields' => array( 51, 35, 37, 50, 49 ),
			'kicker' => 'Last step',
			'title'  => 'Review and pay.',
			'widget' => 'summary',
			'art'    => 'panel-bike.webp',
			'submit' => 'Register',
		),
	),

	'done'    => array(
		'title' => 'Happy hunting!',
		'cta'   => 'Back to race info',
	),
);
