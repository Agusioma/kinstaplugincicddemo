<?php

require_once '../kinstaplugincicddemo/event-mgmt-plugin.php';

class PartyEventsSubmissionTest extends WP_UnitTestCase {
	private static $plugin_instance;
	public function setUp(): void {

		parent::setUpBeforeClass();
		self::$plugin_instance = new Party_Events_Plugin();
	}

	public function test_form_submission() {
		// Simulate form submission
		$_POST['submit_btn']        = 'Submit';
		$_POST['event_title']       = 'Test Event';
		$_POST['event_date']        = '2023-12-31';
		$_POST['event_venue']       = 'Test Venue';
		$_POST['event_organizer']   = 'Test Organizer';
		$_POST['event_description'] = 'This is a test event description.';

		// Call the form processing function
		self::$plugin_instance->process_event_submission();

		// Check if the event was inserted into the database
		global $wpdb;
		$table_name = $wpdb->prefix . 'party_events';
		$event      = $wpdb->get_row( "SELECT * FROM $table_name WHERE title = 'Test Event'" );

		$this->assertNotNull( $event );
		$this->assertEquals( 'Test Event', $event->title );
		$this->assertEquals( '2023-12-31', $event->date );
		$this->assertEquals( 'Test Venue', $event->venue );
		$this->assertEquals( 'Test Organizer', $event->organizer );
		$this->assertEquals( 'This is a test event description.', $event->description );
	}
}
