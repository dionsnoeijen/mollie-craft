<?php

namespace Craft;

/**
 * Mollie Plugin controller
 */
class Mollie_InstallController extends BaseController
{
    /**
     * Dependencies
     *
     * @return null
     */
    public function actionIndex()
    {
        $pluginDependencies = craft()->plugins->getPlugin('mollie')->getPluginDependencies();

        if (count($pluginDependencies) > 0) {

            $this->renderTemplate('mollie/_install/dependencies', ['pluginDependencies' => $pluginDependencies]);
        } else {

            $this->redirect('settings/plugins/mollie');
        }
    }
}