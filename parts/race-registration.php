<?php
/**
 * Race registration experience shell (see inc/race-registration.php).
 *
 * The site header and footer still load (analytics, scripts), but the
 * stylesheet hides them so the registration fills the screen.
 *
 * @package bellaworks
 */

$regx   = bellaworks_regx_current();
$assets = get_template_directory_uri() . '/assets/race-registration/' . sanitize_file_name( $regx['theme'] ) . '/';

get_header();
?>

<div class="rx" id="rx" data-state="loading">

	<div class="rx-bg" aria-hidden="true">
		<svg class="rx-contours" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
			<path class="c1" d="M-40 170 C 120 120, 210 260, 330 250 S 470 60, 600 -40" />
			<path class="c2" d="M-40 520 C 90 470, 150 330, 250 380 S 330 640, 470 700 S 560 860, 520 960" />
			<path class="c3" d="M1480 120 C 1320 150, 1290 300, 1190 260 S 1120 40, 1010 -40" />
			<path class="c4" d="M1480 560 C 1380 520, 1300 420, 1250 520 S 1330 760, 1200 800 S 1080 900, 1060 960" />
			<path class="c5" d="M1480 360 C 1400 330, 1350 390, 1360 450 S 1450 520, 1480 500" />
			<circle class="b1" cx="420" cy="80" r="16" />
			<circle class="b2" cx="150" cy="460" r="26" />
			<circle class="b3" cx="1340" cy="170" r="30" />
			<circle class="b4" cx="1130" cy="820" r="18" />
			<circle class="b5" cx="480" cy="760" r="24" />
			<circle class="b6" cx="1395" cy="700" r="12" />
		</svg>
	</div>

	<header class="rx-top">
		<a class="rx-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-white.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="150" height="40">
		</a>
		<div class="rx-progress-pct" aria-live="polite"></div>
		<div class="rx-top-right">
			<div class="rx-total" id="rx-total" aria-live="polite" hidden>
				<span class="rx-total-label">Total</span>
				<span class="rx-total-value">$0.00</span>
			</div>
			<?php if ( ! empty( $regx['raceUrl'] ) ) : ?>
				<a class="rx-exit" href="<?php echo esc_url( $regx['raceUrl'] ); ?>">Race info</a>
			<?php endif; ?>
		</div>
	</header>

	<div class="rx-progress" id="rx-progress" aria-hidden="true">
		<div class="rx-progress-fill"></div>
	</div>

	<main class="rx-stage" id="rx-stage">

		<?php if ( ! empty( $regx['welcome'] ) ) : $w = $regx['welcome']; ?>
		<section class="rx-welcome" id="rx-welcome" hidden>
			<div class="rx-welcome-inner">
				<?php if ( ! empty( $w['logo'] ) ) : ?>
					<img class="rx-welcome-logo rx-pop" style="--d:0" src="<?php echo esc_url( $assets . $w['logo'] ); ?>" alt="<?php echo esc_attr( $w['logoAlt'] ?? '' ); ?>">
				<?php endif; ?>
				<div class="rx-welcome-copy">
					<h1 class="rx-h1 rx-pop" style="--d:1"><?php echo esc_html( $w['title'] ); ?></h1>
					<p class="rx-lede rx-pop" style="--d:2"><?php echo esc_html( $w['lede'] ); ?></p>
					<?php if ( ! empty( $w['list'] ) ) : ?>
						<div class="rx-ready rx-pop" style="--d:3">
							<div class="rx-ready-title">Have these handy</div>
							<ul>
								<?php foreach ( $w['list'] as $item ) : ?>
									<li><?php echo esc_html( $item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
					<button type="button" class="rx-btn rx-pop" style="--d:4" id="rx-start">
						<?php echo esc_html( $w['cta'] ?? 'Start' ); ?> <span class="rx-arrow" aria-hidden="true">&rarr;</span>
					</button>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<section class="rx-frame" id="rx-frame">
			<div class="rx-frame-inner">
				<div class="rx-art" aria-hidden="true"><img alt="" src=""></div>
				<div class="rx-copy">
					<div class="rx-head" id="rx-head" tabindex="-1">
						<div class="rx-kicker"></div>
						<h2 class="rx-h2"></h2>
						<p class="rx-lede"></p>
					</div>
					<div class="rx-widget" id="rx-widget"></div>

					<?php while ( have_posts() ) : the_post(); ?>
						<div class="rx-form">
							<?php the_content(); ?>
						</div>
					<?php endwhile; ?>

					<div class="rx-tip" id="rx-tip" hidden>
						<svg class="rx-tip-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-4.9 12 6.9 6.9 0 0 1 1.6 2.6l.1.4h6.4l.1-.4a6.9 6.9 0 0 1 1.6-2.6A7 7 0 0 0 12 2zm-2.5 17a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-.5h-5v.5zm1.5 3a1.5 1.5 0 0 0 2 0 1.6 1.6 0 0 0 .4-1h-2.8c0 .4.1.7.4 1z"/></svg>
						<div class="rx-tip-body"></div>
					</div>

					<div class="rx-error" id="rx-error" role="alert" hidden></div>

					<div class="rx-nav" id="rx-nav">
						<button type="button" class="rx-back" id="rx-back">&larr; Back</button>
						<button type="button" class="rx-btn" id="rx-next">
							<span class="rx-next-label">Continue</span> <span class="rx-arrow" aria-hidden="true">&rarr;</span>
						</button>
					</div>
				</div>
			</div>
		</section>

		<section class="rx-done" id="rx-done" hidden>
			<div class="rx-done-inner">
				<?php if ( ! empty( $regx['welcome']['logo'] ) ) : ?>
					<img class="rx-done-logo rx-pop" style="--d:0" src="<?php echo esc_url( $assets . $regx['welcome']['logo'] ); ?>" alt="<?php echo esc_attr( $regx['welcome']['logoAlt'] ?? '' ); ?>">
				<?php endif; ?>
				<h1 class="rx-h1 rx-pop" style="--d:1"><?php echo esc_html( $regx['done']['title'] ?? 'Thank you!' ); ?></h1>
				<div class="rx-done-message rx-pop" style="--d:2" id="rx-done-message"></div>
				<?php if ( ! empty( $regx['raceUrl'] ) ) : ?>
					<a class="rx-btn rx-pop" style="--d:3" href="<?php echo esc_url( $regx['raceUrl'] ); ?>">
						<?php echo esc_html( $regx['done']['cta'] ?? 'Back to race info' ); ?> <span class="rx-arrow" aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
				<a class="rx-done-home rx-pop" style="--d:4" href="<?php echo esc_url( home_url( '/' ) ); ?>">Go to whitewater.org</a>
			</div>
		</section>

	</main>
</div>

<?php
get_footer();
