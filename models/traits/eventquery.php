<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Calendar, a Wordpress plugin.                  *
 *    Copyright (C) 2014  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


require_once(RPBCALENDAR_ABSPATH . 'models/traits/event.php');


/**
 * Fetch the events that meet a certain set of criterias.
 */
class RPBCalendarTraitEventQuery extends RPBCalendarTraitEvent
{
	private $query;
	private $atLeastOneEvent;


	/**
	 * Constructor.
	 *
	 * @param array $where Selection criterias:
	 *
	 *  - `$where['time_frame_begin']`: only the events that end at or after this date,
	 *  - `$where['time_frame_end']`: only the events that start before or at this date.
	 */
	public function __construct($where)
	{
		// Query default arguments.
		$args = array(
			'post_type'   => 'rpbevent',
			'post_status' => 'publish',
			'nopaging'    => true
		);
		$dateBeginSubQuery = array('key' => 'rpbevent_date_begin');
		$dateEndSubQuery   = array('key' => 'rpbevent_date_end'  );

		// Filter: only the event corresponding to the given ID.
		if(isset($where['id'])) {
			$args['p'] = $where['id'];
		}

		// Filter: only the events within a given time frame.
		if(isset($where['time_frame_end'])) {
			$dateBeginSubQuery['value'  ] = $where['time_frame_end'];
			$dateBeginSubQuery['compare'] = '<=';
		}
		if(isset($where['time_frame_begin'])) {
			$dateEndSubQuery['value'  ] = $where['time_frame_begin'];
			$dateEndSubQuery['compare'] = '>=';
		}

		// Create the WP_Query object.
		add_filter('posts_orderby', array(__CLASS__, 'customOrderBy'));
		$args['meta_query'] = array($dateBeginSubQuery, $dateEndSubQuery);
		$this->query = new WP_Query($args);
		remove_filter('posts_orderby', array(__CLASS__, 'customOrderBy'));

		// At least one event founded?
		$this->atLeastOneEvent = $this->query->have_posts();
	}


	/**
	 * Return the ORDER BY clause to use for the query.
	 *
	 *  @return string
	 */
	public static function customOrderBy()
	{
		global $wpdb;
		return "$wpdb->postmeta.meta_value ASC, mt1.meta_value DESC, $wpdb->posts.post_title ASC";
	}


	/**
	 * Whether some events matching the required criterias have been founded or not.
	 *
	 * @return boolean
	 */
	public function hasEvent()
	{
		return $this->atLeastOneEvent;
	}


	/**
	 * Try to fetch the next matching event.
	 *
	 * @return boolean True if the next event has been fetched, false after the last event.
	 */
	public function fetchEvent()
	{
		// Return false if there is no more events.
		if(!$this->query->have_posts()) {
			$this->setEventID(-1);
			return false;
		}

		// Otherwise, fetch the next event, set its ID, and return true.
		$this->query->the_post();
		$this->setEventID($this->query->post->ID);
		return true;
	}


	/**
	 * Title of the currently selected event.
	 *
	 * @return string
	 */
	public function getEventTitle()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->title)) {
			$this->event->title = get_the_title();
		}
		return $this->event->title;
	}


	/**
	 * Public name of the author of the currently selected event.
	 *
	 * @return string
	 */
	public function getEventAuthor()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->author)) {
			$this->event->author = get_the_author();
		}
		return $this->event->author;
	}


	/**
	 * Date at which the event was published, formatted according the date format template
	 * defined by the general WP settings.
	 *
	 * @return string
	 */
	public function getEventReleaseDate()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->releaseDate)) {
			$this->event->releaseDate = get_the_date();
		}
		return $this->event->releaseDate;
	}

	/**
	 * Whether a teaser is defined for the current event.
	 *
	 * @return boolean
	 */
	public function isEventTeaserDefined()
	{
		return $this->getEventTeaser() !== '';
	}


	/**
	 * Return the teaser of the event.
	 *
	 * @return string
	 */
	public function getEventTeaser()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->teaser)) {
			$this->event->teaser = convert_chars(convert_smilies(wptexturize(get_the_excerpt())));
		}
		return $this->event->teaser;
	}


	/**
	 * Return the description of the event.
	 *
	 * @return string
	 */
	public function getEventContent()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->content)) {
			$content = apply_filters('the_content', $this->callGetTheContent(true));
			$this->event->content = $content === '' ? $this->getEventTeaser() : $content;
		}
		return $this->event->content;
	}


	/**
	 * Wrap a call to the WP native function `get_the_content()` into a context in which the global variables that affect
	 * the behavior of this function are properly set.
	 *
	 * @param boolean $globalMore
	 * @return string
	 */
	private function callGetTheContent($globalMore)
	{
		global $more;
		$oldMore = $more;
		$more = $globalMore;
		$retVal = get_the_content();
		$more = $oldMore;
		return $retVal;
	}
}
