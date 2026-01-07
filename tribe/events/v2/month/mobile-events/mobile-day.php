<?php
/**
 * View: Month View Mobile Day
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/month/mobile-events/mobile-day.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 4.9.10
 *
 * @var string $today_date Today's date in the `Y-m-d` format.
 * @var string $day_date The current day date, in the `Y-m-d` format.
 * @var array  $day The current day data.{
 *          @type string $date The day date, in the `Y-m-d` format.
 *          @type bool $is_start_of_week Whether the current day is the first day of the week or not.
 *          @type string $year_number The day year number, e.g. `2019`.
 *          @type string $month_number The day year number, e.g. `6` for June.
 *          @type string $day_number The day number in the month, e.g. `11` for June 11th.
 *          @type string $day_number_no_pad The day number in the month without leading 0, e.g. `8` for June 8th.
 *          @type string $day_url The day url, e.g. `http://yoursite.com/events/2019-06-11/`.
 *          @type int $found_events The total number of events in the day including the ones not fetched due to the per
 *                                  page limit, including the multi-day ones.
 *          @type int $more_events The number of events not showing in the day.
 *          @type array $events The non multi-day events on this day. The format of each event is the one returned by
 *                    the `tribe_get_event` function. Does not include the below events.
 *          @type array $featured_events The featured events on this day. The format of each event is the one returned
 *                    by the `tribe_get_event` function.
 *          @type array $multiday_events The stack of multi-day events on this day. The stack is a mix of event post
 *                              objects, the format is the one returned from the `tribe_get_event` function, and
 *                              spacers. Spacers are falsy values indicating an empty space in the multi-day stack for
 *                              the day
 *      }
 * @var array  $mobile_messages A set of mobile messages that will be used to handle the user interaction in mobile.
 */

use Tribe__Date_Utils as Dates;

$events = ! empty( $day['events'] ) ? $day['events'] : [];
if ( ! empty( $day['multiday_events'] ) ) {
	$events = array_filter( array_merge( $day['multiday_events'], $events ) );
}
$mobile_day_id = 'tribe-events-calendar-mobile-day-' . $day['year_number'] . '-' . $day['month_number'] . '-' . $day['day_number'];

$classes = [ 'tribe-events-calendar-month-mobile-events__mobile-day' ];

if ( $today_date === $day_date ) {
	$classes[] = 'tribe-events-calendar-month-mobile-events__mobile-day--show';
}

$show_content = false;
?>


<div <?php tribe_classes( $classes ); ?> id="<?php echo sanitize_html_class( $mobile_day_id ); ?>">
  
  	<?php if ( count($events) ) : ?>

  		<?php foreach ( $events as $event ) : ?>
        <?php  
          $event_title = $event->post_title;
          $event_slug = $event->post_name;
          $is_hours_operation = (strpos($event_slug, 'hours-of-operation') !== false) ? true : false;
          $event_content = $event->post_content;
          $event_content = ($event_content) ? apply_filters('the_content', $event_content) : '';
        ?>
  			<?php $event_date = $event->dates->start->format( Dates::DBDATEFORMAT ); ?>

  			<?php $this->template( 'month/mobile-events/mobile-day/day-marker', [ 'events' => $events, 'event' => $event, 'request_date' => $day_date ] ); ?>

  			<?php $this->setup_postdata( $event ); ?>

        <?php if($is_hours_operation) { ?>
          <?php if($event_content) { 
            $metaExtra = ['passactivities', 'waterrelease'];
            $newContent = '';
            //This function is found in hours generator plugin
            if (function_exists('getHoursGeneratorPlaces')) {
              $hrgen = getHoursGeneratorPlaces();
              $the_places = (isset($hrgen['places']) && $hrgen['places']) ? $hrgen['places'] : '';
              if($the_places) {
                foreach($metaExtra as $mx) {
                  if( isset($the_places[$mx]) && $the_places[$mx] ) {
                    $placeName = $the_places[$mx];
                    $placeNameStr = strtolower($placeName);
                    $placeSlug = $mx;
                    $metaxhours = get_post_meta($event->ID, $placeSlug.'_hours',true);
//                     if (strpos( strtolower($event_content), $placeNameStr) == false) {
//                       if($metaxhours) {
//                         $newContent .= '<p><strong>'.$placeName.'</strong><br><em>'.$metaxhours.'</em></p>';
//                       }
//                     }
                  }
                }
              }
            }
            if($newContent) {
              $event_content = $newContent . $event_content;
            }

            ?>
  		   <div class="hours-of-operation-mobile">
              <?php echo $event_content ?>
           </div>
			<?php  
				$activityLink = '';
				$activityScheduleId = getActivityScheduleIdByDate($day['date']);
				$activityLink = '';
				$compareDate = strtotime($day['date']);
				$todayDate = strtotime( date('Ymd') );

				if($activityScheduleId) {
				  $dateInfo = date('Ymd', strtotime($day['date']));
				  if($compareDate >= $todayDate) {
					$activityLink = get_site_url() . '/daily-activity-schedule/?date=' . $dateInfo;
				  }
				}
			  ?>

			<?php if ($activityLink) { ?>
			<div class="activity-schedule-link is--mobile is--mobile-day" style="padding-top:0;padding-bottom:0;margin-bottom:30px;">
			  <a href="<?php echo $activityLink ?>" class="button">View Activity Schedule</a>
			</div>
			<?php } ?>
	
          <?php } ?>

        <?php } else { ?>
          <?php $this->template( 'month/mobile-events/mobile-day/mobile-event', [ 'event' => $event ] ); ?>
        <?php } ?>


  		<?php endforeach; ?>

  		<?php $this->template( 'month/mobile-events/mobile-day/more-events', [ 'more_events' => $day['more_events'], 'more_url' => $day['day_url'] ] ); ?>

  	<?php else : ?>

  		<?php
  		$this->template(
  			'components/messages',
  			[
  				'classes' => [ 'tribe-events-header__messages--mobile', 'tribe-events-header__messages--day' ],
  				'messages' => $mobile_messages,
  			]
  		);
  		?>

  	<?php endif; ?>

</div>
