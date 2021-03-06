<?php
/**
 * Copyright (c) 2017. Ambroise Maupate and Julien Blanchet
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name of the ROADIZ shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization from Ambroise Maupate and Julien Blanchet.
 *
 * @file HandlerExtension.php
 * @author Ambroise Maupate <ambroise@rezo-zero.com>
 */

namespace RZ\Roadiz\Utils\TwigExtensions;

use RZ\Roadiz\Core\AbstractEntities\AbstractEntity;
use RZ\Roadiz\Core\Handlers\HandlerFactory;

/**
 * Class HandlerExtension
 * @package RZ\Roadiz\Utils\TwigExtensions
 */
class HandlerExtension extends \Twig_Extension
{
    /**
     * @var HandlerFactory
     */
    private $handlerFactory;

    /**
     * HandlerExtension constructor.
     * @param HandlerFactory $handlerFactory
     */
    public function __construct(HandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    public function getName()
    {
        return 'handlerExtension';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('handler', [$this, 'getHandler']),
        ];
    }

    /**
     * @param $mixed
     * @return \RZ\Roadiz\Core\Handlers\AbstractHandler|null
     * @throws \Twig_Error_Runtime
     */
    public function getHandler($mixed)
    {
        if (null === $mixed) {
            return null;
        }

        if ($mixed instanceof AbstractEntity) {
            try {
                return $this->handlerFactory->getHandler($mixed);
            } catch (\InvalidArgumentException $exception) {
                throw new \Twig_Error_Runtime($exception->getMessage(), -1, null, $exception);
            }
        }

        throw new \Twig_Error_Runtime('Handler filter only supports AbstractEntity objects.');
    }
}
