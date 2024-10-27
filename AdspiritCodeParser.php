<?php

/**
 * Created by Sebastian Viereck IT-Services
 * www.sebastianviereck.de
 * Date: 12.02.16
 * Time: 12:30
 */
class AdspiritCodeParser
{
    /**
     * @var string
     */
    private static $asyncJsScript = null;

    /**
     * @return string
     */
    public static function getAsyncJsScript()
    {
        return self::$asyncJsScript;
    }

    /**
     * @param AdspiritBannerModel $model
     *
     * @return string
     * @throws Exception
     */
    public function getBannerCode(AdspiritBannerModel $model)
    {
        if ($model->getType() == AdspiritBannerModel::TYPE_SCRIPT) {
            if ($model->isResponsive()) {

                /**
                 * <script src="http://tycoon.adspirit.net/adresponsivescript.php?pid=1&ord=[timestamp]" type="text/javascript"></script>
                 */
                $bannerCode = "<div style='{$this->getTextAlignCode($model)}
					'
				>
					{$this->getHeadingCode($model)}
				
					<script type='text/javascript'
					src='//{$model->getHostname()}/adresponsivescript.php?pid={$model->getBannerId()}&{$model->getParameters()}'>
					</script>
				</div>
				";
            } else {
                $bannerCode = "<div style='{$this->getTextAlignCode($model)}
					display:block;
					width:{$model->getWidth()}px;
					height:{$model->getHeight()}px;'
				>
					{$this->getHeadingCode($model)}
					
					<script type='text/javascript'
					src='//{$model->getHostname()}/adscript.php?pid={$model->getBannerId()}&{$model->getParameters()}'>

				</script>
				</div>";
            }

        } else if ($model->getType() == AdspiritBannerModel::TYPE_IFRAME) {
            if ($model->isResponsive()) {
                throw new Exception('Responsive is not allowed with type iframe');
            } else {
                $bannerCode =
                    "<div style='{$this->getTextAlignCode($model)}
					display:block;
					width:{$model->getWidth()}px;
					'
				>
					{$this->getHeadingCode($model)}

					<iframe
					width='{$model->getWidth()}'
					height='{$model->getHeight()}'
					noresize='noresize'
					scrolling='no'
					frameborder='0'
					marginheight='0'
					marginwidth='0'
					src='//{$model->getHostname()}/adframe.php?pid={$model->getBannerId()}&{$model->getParameters()}'
					ALLOWTRANSPARENCY='true'></iframe>

				</div>";
            }

        } else if ($model->getType() == AdspiritBannerModel::TYPE_ASYNC) {
            if ($model->isResponsive()) {
                /**
                 *
                 * <ins class="asm_async_creative"
                 * style="display:inline-block; text-align:left;"
                 * data-asm-host="tycoon.adspirit.net"
                 * data-asm-responsive="1"
                 * data-asm-params="pid=1"></ins>
                 * <script src="http://tycoon.adspirit.net/adasync.js" async type="text/javascript"></script>
                 *
                 */

                $bannerCode =
                    "<div style='{$this->getTextAlignCode($model)}
					'
				>
					{$this->getHeadingCode($model)}

					<ins
					class='asm_async_creative'
					style='display:block;text-align:left;'
					data-asm-host='{$model->getHostname()}'
					data-asm-params='pid={$model->getBannerId()}&{$model->getParameters()}'
					data-asm-responsive='1'
					></ins>
				</div>";
                /*				$bannerCode = '
<ins class="asm_async_creative" style="display:inline-block; text-align:left;" 
data-asm-host="tycoon.adspirit.net" data-asm-responsive="1" data-asm-params="pid=1"></ins>
<script src="http://tycoon.adspirit.net/adasync.js" async type="text/javascript"></script>';*/

            } else {
                $bannerCode =
                    "<div style='{$this->getTextAlignCode($model)}
					display:block;
					width:{$model->getWidth()}px;
					'
				>
					{$this->getHeadingCode($model)}

					<ins
					class='asm_async_creative'
					style='display:block;
					width:{$model->getWidth()}px;
					height:{$model->getHeight()}px;
					text-align:left;'
					data-asm-host='{$model->getHostname()}'
					data-asm-params='pid={$model->getBannerId()}&{$model->getParameters()}'></ins>

				</div>";
            }

            self::$asyncJsScript = "<script src='//{$model->getHostname()}/adasync.js' async type='text/javascript' language='JavaScript'></script>";
        } else {
            throw new Exception('type not found');
        }

        return $bannerCode;
    }

    private function getTextAlignCode(AdspiritBannerModel $model)
    {
        $alignment = $model->getAlignment();
        if ($alignment != AdspiritBannerModel::ALIGNMENT_NONE) {
            if ($alignment == AdspiritBannerModel::ALIGNMENT_LEFT) {
                $align = "left";
            } else if ($alignment == AdspiritBannerModel::ALIGNMENT_RIGHT) {
                $align = "right";
            } else if ($alignment == AdspiritBannerModel::ALIGNMENT_CENTER) {
                $align = "center";
            } else {
                throw new Exception('alignment not found');
            }

            return "text-align: $align ;";
        }

    }

    private function getHeadingCode(AdspiritBannerModel $model)
    {
        $heading = $model->getHeading();
        if ($heading) {
            return "<div class='adspirit_headline'>$heading</div>";
        }

        return '';
    }

}