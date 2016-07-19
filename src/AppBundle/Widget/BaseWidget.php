<?php
/**
 * Created by PhpStorm.
 * User: lichnow
 * Date: 16/7/15
 * Time: 下午12:15
 */

namespace AppBundle\Widget;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseWidget
{
    protected $container;
    protected $router;
    protected $doctrine;
    protected $em;
    public function __construct(ContainerInterface $container)
    {
        $this->router = $container->get('router');
        $this->doctrine = $container->get('doctrine');
        $this->em = $this->doctrine->getManager();
        $this->container = $container;
    }

    /**
     * 此类为symfony controller基类中拷贝用于doctrine数据的json格式转换
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     *
     * @param mixed $data    The response data
     * @param int   $status  The status code to use for the Response
     * @param array $headers Array of extra headers to add
     * @param array $context Context to pass to serializer when using serializer component
     *
     * @return JsonResponse
     */
    protected function json($data, $status = 200, $headers = array(), $context = array())
    {
        if ($this->container->has('serializer')) {
            $json = $this->container->get('serializer')->serialize($data, 'json', array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ], $context));

            return new JsonResponse($json, $status, $headers, true);
        }

        return new JsonResponse($data, $status, $headers);
    }
}