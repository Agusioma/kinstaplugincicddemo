<?php
/*
Plugin Name: Party Events Plugin
Description: A simple plugin to create and display party events.
Version: 1.0
Author: Kinsta
*/

class Party_Events_Plugin {

    public function __construct() {
        // Register activation hook
        register_activation_hook(__FILE__, array($this, 'create_table'));

        // Register shortcodes and hooks
        add_shortcode('party_event_form', array($this, 'display_event_reg_form'));
        add_action('init', array($this, 'process_event_submission'));
        add_shortcode('events', array($this, 'display_added_events'));
    }

    // Function to create the database table on plugin activation
    public function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'party_events';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title tinytext NOT NULL,
            date date NOT NULL,
            venue tinytext NOT NULL,
            organizer tinytext NOT NULL,
            description text NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Shortcode to display the event submission form
    public function display_event_reg_form() {
        ob_start();
        ?>
        <form action="" method="post">
            <p>
                <label for="event_title">Event Title</label>
                <input type="text" name="event_title" id="pep_event_title" required>
            </p>
            <p>
                <label for="event_date">Date</label>
                <input type="date" name="event_date" id="pep_event_date" required>
            </p>
            <p>
                <label for="event_venue">Venue</label>
                <input type="text" name="event_venue" id="pep_event_venue" required>
            </p>
            <p>
                <label for="event_organizer">Organizer</label>
                <input type="text" name="event_organizer" id="pep_event_organizer" required>
            </p>
            <p>
                <label for="event_description">Description</label>
                <textarea name="event_description" id="pep_event_description" required></textarea>
            </p>
            <p>
                <input type="submit" name="submit_btn" value="Create Event">
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    // Function to process form submission
    public function process_event_submission() {
        if (isset($_POST['submit_btn'])) {
            $title = sanitize_text_field($_POST['event_title']);
            $date = sanitize_text_field($_POST['event_date']);
            $venue = sanitize_text_field($_POST['event_venue']);
            $organizer = sanitize_text_field($_POST['event_organizer']);
            $description = sanitize_textarea_field($_POST['event_description']);

            global $wpdb;
            $table_name = $wpdb->prefix . 'party_events';

            $wpdb->insert(
                $table_name,
                [
                    'title' => $title,
                    'date' => $date,
                    'venue' => $venue,
                    'organizer' => $organizer,
                    'description' => $description,
                ]
            );
        }
    }

    // Shortcode to display the events
    public function display_added_events() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'party_events';
        $events = $wpdb->get_results("SELECT * FROM $table_name");
        $output = '<div>';

        if ($events) {
            foreach ($events as $event) {
                $output .= '<div>';
                $output .= '<h2>' . esc_html($event->title) . '</h2>';
                $output .= '<p><strong>Date:</strong> ' . esc_html($event->date) . '</p>';
                $output .= '<p><strong>Venue:</strong> ' . esc_html($event->venue) . '</p>';
                $output .= '<p><strong>Organizer:</strong> ' . esc_html($event->organizer) . '</p>';
                $output .= '<div>' . esc_html($event->description) . '</div>';
                $output .= '</div>';
            }
        } else {
            $output .= '<p>No events found.</p>';
        }

        $output .= '</div>';
        return $output;
    }
}

// Instantiate the plugin class
new Party_Events_Plugin();
?>