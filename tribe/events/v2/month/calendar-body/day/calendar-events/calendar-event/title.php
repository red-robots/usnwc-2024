<?php
/**
 * View: Month View - Calendar Event Title
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/month/calendar-body/day/calendar-events/calendar-event/title.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTICLE_LINK_HERE}
 *
 * @version 5.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

?>
<h3 data-actual-link="<?php echo esc_url( $event->permalink ); ?>" data-slug="<?php echo sanitize_title($event->title); ?>" data-event-id="<?php echo esc_attr( $event->ID ); ?>" class="tribe-events-calendar-month__calendar-event-title tribe-common-h8 tribe-common-h--alt">
	<?php  
  $tribeEventLink = tribe_get_event_website_url($event);
  $eventLink = ($tribeEventLink) ? $tribeEventLink : '';
  ?>
  <?php if ($tribeEventLink) { ?>
  <a href="<?php echo $tribeEventLink ?>" rel="bookmark" class="eventName hasEventLink"><?php echo $event->title; ?></a>
  <?php } else { ?>
  <span class="eventName noEventLink"><?php echo $event->title; ?></span>
  <?php } ?>
</h3>


