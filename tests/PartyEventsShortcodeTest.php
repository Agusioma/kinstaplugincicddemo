<?php

require_once "../event-mgmt-plugin.php";

class PartyEventsShortcodeTest extends WP_UnitTestCase {

    public function setUp(): void {
        parent::setUp();
        
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
    }

    public function test_display_events_shortcode() {
        $output = do_shortcode('[events]');
        $this->assertStringContainsString('Sample Event', $output);
        $this->assertStringContainsString('2023-12-31', $output);
        $this->assertStringContainsString('Sample Venue', $output);
        $this->assertStringContainsString('Sample Organizer', $output);
        $this->assertStringContainsString('Sample description.', $output);
    }
}

?>
