<?php

/* test2.html */
class __TwigTemplate_9632b0b966a9748bbe1f740683196fa7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8' />
  </head>
  <body>
    <div>Test2: ";
        // line 7
        if (isset($context["fuck"])) { $_fuck_ = $context["fuck"]; } else { $_fuck_ = null; }
        echo twig_escape_filter($this->env, $_fuck_, "html", null, true);
        echo "</div>
  </body>
</html>

";
    }

    public function getTemplateName()
    {
        return "test2.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  25 => 7,  17 => 1,);
    }
}
