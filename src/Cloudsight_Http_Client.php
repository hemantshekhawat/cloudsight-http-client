<?php
/**
 * HTTP client for the CloudSight API, with mock API functionality.
 *
 * (This library is NOT endorsed by CloudSight.)
 *
 * @author Nick Freear, 2 May 2015.
 * @copyright 2015 Nick Freear.
 *
 * @link  http://cloudsight.readme.io/v1.0/docs
 * @link  https://gist.github.com/nfreear/ac98e809b574948a47ab
 */

namespace Nfreear\Cloudsight;

class Cloudsight_Http_Client extends \Net_Http_Client
{
    private $cs_api_key;
    protected $cs_is_mock;
    protected $cs_input;
    protected $cs_is_complete = false;

    const BASE = 'https://api.cloudsightapi.com/';


    public function __construct($api_key = null, $is_mock = false)
    {
        parent::__construct();

        $this->cs_api_key = $api_key;
        $this->cs_is_mock = (bool)$is_mock;

        $this->setHeader('Authorization', 'CloudSight '. $this->cs_api_key);
        $this->setHeader('Accept', 'application/json');
    }

    /** Call me once to submit the image, and get a token.
     *
     * @param  string or array
     * @return object Object including the `token`
     */
    public function postImageRequests($post_data_or_url)
    {
        $this->cs_input = $this->csPostData($post_data_or_url);

        if ($this->isMock()) {
            return $this->mockImageRequests();
        }
        $this->setHeader('Content-Type', 'multipart/form-data');
        $this->post(self::BASE . 'image_requests', $this->cs_input);
        return $this->csParseJson();
    }

    /** Poll me multiple times, until I return 'completed' or an error.
     *
     * @param  string $token Token from `post_image_requests` call.
     * @param  int $mock_count Optional counter for mock requests.
     * @return object Object
     */
    public function getImageResponses($token, $mock_count = 0)
    {
        if ($this->isMock()) {
            $result = $this->mockImageResponses($mock_count);
        } else {
            $this->get(sprintf(self::BASE . 'image_responses/%s', $token));
            $result = $this->csParseJson();
        }
        $this->cs_is_complete = ('not completed' !== $result->status);
        return $result;
    }

    /** Is image analysis complete?
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->cs_is_complete;
    }

    /** Convenience method to show raw data from cURL.
     *  CAUTION: exposes your API key - don't use in production!
     */
    public function debugHeaders()
    {
        header('X-cs-api-key: '. json_encode($this->cs_api_key));
        header('X-cs-input: '. json_encode($this->cs_input));
        if ($this->isMock()) {
            return;
        }
        header('X-cs-headers: '. json_encode($this->getHeaders()));
        header('X-cs-info: '. json_encode($this->getInfo()));
    }

    /** Helper to prepare post_data for `post_image_requests`
     *
     * @param array or string
     * @return array
     */
    protected function csPostData($data)
    {
        if (is_string($data)) {
            $data = array(
                'remote_image_url' => $data,
                'locale' => 'en-US',
            );
        }
        $result = array();
        foreach ($data as $key => $value) {
            $result[ \sprintf('image_request[%s]', $key) ] = $value;
        }
        return $result;
    }

    public function getPostData()
    {
        return (object) $this->cs_input;
    }

    /** {@inheritdoc}
    */
    public function getStatus()
    {
        return $this->isMock() ? 200 : parent::getStatus();
    }


    // ==================================================

    protected function csParseJson()
    {
        return 200 == $this->getStatus() ? (object) json_decode($this->getBody()) : null;
    }

    protected function isMock()
    {
        return $this->cs_is_mock;
    }

    protected function mockImageRequests()
    {
        return (object) array(
            'mock' => true,
            'url' => "//images.cloudsightapi.com/uploads/image_request/__/Image.jpg",
            'token' => 'Mock_AJKAWHKGLjqMd9KDNIXQfg_' . time(),
        );
    }

    protected function mockImageResponses($count)
    {
        $count = intval($count);
        $rand = mt_rand(2, 7);
        $mock_resp = (object) array(
            'mock' => true,
            'mock_rand' => $rand,
            'mock_count' => $count,
            'status' => 'not completed',
        );
        if ($count < $rand) {
            return $mock_resp;
        }
        $mock_resp->status = 'completed';
        $mock_resp->name = 'red beats by dre headphones';
        return $mock_resp;
    }
}
