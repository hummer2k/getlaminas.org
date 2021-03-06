<?php

namespace GetLaminas\Blog;

use DateTimeInterface;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Container\ContainerInterface;

class PlatesFunctionsDelegator implements ExtensionInterface
{
    public $template;

    private $engine;

    public function __invoke(ContainerInterface $container, $name, callable $factory)
    {
        $engine = $factory();
        $engine->loadExtension($this);
        return $engine;
    }

    public function register(Engine $engine) : void
    {
        $engine->registerFunction('formatDate', [$this, 'formatDate']);
        $engine->registerFunction('formatDateRfc', [$this, 'formatDateRfc']);
        $engine->registerFunction('postUrl', [$this, 'postUrl']);
    }

    public function formatDate(DateTimeInterface $date, string $format = 'j F Y') : string
    {
        return $date->format($format);
    }

    public function formatDateRfc(DateTimeInterface $date) : string
    {
        return $this->formatDate($date, 'c');
    }

    public function postUrl(BlogPost $post) : string
    {
        return $this->template->url('blog.post', ['id' => $post->id]);
    }
}
