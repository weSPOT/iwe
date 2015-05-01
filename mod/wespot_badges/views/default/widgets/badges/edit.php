<div>
    <label>Inquiry server:</label>
    <?php
        //This is an instance of the ElggWidget class that represents our widget.
        $widget = $vars['inquiryserver'];

        // Give the user a plain text box to input a message
        echo elgg_view('input/text', array(
            'name' => 'params[inquiryserver]',
            'value' => $widget->inquiryserver,
            'class' => 'hello-input-text',
        ));
    ?>
</div>	