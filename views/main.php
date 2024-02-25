<?php

ob_start();
// echoing path for script submit purpose
?>
<div style="padding: 2rem 1rem">
    <script>
        var takeoff_plugin_path = '<?php echo URL_PATH ?>';
    </script>
    <h1>CSV Codes Loader</h1>
    <form enctype="multipart/form-data" id="takeoff_form">

        <label for="file" class="screen-reader-text">CSV File</label>
        <input type="file" name="file" id="file" class="wp-upload-php" />


        <?php submit_button(text: 'LOAD CODES', other_attributes: [
            'id' => 'takeoff_submit',
        ]); ?>
        <p id="takeoff_message" style="display: none">Processing, please wait... <br>
            For 10.000 codes it may take up to 7 minutes. <br>
            This status will be updated in real time. Also you can close this window, if you want.
        </p>

    </form>
</div>
<?
return ob_get_clean('Download');
