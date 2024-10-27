<?php
/**
 * Created by Sebastian Viereck IT-Services
 * www.sebastianviereck.de
 * Date: 11.02.16
 * Time: 15:46
 */
defined('ABSPATH') or die('No script kiddies please!');
?>

<h1>List Banners</h1>

<?php
if (isset($_POST['delete'])) {
    $id = (int)$_POST['id'];
    AdspiritBanners::deleteBannerById($id);
}


$adspirit = new AdspiritBanners();
$banners = $adspirit->getAllBanners();
?>
<table class="wp-list-table widefat plugins">
    <thead>
    <tr>
        <td>
            Banner-ID
        </td>
        <td>
            Width x Height
        </td>
        <td>
            Short Code
        </td>
        <td>
            Delete
        </td>
        <td>
            Edit
        </td>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($banners) {
        foreach ($banners as $banner) {
            $bannerModel = new AdspiritBannerModel();
            $bannerModel->loadModel($banner);
            ?>
            <tr>
                <td>
                    <?php echo $bannerModel->getId() ?>
                </td>
                <td>
                    <?php echo $bannerModel->getWidth() ?> x <?php echo $bannerModel->getHeight() ?>
                </td>
                <td>
                    <?php echo $bannerModel->getShortCode() ?>
                </td>
                <td>
                    <form target="_self" method="post">
                        <input type="hidden" value="<?php echo $bannerModel->getId() ?>" name="id">
                        <input type="submit" name="delete" class="button" value="Delete"
                               onclick="return confirm('Do you really want to delete this item?')">
                    </form>
                </td>
                <td>
                    <a class="button"
                       href='<?php echo AdspiritBanners::getUpdateUrl($bannerModel->getId()) ?>'>Edit</a>
                </td>
            </tr>

            <?php
        }
    }
    ?>

    </tbody>
</table>
<p>
    You can create a banners and insert them to your posts or sites by a shortcode or a widget. <br>
    To insert a banner by shortcode copy the shortcode including the squared brackets into your post.<br>
    To add a banner to your sidebar or any other widget area in your theme use the adspirit widget in the
    <a href="widgets.php">widget menu</a>.
</p>

<a href="<?php echo AdspiritBanners::getCreateUrl() ?>" class="button button-primary">Add Banner</a>




