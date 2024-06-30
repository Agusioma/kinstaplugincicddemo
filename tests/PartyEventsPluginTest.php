<?php

class PartyEventsPluginTest extends WP_UnitTestCase {

    public function test_form_submission() {
        // Simulate form submission
        $_POST['pep_submit'] = 'Submit';
        $_POST['pep_event_title'] = 'Test Event';
        $_POST['pep_event_date'] = '2023-12-31';
        $_POST['pep_event_venue'] = 'Test Venue';
        $_POST['pep_event_organizer'] = 'Test Organizer';
        $_POST['pep_event_description'] = 'This is a test event description.';

        // Call the form processing function
        pep_process_form();

        // Check if the event was inserted into the database
        global $wpdb;
        $table_name = $wpdb->prefix . 'party_events';
        $event = $wpdb->get_row("SELECT * FROM $table_name WHERE title = 'Test Event'");

        $this->assertNotNull($event);
        $this->assertEquals('Test Event', $event->title);
        $this->assertEquals('2023-12-31', $event->date);
        $this->assertEquals('Test Venue', $event->venue);
        $this->assertEquals('Test Organizer', $event->organizer);
        $this->assertEquals('This is a test event description.', $event->description);
    }
}

?>