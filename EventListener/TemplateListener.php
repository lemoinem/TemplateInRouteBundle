<?php

namespace WMC\TemplateInRouteBundle\EventListener;

use Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener as BaseListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Port of https://github.com/sensiolabs/SensioFrameworkExtraBundle/pull/266
 *
 * This class is strongly based on
 * Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener
 * by Fabien Potencier <fabien@symfony.com> (which is under the MIT license)
 */
class TemplateListener extends BaseListener
{
    /**
     * Guesses the template name to render and its variables and adds them to
     * the request object.
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $request = $event->getRequest();

        if (!($configuration = $request->attributes->get('_template'))) {
            return;
        }

        // This "if" is the section added to the original listener
        // It allows the _template configuration to be specified directly in
        // the route.
        if (is_string($configuration)) {
            $configuration = new Template(['value' => $configuration]);
        } elseif (true === $configuration) {
            $configuration = new Template([]);
        }

        if (!($configuration instanceof Template)) {
            return;
        }

        if (!$configuration->getTemplate()) {
            $guesser = $this->container->get('sensio_framework_extra.view.guesser');
            $configuration->setTemplate($guesser->guessTemplateName($controller, $request, $configuration->getEngine()));
        }

        $request->attributes->set('_template', $configuration->getTemplate());
        $request->attributes->set('_template_vars', $configuration->getVars());
        $request->attributes->set('_template_streamable', $configuration->isStreamable());

        // all controller method arguments
        if (!$configuration->getVars()) {
            $r = new \ReflectionObject($controller[0]);

            $vars = array();
            foreach ($r->getMethod($controller[1])->getParameters() as $param) {
                $vars[] = $param->getName();
            }

            $request->attributes->set('_template_default_vars', $vars);
        }
    }
}
