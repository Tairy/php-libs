<?php
/**
 *
 * HttpClient.php
 * @author Tairy <tairyguo@gmail.com>
 * @license Apache 2.0
 *
 */

class HttpClient
{
    /**
     * _url
     *
     * @var string
     * @access private
     */
    private $_url;

    /**
     * _agent
     *
     * @var string
     * @access private
     */
    private $_agent = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)';

    /**
     * _header
     *
     * @var string
     * @access private
     */
    private $_header;

    /**
     * _timeout
     *
     * @var int
     * @access private
     */
    private $_timeout = 30;

    /**
     * _method
     *
     * @var string
     * @access private
     */
    private $_method;

    /**
     * _referer
     *
     * @var string
     * @access private
     */
    private $_referer;

    /**
     * _options
     *
     * @var array
     * @access private
     */
    private $_options = [];

    /**
     * _data
     *
     * @var array
     * @accesss privare
     */
    private $_data = [];



    /**
     * @param $url
     * @access public
     */
    public function __construct($url)
    {
        $this->_url = $url;
    }

    /**
     * @param $timeout
     * @access public
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
        return $this;
    }

    /**
     * @param $method
     * @access public
     * @return $this
     */
    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }

    /**
     * @param $header
     * @access public
     * @return $this
     */
    public function setHeader($header)
    {
        $this->_header = $header;
        return $this;
    }

    /**
     * @param $referer
     * @access public
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->_referer = $referer;
        return $this;
    }

    /**
     * @param $agent
     * @access public
     * @return $this
     */
    public function setAgent($agent)
    {
        $this->_agent = $agent;
        return $this;
    }

    /**
     * @param array $options
     * @access public
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->_options = $options;
        return $this;
    }

    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * @access private
     */
    private function request()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_agent);

        // disable ssl check
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if(!empty($this->_options)) {
            foreach ($this->_options as $key => $option) {
                curl_setopt($ch, $key, $option);
            }
        }

        if (!empty($this->_method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->_method);
        }

        if(!empty($this->_data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_data);
        }

        $result = @curl_exec($ch);

        if (false === $result) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('Curl error: ' . $error);
        }

        curl_close($ch);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function post()
    {
        $this->request();
        return $this;
    }
}