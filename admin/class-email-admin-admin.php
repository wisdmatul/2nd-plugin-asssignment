<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://atul.com
 * @since      1.0.0
 *
 * @package    Email_Admin
 * @subpackage Email_Admin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Email_Admin
 * @subpackage Email_Admin/admin
 * @author     atul.com/atul-plugin <atul@atul.com>
 */
class Email_Admin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/email-admin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/email-admin-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function send_emaildata()
{
    error_log('send_emaildata');
    $to = get_option('admin_email');
    $subject = 'Daily posts';
    $data = $this->get_emaildata();
    $message = '';

    foreach($data as $post_data)
    {
        $message .= 'Title:' . $post_data['title'] . "\n";
        $message .= 'URL:' . $post_data['url'] . "\n";
        $message .= 'Metta Title:' . $post_data['meta_title'] . "\n";
        $message .= 'Meta Description' . $post_data['meta_description'] . "\n";
        $message .= 'Meta Keywords:' . $post_data['meta_keywords'] . "\n";
        $message .= 'Page Speed Score:' . $post_data['page_speed'] . "seconds \n";
        $message .= "\n";
    }
    $headers = array(
        'From: atul.kumar@wisdmlabs.com',
        'Content-Type: text/html; charset=UTF-8'
    );

    wp_mail($to, $subject, $message, $headers);
}

public function get_emaildata()
{
    $args = array(
        'date_query' => array(
            array(
                'after' => '24 hours ago',
            ),
        ),
    );

    $query = new WP_Query( $args );
    $posts = $query->posts;
    $data = array();

    foreach($posts as $post)
    {
        $post_data = array(
            'title' => $post->post_title,
            'url' => get_permalink($post->ID),
            'meta_title' => get_post_meta($post->ID, '_yoast_wpseo_title', true),
            'meta_description' => get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true ),
            'meta_keywords' => get_post_meta($post->ID, '_yoast_wpseo_focuskw', true),
            'page_speed' => $this->get_page_speed_score( get_permalink( $post->ID ) ),
        );
        array_push($data, $post_data);
    }
    return $data;
}

function get_page_speed_score($url) {

    $api_key = "416ca0ef-63e4-4caa-a047-ead672ecc874"; // your api key
	$new_url = "http://www.webpagetest.org/runtest.php?url=".$url."&runs=1&f=xml&k=".$api_key; 
	$run_result = simplexml_load_file($new_url);
	$test_id = $run_result->data->testId;

    $status_code=100;
    
  //  while( $status_code != 200){
//        sleep(10);
        $xml_result = "http://www.webpagetest.org/xmlResult/".$test_id."/";
	    $result = simplexml_load_file($xml_result);
        $status_code = $result->statusCode;
        //$time = 'status_code: '. $status_code;
        
           $time = (float) ($result->data->median->firstView->loadTime)/1000;
           
   //};

    return $time;
}

}
