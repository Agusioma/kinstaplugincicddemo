<?php


class PartyEventsActivationTest extends WP_UnitTestCase {

    public function test_create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'party_events';
        
        // Check if the table does not exist
        $this->assertFalse($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name);

        // Simulate plugin activation
        pep_create_table();

        // Check if the table now exists
        $this->assertTrue($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name);

        // Check if the table has the correct columns
        $columns = $wpdb->get_results("DESCRIBE $table_name");
        $this->assertCount(6, $columns);  // id, title, date, venue, organizer, description
    }
}

?>
