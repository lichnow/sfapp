<?php
namespace AppBundle\Utils;
/*
 * 编写Markdown扩展
 * Markdown类库使用http://parsedown.org/
 * 过滤XSS代码http://htmlpurifier.org/docs
 */
class Markdown
{
    /**
     * @var \Parsedown
     */
    private $parser;

    /**
     * @var \HTMLPurifier
     */
    private $purifier;

    public function __construct()
    {
        $this->parser = new \Parsedown();

        $purifierConfig = \HTMLPurifier_Config::create([
            'Cache.DefinitionImpl' => null, // Disable caching
        ]);
        $this->purifier = new \HTMLPurifier($purifierConfig);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function toHtml($text)
    {
        $html = $this->parser->text($text);
        $safeHtml = $this->purifier->purify($html);

        return $safeHtml;
    }
}
