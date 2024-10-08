<?php
/**
 * View: Month View - Single Multiday Event Hidden Title
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/month/calendar-body/day/multiday-events/multiday-event/hidden/link.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @since 5.1.1
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 *
 * @version 5.1.1
 */

?>
<div style="display:none">
<a
	href="<?php echo esc_url( $event->permalink ); ?>"
	class="tribe-events-calendar-month__multiday-event-hidden-link"
	data-js="tribe-events-tooltip"
	data-tooltip-content="#tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
	aria-describedby="tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
>
	<?php $this->template( 'month/calendar-body/day/multiday-events/multiday-event/hidden/link/featured', [ 'event' => $event ] ); ?>
	<?php $this->template( 'month/calendar-body/day/multiday-events/multiday-event/hidden/link/title', [ 'event' => $event ] ); ?>
</a>
</div>

<?php  
  $slug_name = sanitize_title($event->post_title);
  $is_hide = ($slug_name=='hours-of-operation') ? ' hide':'';
?>

<div class="custom-event-info" data-event-name="<?php echo esc_attr( $event->post_title ); ?>" data-event-id="<?php echo esc_attr( $event->ID ); ?>">
  <?php $this->template( 'month/calendar-body/day/multiday-events/multiday-event/hidden/link/featured', [ 'event' => $event ] ); ?>
  <?php $this->template( 'month/calendar-body/day/multiday-events/multiday-event/hidden/link/title', [ 'event' => $event ] ); ?>
</div>
