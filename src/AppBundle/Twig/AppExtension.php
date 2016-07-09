<?php

namespace AppBundle\Twig;

use AppBundle\Utils\Markdown;

/**
 * Twig扩展类
 * Twig模板基本编写方法可查看
 * http://twig.sensiolabs.org/doc/advanced_legacy.html#creating-an-extension
 * Symfony3的Twig扩展编写方法可查看
 * http://symfony.com/doc/current/cookbook/templating/twig_extension.html
 * Class AppExtension
 * @package AppBundle\Twig
 */
class AppExtension extends \Twig_Extension
{
    /**
     * @var Markdown
     */
    private $parser;

    public function __construct(Markdown $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('md2html', [$this, 'markdownToHtml'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Transforms the given Markdown content into HTML content.
     *
     *  @param string $content
     *
     * @return string
     */
    public function markdownToHtml($content)
    {
        return $this->parser->toHtml($content);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // the name of the Twig extension must be unique in the application. Consider
        // using 'app.extension' if you only have one Twig extension in your application.
        return 'app.extension';
    }
}
