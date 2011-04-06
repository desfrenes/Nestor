<?php
class Services
{
    /**
     * Retourne une liste des services disponibles sur ce serveur
     *
     * <code>
     * $services = $proxy->getServices();
     * </code>
     * @return array
     */
    public function getServices()
    {
        $files = glob(dirname(__FILE__) . '/*.php');
        foreach($files as $k => $file)
        {
            $files[$k] = basename($file, '.php');
        }
        return $files;
    }
}
