<?php 

namespace Aham\Helpers;

/**
 * Basic helper class to be used for SSO authentication with Disqus.
 */
class DisqusHelper {

    /**
     * Secret Disqus API key
     * 
     * @var string
     */
    protected $privateKey;


    /**
     * Public Disqus API key
     * 
     * @var string
     */
    protected $publicKey;


    /**
     * Creates a new Disqus instance                      
     */
    public function __construct()
    {
        $this->privateKey = 'jPrLlZx0xq0C6tMPiK53zMwX0kWQh7dx9C39LBJuxL6wYPiZBniiEYRb04JeonRF';
        $this->publicKey = 'PRtUpcnCNfCaBeDcoW0XmHZKUT9WmjNxBeOd5pPvPL3bT7L2BrE5GZfRyqvNq8gB';
    }


    /**
     * The final payload that must be sent to Disqus in order to associate the user.
     * Example usage: this.page.remote_auth_s3 = "<?php echo DisqusAuth::payload(); ?>";
     *
     * @param  string $userData The user data to authenticate with. Only 'id', 'username' and 'email' are used.
     * 
     * @return string
     */
    public function payload($userData)
    {
        if ( ! is_array($userData) )
        {
            $userData = $userData->toArray();
        }

        // Only these are supported by Disqus
        // See: https://help.disqus.com/customer/portal/articles/236206-single-sign-on#user-data
        $userData = array_only($userData, ['id', 'username', 'email', 'avatar', 'url']);

        $timestamp = time();
        $encodedData = $this->getEncodedData($userData);

        return $encodedData . ' ' . $this->getHMAC($encodedData, $timestamp) . ' ' . $timestamp;
    }


    /**
     * The public API key.
     * Example usage: this.page.api_key = "<?php echo DisqusAuth::publicKey(); ?>";
     * 
     * @return string
     */
    public function publicKey()
    {
        return $this->publicKey;
    }


    /**
     * Base64 encoded string of the JSON encoded user data.
     * 
     * @param  string $userData The data to encode
     * @return string
     */
    private function getEncodedData(array $userData)
    {
        return base64_encode(json_encode($userData));
    }


    /**
     * Disqus specific encrypted hash of <encoded user data> <timestamp>.
     * 
     * @param  string $encodedData The encoded user data
     * @param  integer $timestamp Unix timestamp
     * @return string
     */
    protected function getHMAC($encodedData, $timestamp)
    {
        $message = $encodedData . ' ' . $timestamp;
        
        return hash_hmac('sha1', $message, $this->privateKey);
    }
}