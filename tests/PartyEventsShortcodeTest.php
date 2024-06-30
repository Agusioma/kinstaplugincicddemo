<?php

require_once('../kinstaplugincicddemo/event-mgmt-plugin.php');

class PartyEventsShortcodeTest extends WP_UnitTestCase {
    private static $class_instance;
    public function setUp(): void
    {
        parent::setUpBeforeClass();
        self::$class_instance = new Party_Events_Plugin();
    }

    public function test_display_events_shortcode() {

        // Insert a test event into the database
        global $wpdb;
        $table_name = $wpdb->prefix . 'party_events';
        $wpdb->insert($table_name, [
            'title' => 'Sample Event',
            'date' => '2023-12-31',
            'venue' => 'Sample Venue',
            'organizer' => 'Sample Organizer',
            'description' => 'Sample description.',
        ]);

        $output = do_shortcode('[events]');
        $this->assertStringContainsString('Sample Event', $output);
        $this->assertStringContainsString('2023-12-31', $output);
        $this->assertStringContainsString('Sample Venue', $output);
        $this->assertStringContainsString('Sample Organizer', $output);
        $this->assertStringContainsString('Sample description.', $output);
    }
}

?>