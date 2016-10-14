<?php

namespace Craft;

class MolliePlugin extends BasePlugin
{

    /**
     * Plugin metadata
     */
    public function getName() { return Craft::t('Mollie'); }
    public function getVersion() { return '1.0.0'; }
    public function getSchemaVersion() { return '1.0.0'; }
    public function getDeveloper() { return 'Dion Snoeijen'; }
    public function getDeveloperUrl() { return 'http://dionsnoeijen.nl'; }
    public function getPluginUrl() { return ''; }
    public function getDocumentationUrl() { return ''; }

    /**
     * Initialize composer autoloader
     */
    public function init()
    {
        require CRAFT_PLUGINS_PATH . 'mollie/vendor/autoload.php';
    }

    /**
     * Enable cp section
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }

    /**
     * Get settings page
     *
     * @return mixed
     */
    public function getSettingsUrl()
    {
        return 'mollie/settings';
    }

    /**
     * Called before save to database
     *
     * @param array $settings
     * @return array
     */
    public function prepSettings($settings)
    {
        // Modify $settings here...

        return $settings;
    }

    /**
     * Returns required plugins
     *
     * @return array Required plugins
     */
    public function getRequiredPlugins()
    {
        return array(
            array(
                'name' => "OAuth",
                'handle' => 'oauth',
                'url' => 'https://dukt.net/craft/oauth',
                'version' => '1.0.0'
            )
        );
    }

    /**
     * Get Plugin Dependencies
     */
    public function getPluginDependencies($missingOnly = true)
    {
        $dependencies = array();

        $plugins = $this->getRequiredPlugins();

        foreach($plugins as $key => $plugin)
        {
            $dependency = $this->getPluginDependency($plugin);

            if($missingOnly)
            {
                if($dependency['isMissing'])
                {
                    $dependencies[] = $dependency;
                }
            }
            else
            {
                $dependencies[] = $dependency;
            }
        }

        return $dependencies;
    }

    /**
     * Hook Register CP Routes
     */
    public function registerCpRoutes()
    {
        return array(
            'mollie/settings' => array('action' => "mollie/settings"),
            'mollie/install'  => array('action' => "mollie/install/index"),
        );
    }

    /**
     * Get OAuth Providers
     */
    public function getOauthProviders()
    {
        require CRAFT_PLUGINS_PATH.'mollie/providers/Mollie.php';

        return [
            'Mollie\Providers\Mollie'
        ];
    }

    /**
     * Get Plugin Dependency
     * @param $dependency
     * @return
     */
    private function getPluginDependency($dependency)
    {
        $isMissing = true;

        $plugin = craft()->plugins->getPlugin($dependency['handle'], false);

        if($plugin)
        {
            $currentVersion = $plugin->version;


            // requires update ?

            if(version_compare($currentVersion, $dependency['version']) >= 0)
            {
                // no (requirements OK)

                if($plugin->isInstalled && $plugin->isEnabled)
                {
                    $isMissing = false;
                }
            }
            // ELSE: yes (requirement not OK)
        }
        // ELSE: Not installed

        $dependency['isMissing'] = $isMissing;
        $dependency['plugin'] = $plugin;
        $dependency['pluginLink'] = 'https://dukt.net/craft/'.$dependency['handle'];

        return $dependency;
    }

    /**
     * Defines the settings.
     *
     * @access protected
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'tokenId' => array(AttributeType::Number),
        );
    }
}