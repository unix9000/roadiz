<?php

namespace RZ\Renzo\Core\Services;

use Pimple\Container;
use Symfony\Component\Routing\RouteCollection;
use RZ\Renzo\Core\Kernel;

/**
 * Register routing services for dependency injection container.
 */
class RoutingServiceProvider implements \Pimple\ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['backendClass'] = function ($c) {
            $theme = $c['em']->getRepository('RZ\Renzo\Core\Entities\Theme')
                             ->findOneBy(array('available'=>true, 'backendTheme'=>true));

            if ($theme !== null) {
                return $theme->getClassName();
            }

            return 'RZ\Renzo\CMS\Controllers\BackendController';
        };

        $container['frontendThemes'] = function ($c) {
            $themes = $c['em']->getRepository('RZ\Renzo\Core\Entities\Theme')
                              ->findBy(array(
                                  'available'=>    true,
                                  'backendTheme'=> false
                              ));



            if (count($themes) < 1) {

                $defaultTheme = new Theme();
                $defaultTheme->setClassName('RZ\Renzo\CMS\Controllers\FrontendController');
                $defaultTheme->setAvailable(true);

                return array(
                    $defaultTheme
                );
            } else {
                return $themes;
            }
        };

        if (isset($c['config']['install']) &&
            true === $c['config']['install']) {
            /*
             * Get Install routes
             */
            $container['routeCollection'] = function ($c) {

                $installClassname = static::INSTALL_CLASSNAME;
                $feCollection = $installClassname::getRoutes();
                $rCollection = new RouteCollection();
                $rCollection->addCollection($feCollection);

                return $rCollection;
            };
        } else {
            /*
             * Get App routes
             */
            $container['routeCollection'] = function ($c) {
                $rCollection = new RouteCollection();

                /*
                 * Add Assets controller routes
                 */
                $rCollection->addCollection(\RZ\Renzo\CMS\Controllers\AssetsController::getRoutes());

                /*
                 * Add Backend routes
                 */
                $beClass = $c['backendClass'];
                $cmsCollection = $beClass::getRoutes();
                if ($cmsCollection !== null) {
                    $rCollection->addCollection(
                        $cmsCollection,
                        '/rz-admin',
                        array('_scheme' => 'https')
                    );
                }

                /*
                 * Add Frontend routes
                 *
                 * return 'RZ\Renzo\CMS\Controllers\FrontendController';
                 */
                foreach ($c['frontendThemes'] as $theme) {
                    $feClass = $theme->getClassName();
                    $feCollection = $feClass::getRoutes();
                    if ($feCollection !== null) {

                        // set host pattern if defined
                        if ($theme->getHostname() != '*' &&
                            $theme->getHostname() != '') {

                            $feCollection->setHost($theme->getHostname());
                        }
                        $rCollection->addCollection($feCollection);
                    }
                }

                return $rCollection;
            };
        }
    }
}
