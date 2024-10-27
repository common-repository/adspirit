<?php
/**
 * Created by Sebastian Viereck IT-Services
 * www.sebastianviereck.de
 * Date: 11.02.16
 * Time: 15:46
 */
defined('ABSPATH') or die('No script kiddies please!');

$validationFailed = false;
$updateSucces = false;
$id = AdspiritBanners::checkBannerId();
$isCreate = !($id > 0);
if (isset($_POST['submit'])) {

    unset($_POST['submit']);
    $bannerModel = new AdspiritBannerModel();
    $bannerModel->loadModelFromArray($_POST);
    if (!$isCreate) {
        $bannerModel->setId($id);
    }

    if ($bannerModel->validate()) {
        //save to db
        if ($isCreate) {
            $newId = AdspiritBanners::createBanner($bannerModel);
            if ($newId) {
                //redirect to update Page
                ?>
                <script type="text/javascript">
                    location = "<?php echo AdspiritBanners::getUpdateUrl($newId) ?>";
                </script>
                <?php
            }
        } else {
            if (AdspiritBanners::updateBanner($bannerModel)) {
                $bannerModel = AdspiritBanners::loadBannerById($id);
                $updateSucces = true;
            }

        }
    } else {
        $validationFailed = true;
    }
} else {
    if ($isCreate) {
        //create
        $bannerModel = new AdspiritBannerModel();
        $lastHostName = AdspiritBanners::getLastHostName();
        if ($lastHostName) {
            $bannerModel->setHostname($lastHostName);
        }

    } else {
        //update
        $bannerModel = AdspiritBanners::loadBannerById($id);
    }
}
?>
<br>
<a href="<?php echo AdspiritBanners::getAdminUrl() ?>">Back</a>
<h1>
    <?php if ($isCreate) : ?>
        Create Banner
    <?php else : ?>
        Update Banner <?php echo $bannerModel->getId() ?>
    <?php endif; ?>

</h1>
<?php if ($updateSucces) : ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Updated Banner #'.$bannerModel->getId()); ?></p>
    </div>
<?php endif; ?>

<form method="post" target="_self">
    <table>
        <tr>
            <td>
                <label for="hostname">Adspirit Hostname</label>
            </td>
            <td>
                <input type="text" name="hostname" value="<?php echo $bannerModel->getHostname() ?>"
                       placeholder="abc.adspirit.net">
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkHostname()) : ?>
                    <div class="error">
                        Input not valid
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label for="banner_id">Placement-ID</label>
            </td>
            <td>
                <input type="number" name="banner_id" value="<?php echo $bannerModel->getBannerId() ?>">
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkBannerId()) : ?>
                    <div class="error">
                        Input not valid
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label for="alignment">Alignment</label>
            </td>
            <td>
                <select name="alignment">
                    <?php
                    $alignments = AdspiritBannerModel::getAllAlignments();
                    foreach ($alignments as $value => $name) {
                        ?>
                        <option
                            <?php echo $value == $bannerModel->getAlignment() ? "selected" : "" ?>
                            value="<?php echo $value ?>">
                            <?php echo $name ?>
                        </option>
                        <?php
                    }
                    ?>

                </select>
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkAlignment()) : ?>
                    <div class="error">
                        Input not valid
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label for="type">Type</label>
            </td>
            <td>
                <select name="type" id="type">
                    <?php
                    $types = AdspiritBannerModel::getAllTypes();
                    foreach ($types as $value => $name) {
                        ?>
                        <option
                            <?php echo $value == $bannerModel->getType() ? "selected" : "" ?>
                            value="<?php echo $value ?>">
                            <?php echo $name ?>
                        </option>
                        <?php
                    }
                    ?>

                </select>
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkType()) : ?>
                    <div class="error">
                        Input not valid
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label for="responsive">Responsive Placement</label>
            </td>
            <td>
                <input type="checkbox" name="responsive" <?php echo $bannerModel->isResponsive() ? "checked" : "" ?>/>
                <script>
                    jQuery( document ).ready(function() {
                        jQuery("[name='responsive']").on('change', function() {
                            value = this.checked;
                            if(value){
                                jQuery('#widthRow').hide();
                                jQuery('#heightRow').hide();
                            }
                            else{
                                jQuery('#widthRow').show();
                                jQuery('#heightRow').show();
                            }
                        }).change(); //trigger event onload
                    });
                </script>
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkResponsive()) : ?>
                    <div class="error">
                        Responsive placement is not available for Type "iframe"
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr id="widthRow">
            <td>
                <label for="width">Width</label>
            </td>
            <td>
                <input type="number" name="width" value="<?php echo $bannerModel->getWidth() ?>">
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkWidth()) : ?>
                    <div class="error">
                        Input not valid
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr id="heightRow">
            <td>
                <label for="height">Height</label>
            </td>
            <td>
                <input type="number" name="height" value="<?php echo $bannerModel->getHeight() ?>">
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkHeight()) : ?>
                    <div class="error">
                        Input not valid
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label for="parameters">Parameters (Optional)</label>
            </td>
            <td>
                <input type="text" name="parameters" value="<?php echo $bannerModel->getParameters() ?>">
            </td>
            <td>
                <?php if ($validationFailed && !$bannerModel->checkParameters()) : ?>
                    <div class="error">
                        Input not valid
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label for="heading">Heading (Optional)</label>
            </td>
            <td>
                <input type="text" name="heading" value="<?php echo $bannerModel->getHeading() ?>">
            </td>
            <?php if ($validationFailed && !$bannerModel->checkHeading()) : ?>
                <div class="error">
                    Input not valid
                </div>
            <?php endif; ?>
        </tr>
        <?php if (!$isCreate) : ?>
            <tr>
                <td>
                    <label for="shortCode">Shortcode</label>
                </td>
                <td>
                    <?php echo $bannerModel->getShortCode() ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>
    <br>
    <input type="submit" value="Save" name="submit" class="button button-primary">
</form>
