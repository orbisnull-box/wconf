<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace wConf;

use dTpl\View;

class NginxVhost
{
    /**
     * @var View
     */
    protected $render;
    protected $configDir = '/etc/nginx/sites-available';
    protected $fcginame = 'php-www';

    public function getRender()
    {
        if (is_null($this->render)) {
            $this->render = new View();
            $this->render->addTemplateDir('tpl');
        }
        return $this->render;
    }

    /**
     * @param string $configDir
     */
    public function setConfigDir($configDir)
    {
        $this->configDir = $configDir;
    }

    /**
     * @return string
     */
    public function getConfigDir()
    {
        return $this->configDir;
    }

    /**
     * @param string $fcginame
     */
    public function setFcginame($fcginame)
    {
        $this->fcginame = $fcginame;
    }

    /**
     * @return string
     */
    public function getFcginame()
    {
        return $this->fcginame;
    }

    public function createConfig($servername, $enable = true, $fcginame = null)
    {
        $confDir = $this->getConfigDir();
        $fcginame = $fcginame ?: $this->getFcginame();
        $render = $this->getRender();
        $render->assignArray(['servername' => $servername, 'fcginame' => $fcginame]);
        $template = 'nginx-vhost';
        $configText = $render->render($template);
        $file = $confDir . '/' . $servername;
        if (file_exists($file)) {
            throw new \RuntimeException("Config file for $servername exists");
        }
        $result = file_put_contents($file, $configText, LOCK_EX);
        if ($result === false) {
            throw new \RuntimeException("Error in write file: $file");
        }
        if ($enable) {
            $symlink = realpath($confDir . '/../' . 'sites-enabled');
            symlink($file, $symlink);
        }
    }


} 