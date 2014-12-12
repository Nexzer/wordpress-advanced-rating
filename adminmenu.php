<?php
global $wpdb;

//insert new rating
if (isset($_POST['submit'])) {
    $wpdb->insert(
            $wpdb->prefix . 'advancedratings_ratings', array(
        'name' => $wpdb->escape($_POST['name']),
        'image' => $wpdb->escape($_POST['image'])
            ), array(
        '%s',
        '%s'
            )
    );
}

if (isset($_POST['massactionsubmit'])) {
    foreach ($_POST['selectid'] as $value) {
        $wpdb->delete($wpdb->prefix . 'advancedratings_ratings', array('id' => $value));
    }
}
?>

<div class="wrap">    
    <h2>Advanced ratings</h2>
    <br class="clear"></br>
    <div id="col-container">
        <div id="col-right">
            <div class="col-wrap">
                <h3>Ratings</h3>


                <form method="POST" action="">
                    <table class="widefat fixed" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>  <!--this column contains checkboxes-->
                                <th class="manage-column column-columnname" scope="col">Image</th>
                                <th class="manage-column column-columnname" scope="col">Name</th>
                                <th class="manage-column column-columnname num" scope="col">Amount of times voted for</th> 
                            </tr>

                        </thead>
                        <tfoot>
                            <tr>
                                <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>  <!--this column contains checkboxes-->
                                <th class="manage-column column-columnname" scope="col">Image</th>
                                <th class="manage-column column-columnname" scope="col">Name</th>
                                <th class="manage-column column-columnname num" scope="col">Amount of times voted for</th> 
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'advancedratings_ratings');

                            foreach ($result as $key => $value) {
                                echo "<tr class='" . ($key % 2 == 0 ? "alternate" : "") . "'>";
                                echo "<th class='check-column' scope='row'><input type='checkbox' name='selectid[]' value='$value->id'></th>";
                                echo "<td class='column-columnname'><img src='$value->image'></td>";
                                echo "<td class='column-columnname'>$value->name</td>";

                                $count = $wpdb->get_results("SELECT COUNT(*) as count FROM wp_advancedratings_data WHERE rating_id='$value->id'");
                                echo "<td class='column-columnname num'>" . $count[0]->count . "</td>";

                                echo "</tr>";
                            }

                            if (count($result) == 0) {
                                echo "<td colspan='7' class='column-columnname'>No results found</td>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <select name="mass" id="mass">
                        <option selected="selected" value="-1"><?php _e("Mass action", "poll"); ?></option>
                        <option value="1"><?php _e("Delete", "poll"); ?></option>
                    </select>
                    <input type="submit" name="massactionsubmit" value="<?php _e("Apply", "poll"); ?>" class="button action">
                </form>
            </div>
        </div>

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3>Add a new rating</h3>
                    <form method='POST' action=''>
                        <div class="form-field">
                            <label for="title">Name</label>
                            <input id="title" type="text" size="40" name="name">
                        </div>
                        <div class="form-field">
                            <label for="image">Image url</label>                            
                            <input id="image" name="image" type="text"/>
                            <input id="image_button" class="button" name="imagebutton" type="button" value="Upload image" />
                        </div>
                        <p class="submit">
                            <input id="submit" class="button button-primary" type="submit" value="Add rating" name="submit"> 
                        </p> 
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
wp_enqueue_media();
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        $('#image_button').click(function(e) {
            var button = $(this);

            wp.media.editor.send.attachment = function(props, attachment) {
                $("#image").val(attachment.url);
            }

            wp.media.editor.open(button);
            return false;
        });

    });
</script>