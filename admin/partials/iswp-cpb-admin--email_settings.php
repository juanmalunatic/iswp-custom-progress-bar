<?php
if (!isset($options)) {$options = [];} // So the code highlighter won't complain
$option_name = 'iswp_cpb__oname__email_settings';
?>

<h4>
    E-mail settings
</h4>

<p>
    The reminder system sends four e-mails. You can edit the mail's text below.
</p>
<p>
    Use [NAME] to output the user's full name.
</p>

<fieldset>
    <table class="form-table">
        <tbody>

        <!-- Start Field M0 -->
        <?php $description = "This e-mail will be sent on the same day as the user pays.";?>
        <tr>
            <th>
                Content of M0
            </th>
            <td>
                <legend class="screen-reader-text">
                    <span>
                        <?=$description?>
                    </span>
                </legend>
                <label for         ="<?= $option_name ?>[email0--text]">
                    <textarea   id ="<?= $option_name ?>[email0--text]"
                              name ="<?= $option_name ?>[email0--text]"
                              cols="80" rows="10"
                    ><?=$options['email0--text']?></textarea>
                    <br />
                    <span class="description">
                        <?=$description?>
                    </span>
                </label>
            </td>
        </tr>
        <!--/end Field M0-->

        <!-- Start Field M1 -->
        <?php $description = "This e-mail will be sent 6 months before payment expires.";?>
        <tr>
            <th>
                Content of M1
            </th>
            <td>
                <legend class="screen-reader-text">
                    <span>
                        <?=$description?>
                    </span>
                </legend>
                <label for           ="<?= $option_name ?>[email1--text]">
                    <textarea     id ="<?= $option_name ?>[email1--text]"
                                name ="<?= $option_name ?>[email1--text]"
                                cols="80" rows="10"
                    ><?=$options['email1--text']?></textarea>
                    <br />
                    <span class="description">
                        <?=$description?>
                    </span>
                </label>
            </td>
        </tr>
        <!--/end Field M1-->

        <!-- Start Field M2 -->
        <?php $description = "This e-mail will be sent 3 months before payment expires.";?>
        <tr>
            <th>
                Content of M2
            </th>
            <td>
                <legend class="screen-reader-text">
                    <span>
                        <?=$description?>
                    </span>
                </legend>
                <label for             ="<?= $option_name ?>[email2--text]">
                    <textarea       id ="<?= $option_name ?>[email2--text]"
                                  name ="<?= $option_name ?>[email2--text]"
                                  cols="80" rows="10"
                    ><?=$options['email2--text']?></textarea>
                    <br />
                    <span class="description">
                        <?=$description?>
                    </span>
                </label>
            </td>
        </tr>
        <!--/end Field M2-->

        <!-- Start Field M3 -->
        <?php $description = "This e-mail will be sent 1 week before payment expires.";?>
        <tr>
            <th>
                Content of M3
            </th>
            <td>
                <legend class="screen-reader-text">
                    <span>
                        <?=$description?>
                    </span>
                </legend>
                <label for               ="<?= $option_name ?>[email3--text]">
                    <textarea         id ="<?= $option_name ?>[email3--text]"
                                    name ="<?= $option_name ?>[email3--text]"
                                    value="<?= $options['email3--text'] ?>"
                                    cols="80" rows="10"
                    ><?=$options['email3--text']?></textarea>
                    <br />
                    <span class="description">
                        <?=$description?>
                    </span>
                </label>
            </td>
        </tr>
        <!--/end Field M3-->

        </tbody>
    </table>
</fieldset>