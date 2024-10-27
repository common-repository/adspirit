<?php

/**
 * Created by Sebastian Viereck IT-Services
 * www.sebastianviereck.de
 * Date: 11.02.16
 * Time: 17:01
 */
class AdspiritBanners
{
    const TABLE_NAME_SUFFIX = 'adspirit_banner';
    const DB_VERSION_CURRENT = '4.7';
    const OPTION_NAME_DB_VERSION = 'adspirit_db_version';
    const ADSPIRIT_ADMIN_PAGE_ID = 'adspirit_banner_list';
    const ADSPIRIT_BANNER_UPDATE_PAGE_ID = 'adspirit_banner_update';

    public static function install()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME_SUFFIX;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE $table_name (
	  id int(11) NOT NULL AUTO_INCREMENT,
	  banner_id int(11) NOT NULL,
	  hostname varchar(100)  NOT NULL,
	  alignment int(3) NOT NULL,
	  type int(3) NOT NULL,
	  width int(11) DEFAULT NULL,
	  height int(11) DEFAULT NULL,
	  responsive int(1) DEFAULT NULL,
	  parameters varchar(255)  DEFAULT NULL,
	  heading varchar(255)  DEFAULT NULL,
	  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  inserted timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  UNIQUE KEY id (id),
	  KEY banner_id (banner_id)
	) ;";

        dbDelta($sql);
        update_option(self::OPTION_NAME_DB_VERSION, self::DB_VERSION_CURRENT);
        update_option('adspirit_table_name', $table_name);

    }

    public static function getTableName()
    {
        return get_option('adspirit_table_name');
    }

    public static function getDbVersion()
    {
        return get_option('adspirit_db_version');
    }

    public static function uninstall()
    {
        global $wpdb;


        $adspirit_table_name = self::getTableName();

        $sql = "DROP TABLE $adspirit_table_name";

        $wpdb->query($sql);
    }

    public static function getAllBanners()
    {
        global $wpdb;
        $adspirit_table_name = self::getTableName();
        $results = $wpdb->get_results("SELECT * FROM $adspirit_table_name WHERE 1", OBJECT);

        return $results;
    }

    public static function getBannerById($id)
    {
        if (!is_integer($id)) {
            throw new Exception('not an int:' . $id);
        }
        global $wpdb;
        $adspirit_table_name = self::getTableName();
        $results = $wpdb->get_results("SELECT * FROM $adspirit_table_name WHERE id =$id LIMIT 1", OBJECT);
        if (is_array($results) && count($results) > 0) {
            return $results[0];
        } else {
            return false;
        }
    }

    public static function getLastHostName()
    {
        global $wpdb;
        $adspirit_table_name = self::getTableName();
        $results = $wpdb->get_results("SELECT hostname FROM $adspirit_table_name WHERE hostname IS NOT NULL AND hostname != ''  ORDER BY updated DESC LIMIT 1",
            OBJECT);
        if (is_array($results) && count($results) > 0) {
            return $results[0]->hostname;
        } else {
            return null;
        }
    }

    public static function deleteBannerById($id)
    {
        if (!is_integer($id)) {
            throw new Exception('not an int:' . $id);
        }
        global $wpdb;
        $adspirit_table_name = self::getTableName();
        $results = $wpdb->get_results("DELETE  FROM $adspirit_table_name WHERE id =$id");

        return $results !== false;
    }

    public static function updateBanner(AdspiritBannerModel $model)
    {
        global $wpdb;
        $adspirit_table_name = self::getTableName();
        $results = $wpdb->query(
            $wpdb->prepare(
                "UPDATE $adspirit_table_name
			SET
 			banner_id = %d,
			hostname = %s,
			alignment = %d,
			type = %d,
			height = %d,
			width = %d,
			responsive = %d,
			parameters = %s,
			heading = %s
 			WHERE id = %d  ",
                $model->getBannerId(),
                $model->getHostname(),
                $model->getAlignment(),
                $model->getType(),
                $model->getHeight(),
                $model->getWidth(),
                $model->isResponsive(),
                $model->getParameters(),
                $model->getHeading(),
                $model->getId()
            )
        );

        return $results !== false;
    }

    public static function createBanner(AdspiritBannerModel $model)
    {
        global $wpdb;
        $adspirit_table_name = self::getTableName();
        $results = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $adspirit_table_name
			SET
 			banner_id = %d,
			hostname = %s,
			alignment = %d,
			type = %d,
			height = %d,
			width = %d,
			responsive = %d,
			parameters = %s,
			heading = %s,
			inserted = NOW(),
			updated = NOW()

 			  ",
                $model->getBannerId(),
                $model->getHostname(),
                $model->getAlignment(),
                $model->getType(),
                $model->getHeight(),
                $model->getWidth(),
                $model->isResponsive(),
                $model->getParameters(),
                $model->getHeading()

            )
        );
        if ($results !== false) {
            return $wpdb->insert_id;
        } else {
            dump($results);
        }
    }

    public static function getAdminUrl()
    {
        return "options-general.php?page=" . AdspiritBanners::ADSPIRIT_ADMIN_PAGE_ID;
    }

    public static function getUpdateUrl($id)
    {
        return "options-general.php?page=" . AdspiritBanners::ADSPIRIT_BANNER_UPDATE_PAGE_ID . "&id=" . $id;
    }

    public static function getCreateUrl()
    {
        return "options-general.php?page=" . AdspiritBanners::ADSPIRIT_BANNER_UPDATE_PAGE_ID;
    }

    /* @return AdspiritBannerModel */
    public static function loadBannerById($id)
    {
        $bannerData = AdspiritBanners::getBannerById($id);
        if ($bannerData) {
            $bannerModel = new AdspiritBannerModel();
            $bannerModel->loadModel($bannerData);

            return $bannerModel;
        } else {
            die('could not find banner');
        }
    }

    /* @return integer */
    public static function checkBannerId()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            if (is_integer($id)) {
                return $id;
            } else {
                die('wrong id set');
            }
        } else {
            return false;
        }
    }

    public static function getBannerCodeById($id)
    {
        $bannerData = AdspiritBanners::getBannerById($id);
        if ($bannerData) {
            $bannerModel = new AdspiritBannerModel();
            $bannerModel->loadModel($bannerData);

            $adspiritCodeParser = new AdspiritCodeParser();

            return $adspiritCodeParser->getBannerCode($bannerModel);
        } else {
            return "Banner not found";
        }


    }
}