<?php
/**
 * View: Month Calendar Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/month/calendar-body/day/calendar-events/calendar-event.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @since 5.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

$classes = tribe_get_post_class( [ 'tribe-events-calendar-month__calendar-event' ], $event->ID );

$classes['tribe-events-calendar-month__calendar-event--featured'] = ! empty( $event->featured );
$classes['tribe-events-calendar-month__calendar-event--sticky']   = ( -1 === $event->menu_order );

$event_post_slug = $event->post_name;
$startDateNoTime = ($event->start_date) ? date('Y-m-d', strtotime($event->start_date)) : '';
$startDate = ($event->start_date) ? $event->start_date : '';
$series = [];
$is_child_series = false;
$is_parent = '';
$is_parent_count = 0;
$post_slug_class = $event->post_name;
$notSeries = [];
if($startDate) {
  $post_slug = str_replace('-'.$startDateNoTime,'',$event_post_slug); 
  $post_slug_class = $post_slug;
  $pervious_date  =  date('Y-m-d', strtotime($startDateNoTime . ' - 1 days')); 
  // echo "<pre style='font-size:11px'>";
  // print_r($pervious_date);
  // echo "</pre>";

  $prev_post_slug = $post_slug .'-'.$pervious_date;
  if($row = getDataBySlug($prev_post_slug)) {
    //Do nothing...
    $is_child_series = true;
  } else {
    for($i=1; $i<=8; $i++) {
      //$new_date  = (new DateTime($startDate))->add(new DateInterval('P'.$i.'D'))->format('Y-m-d');
      $new_date  =  date('Y-m-d', strtotime($startDateNoTime . ' + '.$i.' days')); 
      $post_slug_date = $post_slug .'-'.$new_date;

      if($row = getDataBySlug($post_slug_date)) {
        $series[] = $i;
      } else {
        $notSeries[] = $i;
      }
    }
  }
}
?>

<?php if ($series) { ?>
  <article data-isParent="<?php echo ($series && count($series)>1) ? 'true':'' ?>" data-child-count="<?php echo ($series) ? count($series):'' ?>" dataSlug="<?php echo $post_slug_class ?>" <?php tribe_classes( $classes ) ?>>
<?php } else { ?>
  <?php if ($is_child_series) { ?>
    <article data-child-series="<?php echo $event->post_name ?>" dataSlug="<?php echo $post_slug_class ?>" <?php tribe_classes( $classes ) ?>>
  <?php } else { ?>
    <article dataSlug="<?php echo $post_slug_class ?>" <?php tribe_classes( $classes ) ?>>
  <?php } ?>
<?php } ?>


	<?php $this->template( 'month/calendar-body/day/calendar-events/calendar-event/featured-image', [ 'event' => $event ] ); ?>

	<div class="tribe-events-calendar-month__calendar-event-details">

		<?php //$this->template( 'month/calendar-body/day/calendar-events/calendar-event/date', [ 'event' => $event ] ); ?>
		<?php $this->template( 'month/calendar-body/day/calendar-events/calendar-event/title', [ 'event' => $event ] ); ?>

		<?php //$this->template( 'month/calendar-body/day/calendar-events/calendar-event/tooltip', [ 'event' => $event ] ); ?>

	</div>

</article>

