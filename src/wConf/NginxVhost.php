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
    protected $publicDir = 'public';

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

    /**
     * @param string $publicDir
     */
    public function setPublicDir($publicDir)
    {
        $this->publicDir = $publicDir;
    }

    /**
     * @return string
     */
    public function getPublicDir()
    {
        return $this->publicDir;
    }

    public function createConfig($servername, $public = null, $enable = true, $fcginame = null)
    {
        $confDir = $this->getConfigDir();
        $fcginame = $fcginame ?: $this->getFcginame();
        $public = $public ?: $this->getPublicDir();
        $render = $this->getRender();
        $render->assignArray(['servername' => $servername, 'fcginame' => $fcginame, 'public' => $public]);
        $template = 'nginx-vhost';
        $configText = $render->render($template);
        $file = $confDir . '/' . $servername;
        if (file_exists($file)) {
            throw new \RuntimeException("Config file for $servername exists");
        }
        $result = file_put_contents($file, $configText);
        if ($result === false) {
            throw new \RuntimeException("Error in write file: $file");
        }
        if ($enable) {
            exec("ngxensite $servername");
            exec("nginx -s reload");
        }
        mkdir("/var/www/$servername/$public", 0755, true);
        return $configText;
    }


} 