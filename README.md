WMCRouteInTemplateBundle
========================

This bundle extends
[SensioFrameworkExtraBundle](https://github.com/sensiolabs/SensioFrameworkExtraBundle)
and is a port of the PR
[SensioFrameworkExtraBundle#266](https://github.com/sensiolabs/SensioFrameworkExtraBundle/pull/266).

Installation
------------

The bundle can be installed via composer:

     php composer.phar require wemakecustom/template-in-route-bundle 

You then simply need to enable it in your AppKernel:

     new WMC\RouteInTemplateBundle\WMCTemplateInRouteBundle

Usage
-----

This bundle allow the template to be specified directly in the Route via a
``_template`` attribute, just like if the ``@Template`` annotation was used.

     my_route:
        path: /{product}-awesome
        defaults: { _controller: Bundle:Product:show, _template: Bundle:Product:show-awesome.html.twig}

If the template is named after the controller and action names, you can even
skip the value for the ``_template`` parameter:

     my_route:
        path: /{product}-awesome
        defaults: { _controller: Bundle:Product:showAwesome, _template: true }

This will use the template guesser, same as specifying ``@Template`` without a
template name.

When specified through the route, the ``_template`` parameter will be removed
from ``_route_params``.

This is especially useful if Routes are generated programatically or if the same
controller renders several routes requiring different templates.
