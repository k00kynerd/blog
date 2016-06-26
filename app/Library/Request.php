<?php
namespace Library;

class Request
{

    /** @var string */
    protected $method;

    /** @var  string */
    protected $uri;

    /** @var array */
    protected $get;

    /** @var array */
    protected $post;

    /** @var string */
    protected $rawBody;

    /** @var  array */
    protected $headers;


    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->method = $method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_REQUEST['_url'];
        $this->get = &$_GET;
        $this->post = &$_POST;
        $this->rawBody = file_get_contents('php://input');
        $this->headers = $this->getAllHeaders();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->getRequestArrayValue($this->get, $key, $default);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function post($key, $default = null)
    {
        return $this->getRequestArrayValue($this->post, $key, $default);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getHeader($key, $default = null)
    {
        return $this->getRequestArrayValue($this->headers, $key, $default);
    }

    /**
     * @return string
     */
    public function getRawBody()
    {
        return $this->rawBody;
    }

    /**
     * @return mixed|null
     */
    public function getBodyJson()
    {
        $object = json_decode($this->rawBody, true);
        if ($object === false) {
            return null;
        }
        return $object;
    }

    /**
     * @param $method
     * @return bool
     */
    public function is($method)
    {
        return ($this->method === strtoupper($method));
    }

    /**
     * @return array
     */
    private function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * @param $array
     * @param $key
     * @param $default
     * @return mixed
     */
    private function getRequestArrayValue(&$array, $key, $default)
    {
        if (isset($array[$key])) {
            return $array[$key];
        } else {
            return $default;
        }
    }
}