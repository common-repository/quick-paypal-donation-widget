<?php
/*
  Plugin Name: Quick PayPal Donation Widget
  Plugin URI:http://www.junktheme.com
  Description: Thanks for installing  Paypal Donation Widget - Easy and simple way to display donation button on wordpress website.
  Version: 1.0
  Author: Junk Theme
  Author URI: http://www.junktheme.com

 */

add_action('widgets_init', 'bs_paypal_widget');

function bs_paypal_widget() {
    register_widget('bs_paypal_widget_widget');
}


class bs_paypal_widget_widget extends WP_Widget {

    function bs_paypal_widget_widget() {
        $this->WP_Widget('bs_paypal_widget_widget', 'Quick PayPal Donation Widget', array('description' => 'This Is Paypal Donation Widget'));
    }

    /* -------------------------------------------------------
     * Front-end display of widget
     * ------------------------------------------------------- */

    function widget($args, $bs_paypal_widget) {

        extract($args);

        //Our variables from the widget settings.

        $title_widget = $bs_paypal_widget['title'];
        $title = apply_filters('widget_title', $title_widget);
        echo $before_widget;
        
        if ($title)
            echo $before_title . $title . $after_title;
        echo $this->display_paypal_widget( $bs_paypal_widget['paypal_email'], $bs_paypal_widget['paypal_org_name'],$bs_paypal_widget['donate_info'],$bs_paypal_widget['donate_amount'],$bs_paypal_widget['currency_code'],$bs_paypal_widget['button']);


        echo $after_widget;
    }

    public function display_paypal_widget($email, $org, $info, $amount,$currency,$button) {
        $data = '';
        $data.='<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type="hidden" name="cmd" value="_donations">
                <input type="hidden" name="business" value="'.$email.'">
                <input type="hidden" name="lc" value="MQ">
                <input type="hidden" name="amount" value="'.$amount.'">
                <input type="hidden" name="item_name" value="'.$org.'">
                <input type="hidden" name="item_number" value="'.$info.'">
                <input type="hidden" name="no_note" value="0">
                <input type="hidden" name="currency_code" value="'.$currency.'">
                <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
                <input type="image" style="position:relative" src="'.$button.'" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>';
        return $data;
    }

    /* -------------------------------------------------------
     *              Sanitize data, save and retrive
     * ------------------------------------------------------- */

    function update($new_bs_paypal_widget, $old_bs_paypal_widget) {
        $bs_paypal_widget = $old_bs_paypal_widget;

        $bs_paypal_widget['title'] = esc_attr($new_bs_paypal_widget['title']);
        $bs_paypal_widget['paypal_email'] = esc_attr($new_bs_paypal_widget['paypal_email']);
        $bs_paypal_widget['paypal_org_name'] = esc_attr($new_bs_paypal_widget['paypal_org_name']);
        $bs_paypal_widget['donate_info'] = esc_attr($new_bs_paypal_widget['donate_info']);
        $bs_paypal_widget['donate_amount'] = esc_attr($new_bs_paypal_widget['donate_amount']);
        $bs_paypal_widget['currency_code'] = esc_attr($new_bs_paypal_widget['currency_code']);
        $bs_paypal_widget['button'] = esc_attr($new_bs_paypal_widget['button']);


        return $bs_paypal_widget;
    }

    /* -------------------------------------------------------
     *              Back-End display of widget
     * ------------------------------------------------------- */

    function form($bs_paypal_widget) {

        $defaults = array(
            'title' => 'Donation',
            'paypal_email' => '',
            'paypal_org_name' => '',
            'donate_info' => '',
            'donate_amount' => '0',
            'currency_code'=>'USD',
            'button'=>'https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif'
        );
        $bs_paypal_widget = wp_parse_args((array) $bs_paypal_widget, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $bs_paypal_widget['title']; ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('paypal_email'); ?>">Paypal Email:</label>
            <input id="<?php echo $this->get_field_id('paypal_email'); ?>" name="<?php echo $this->get_field_name('paypal_email'); ?>" value="<?php echo $bs_paypal_widget['paypal_email']; ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('paypal_org_name'); ?>">Organigation Name:</label>
            <input id="<?php echo $this->get_field_id('paypal_org_name'); ?>" name="<?php echo $this->get_field_name('paypal_org_name'); ?>" value="<?php echo $bs_paypal_widget['paypal_org_name']; ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('donate_info'); ?>">Donate Info:</label>
            <input id="<?php echo $this->get_field_id('donate_info'); ?>" name="<?php echo $this->get_field_name('donate_info'); ?>" value="<?php echo $bs_paypal_widget['donate_info']; ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('donate_amount'); ?>">Amount:</label>
            <input id="<?php echo $this->get_field_id('donate_amount'); ?>" name="<?php echo $this->get_field_name('donate_amount'); ?>" value="<?php echo $bs_paypal_widget['donate_amount']; ?>" style="width:100%;" />
        </p>
        <p>
            <label>currency:</label>
            <?php
            echo "<select style='width:100%' id='" . $this->get_field_id('currency_code') . "' name='" . $this->get_field_name('currency_code') . "'>";
            $know = array('USD','AUD','BRL','GBP','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MXN','TWD','NZD','NOK','PHP','PLN','RUB','SGD','SEK','CHF','THB');
            foreach ($know as $v) {
                echo '<option value="' . $v . '"';
                if ($v == $bs_paypal_widget['currency_code']) {
                    echo 'selected="selected"';
                }

                echo '>' . $v . '</option>';
            }

            echo "</select>";
            ?>
        </p>
         <p>
            <label>Button:</label>
            <?php
            echo "<select style='width:100%' id='" . $this->get_field_id('button') . "' name='" . $this->get_field_name('button') . "'>";
            $know = array('large'=>'https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif','Small'=>'https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif');
            foreach ($know as $key=> $v) {
                echo '<option value="' . $v . '"';
                if ($v == $bs_paypal_widget['button']) {
                    echo 'selected="selected"';
                }

                echo '>' . $key . '</option>';
            }

            echo "</select>";
            ?>
        </p>
        
<?php }

}

// //shortcode
// add_shortcode('bs_paypal_shortcode', 'bs_paypal_widget_shortcode');

// function bs_paypal_widget_shortcode($atts, $content = NULL) {
//     extract(shortcode_atts(
//                     array(
//         'username' => 'https://www.facebook.com/FacebookforDevelopers/',
//         'show_face' => 'true',
//         'hide_cover' => 'true',
//         'small_header' => 'false',
//         'language'=>'en_US',
//     ), $atts));

//    $data = bs_paypal_widget_widget::display_paypal_widget($username, $small_header, $hide_cover, $show_face,$language);
//     return $data;
// }
