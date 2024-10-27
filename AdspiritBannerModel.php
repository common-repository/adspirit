<?php

/**
 * Created by Sebastian Viereck IT-Services
 * www.sebastianviereck.de
 * Date: 12.02.16
 * Time: 09:08
 */
class AdspiritBannerModel
{
    private $id;
    private $banner_id;
    private $hostname;
    private $alignment;
    private $type;
    private $width = null;
    private $height = null;

    /**
     * @var @bool
     */
    private $responsive = null;
    private $parameters;
    private $heading;
    private $updated;
    private $inserted;

    const ALIGNMENT_NONE = 10;
    const ALIGNMENT_LEFT = 20;
    const ALIGNMENT_RIGHT = 30;
    const ALIGNMENT_CENTER = 40;

    public static function getAllAlignments()
    {
        return array(
            self::ALIGNMENT_NONE => 'none',
            self::ALIGNMENT_LEFT => 'left',
            self::ALIGNMENT_RIGHT => 'right',
            self::ALIGNMENT_CENTER => 'center',
        );
    }

    const TYPE_SCRIPT = 10;
    const TYPE_IFRAME = 20;
    const TYPE_ASYNC = 30;

    public static function getAllTypes()
    {
        return array(
            self::TYPE_SCRIPT => 'script',
            self::TYPE_IFRAME => 'iframe',
            self::TYPE_ASYNC => 'async',
        );
    }

    public function loadModel(stdClass $databaseData)
    {
        foreach ($databaseData as $name => $value) {
            $this->$name = $value;
        }

        return $this;
    }

    public function loadModelFromArray($array)
    {
        foreach ($array as $name => $value) {
            $this->$name = $value;
        }

        return $this;
    }

    public function validate()
    {
        if (
            $this->checkHostname() &&
            $this->checkBannerId() &&
            $this->checkAlignment() &&
            $this->checkType() &&
            $this->checkHeight() &&
            $this->checkWidth() &&
            $this->checkParameters() &&
            $this->checkParameters() &&
            $this->checkResponsive()
        ) {
            return true;
        }
    }

    public function checkResponsive()
    {
        if ($this->responsive && $this->type == self::TYPE_IFRAME) {
            return false;
        }

        return true;
    }

    public function checkHostname()
    {
        if (strlen($this->hostname) > 5 && strlen($this->hostname) < 255) {
            return true;
        }
    }

    public function checkBannerId()
    {
        return $this->banner_id > 0;
    }

    public function checkAlignment()
    {
        return in_array($this->alignment, array_keys(self::getAllAlignments()));

    }

    public function checkType()
    {
        return in_array($this->type, array_keys(self::getAllTypes()));

    }

    public function checkHeight()
    {
        if ($this->isResponsive()) {
            return true;
        }

        return $this->height > 0;
    }

    public function checkWidth()
    {
        if ($this->isResponsive()) {
            return true;
        }

        return $this->width > 0;
    }

    public function checkParameters()
    {
        return true;
    }

    public function checkHeading()
    {
        return true;
    }


    public function getShortCode()
    {
        return "[adspirit " . $this->getId() . "]";
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return AdspiritBannerModel
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBannerId()
    {
        return $this->banner_id;
    }

    /**
     * @param mixed $banner_id
     *
     * @return AdspiritBannerModel
     */
    public function setBannerId($banner_id)
    {
        $this->banner_id = $banner_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return trim($this->hostname);
    }

    /**
     * @param mixed $hostname
     *
     * @return AdspiritBannerModel
     */
    public function setHostname($hostname)
    {
        $this->hostname = trim($hostname);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @param mixed $alignment
     *
     * @return AdspiritBannerModel
     */
    public function setAlignment($alignment)
    {
        $this->alignment = $alignment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return AdspiritBannerModel
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     *
     * @return AdspiritBannerModel
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     *
     * @return AdspiritBannerModel
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     *
     * @return AdspiritBannerModel
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @param mixed $heading
     *
     * @return AdspiritBannerModel
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     *
     * @return AdspiritBannerModel
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInserted()
    {
        return $this->inserted;
    }

    /**
     * @param mixed $inserted
     *
     * @return AdspiritBannerModel
     */
    public function setInserted($inserted)
    {
        $this->inserted = $inserted;

        return $this;
    }

    /**
     * @return bool
     */
    public function isResponsive()
    {
        return (bool)$this->responsive;
    }

    /**
     * @param bool $responsive
     *
     * @return $this
     */
    public function setResponsive($responsive)
    {
        $this->responsive = (bool)$responsive;

        return $this;
    }


}