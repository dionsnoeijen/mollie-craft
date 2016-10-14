<?php

namespace Craft;

/**
 * Are the required dependencies available?
 *
 * Class Mollie_PluginService
 * @package Craft
 */
class Mollie_PluginService extends BaseApplicationComponent
{
    /**
     * @return bool
     */
    public function requireDependencies()
    {
        $plugin = craft()->plugins->getPlugin('mollie');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            $url = UrlHelper::getUrl('mollie/install');
            craft()->request->redirect($url);

            return false;
        } else {
            return true;
        }
    }
}