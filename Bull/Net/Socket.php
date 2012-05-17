<?php
/**
 *
 * Generalized Socket class.
 *
 * @package Bull.Net
 *
 */

class Bull_Net_Socket
{
    const NET_SOCKET_READ  = 1;
    const NET_SOCKET_WRITE = 2;
    const NET_SOCKET_ERROR = 4;
    /**
     *
     * Socket file pointer.
     *
     * @var resource $fp
     *
     */
    protected $fp = null;

    /**
     *
     * Whether the socket is blocking. Defaults to true.
     *
     * @var boolean $blocking
     *
     */
    protected $blocking = true;

    /**
     *
     * Whether the socket is persistent. Defaults to false.
     *
     * @var boolean $persistent
     *
     */
    protected $persistent = false;

    /**
     *
     * The IP address to connect to.
     *
     * @var string $addr
     *
     */
    protected $addr = '';

    /**
     *
     * The port number to connect to.
     *
     * @var integer $port
     *
     */
    protected $port = 0;

    /**
     *
     * Number of seconds to wait on socket connections before assuming
     * there's no more data. Defaults to no timeout.
     *
     * @var integer $timeout
     *
     */
    protected $timeout = false;

    /**
     *
     * Number of bytes to read at a time in readLine() and
     * readAll(). Defaults to 2048.
     *
     * @var integer $line_length
     *
     */
    protected $line_length = 2048;

    /**
     *
     * The string to use as a newline terminator. Usually "\r\n" or "\n".
     *
     * @var string $newline
     *
     */
    protected $newline = "\r\n";

    /**
     *
     * Connect to the specified port. If called when the socket is
     * already connected, it disconnects and connects again.
     *
     * @param string  $addr       IP address or host name.
     * @param integer $port       TCP port number.
     * @param boolean $persistent (optional) Whether the connection is
     *                            persistent (kept open between requests
     *                            by the web server).
     * @param integer $timeout    (optional) How long to wait for data.
     * @param array   $options    See options for stream_context_create.
     *
     * @access public
     *
     * @return boolean True on success
     *
     */
    public function connect($addr, $port = 0, $persistent = null,
                     $timeout = null, $options = null)
    {
        if (is_resource($this->fp)) {
            @fclose($this->fp);
            $this->fp = null;
        }

        if (!$addr) {
            return $this->raiseError('$addr cannot be empty');
        } elseif (strspn($addr, '.0123456789') == strlen($addr)
                  || strstr($addr, '/') !== false) {
            $this->addr = $addr;
        } else {
            $this->addr = @gethostbyname($addr);
        }

        $this->port = $port % 65536;

        if ($persistent !== null) {
            $this->persistent = $persistent;
        }

        if ($timeout !== null) {
            $this->timeout = $timeout;
        }

        $openfunc = $this->persistent ? 'pfsockopen' : 'fsockopen';
        $errno    = 0;
        $errstr   = '';

        $old_track_errors = @ini_set('track_errors', 1);

        if ($options && function_exists('stream_context_create')) {
            if ($this->timeout) {
                $timeout = $this->timeout;
            } else {
                $timeout = 0;
            }
            $context = stream_context_create($options);

            // Since PHP 5 fsockopen doesn't allow context specification
            if (function_exists('stream_socket_client')) {
                $flags = STREAM_CLIENT_CONNECT;

                if ($this->persistent) {
                    $flags = STREAM_CLIENT_PERSISTENT;
                }

                $addr = $this->addr . ':' . $this->port;
                $fp   = stream_socket_client($addr, $errno, $errstr,
                                             $timeout, $flags, $context);
            } else {
                $fp = @$openfunc($this->addr, $this->port, $errno,
                                 $errstr, $timeout, $context);
            }
        } else {
            if ($this->timeout) {
                $fp = @$openfunc($this->addr, $this->port, $errno,
                                 $errstr, $this->timeout);
            } else {
                $fp = @$openfunc($this->addr, $this->port, $errno, $errstr);
            }
        }

        if (!$fp) {
            if ($errno == 0 && !strlen($errstr) && isset($php_errormsg)) {
                $errstr = $php_errormsg;
            }
            @ini_set('track_errors', $old_track_errors);
            return $this->raiseError($errstr, $errno);
        }

        @ini_set('track_errors', $old_track_errors);
        $this->fp = $fp;

        return $this->setBlocking($this->blocking);
    }

    /**
     *
     * Disconnects from the peer, closes the socket.
     *
     * @access public
     *
     * @return mixed true on success or trigger error instance otherwise
     *
     */
    public function disconnect()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        @fclose($this->fp);
        $this->fp = null;
        return true;
    }

    /**
     *
     * Set the newline character/sequence to use.
     *
     * @param string $newline  Newline character(s)
     *
     * @return boolean True
     *
     */
    public function setNewline($newline)
    {
        $this->newline = $newline;
        return true;
    }

    /**
     *
     * Find out if the socket is in blocking mode.
     *
     * @access public
     *
     * @return boolean  The current blocking mode.
     *
     */
    public function isBlocking()
    {
        return $this->blocking;
    }

    /**
     *
     * Sets whether the socket connection should be blocking or
     * not. A read call to a non-blocking socket will return immediately
     * if there is no data available, whereas it will block until there
     * is data for blocking sockets.
     *
     * @param boolean $mode True for blocking sockets, false for nonblocking.
     *
     * @access public
     *
     * @return mixed true on success or trigger error otherwise
     *
     */
    public function setBlocking($mode)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $this->blocking = $mode;
        stream_set_blocking($this->fp, (int)$this->blocking);
        return true;
    }

    /**
     *
     * Sets the timeout value on socket descriptor,
     * expressed in the sum of seconds and microseconds
     *
     * @param integer $seconds      Seconds.
     *
     * @param integer $microseconds Microseconds.
     *
     * @access public
     *
     * @return mixed true on success or trigger_error otherwise
     *
     */
    public function setTimeout($seconds, $microseconds)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        return socket_set_timeout($this->fp, $seconds, $microseconds);
    }

    /**
     *
     * Sets the file buffering size on the stream.
     * See php's stream_set_write_buffer for more information.
     *
     * @param integer $size Write buffer size.
     *
     * @access public
     *
     * @return mixed on success or trigger error otherwise
     *
     */
    public function setWriteBuffer($size)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $returned = stream_set_write_buffer($this->fp, $size);
        if ($returned == 0) {
            return true;
        }
        return $this->raiseError('Cannot set write buffer.');
    }

    /**
     *
     * Returns information about an existing socket resource.
     * Currently returns four entries in the result array:
     *
     * - timed_out (bool) - The socket timed out waiting for data
     * - blocked (bool) - The socket was blocked
     * - eof (bool) - Indicates EOF event
     * - unread_bytes (int) - Number of bytes left in the socket buffer
     *
     * @access public
     *
     * @return mixed Array containing information about existing socket
     *               resource or trigger error otherwise
     *
     */
    public function getStatus()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        return socket_get_status($this->fp);
    }

    /**
     *
     * Get a specified line of data
     *
     * @param int $size ?
     *
     * @access public
     *
     * @return $size bytes of data from the socket, or trigger_error if
     *         not connected.
     *
     */
    public function gets($size = null)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        if (is_null($size)) {
            return @fgets($this->fp);
        } else {
            return @fgets($this->fp, $size);
        }
    }

    /**
     *
     * Read a specified amount of data. This is guaranteed to return,
     * and has the added benefit of getting everything in one fread()
     * chunk; if you know the size of the data you're getting
     * beforehand, this is definitely the way to go.
     *
     * @param integer $size The number of bytes to read from the socket.
     *
     * @access public
     *
     * @return $size bytes of data from the socket, or trigger error if
     *         not connected.
     *
     */
    public function read($size)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        return @fread($this->fp, $size);
    }

    /**
     *
     * Write a specified amount of data.
     *
     * @param string  $data      Data to write.
     *
     * @param integer $blocksize Amount of data to write at once.
     *                           NULL means all at once.
     *
     * @access public
     *
     * @return mixed If the socket is not connected, trigger an error
     *               If the write succeeds, returns the number of bytes written
     *               If the write fails, returns false.
     *
     */
    public function write($data, $blocksize = null)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        if (is_null($blocksize) && !OS_WINDOWS) {
            return @fwrite($this->fp, $data);
        } else {
            if (is_null($blocksize)) {
                $blocksize = 1024;
            }
            $pos  = 0;
            $size = strlen($data);
            while ($pos < $size) {
                $written = @fwrite($this->fp, substr($data, $pos, $blocksize));
                if (!$written) {
                    return $written;
                }
                $pos += $written;
            }
            return $pos;
        }
    }

    /**
     *
     * Write a line of data to the socket, followed by a trailing newline.
     *
     * @param string $data Data to write
     *
     * @access public
     *
     * @return mixed fputs result, or an error
     *
     */
    public function writeLine($data)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        return fwrite($this->fp, $data . $this->newline);
    }

    /**
     *
     * Tests for end-of-file on a socket descriptor.
     *
     * Also returns true if the socket is disconnected.
     *
     * @access public
     *
     * @return bool
     *
     */
    public function eof()
    {
        return (!is_resource($this->fp) || feof($this->fp));
    }

    /**
     *
     * Reads a byte of data
     *
     * @access public
     *
     * @return 1 byte of data from the socket, or trigger error if
     *         not connected.
     *
     */
    public function readByte()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        return ord(@fread($this->fp, 1));
    }

    /**
     *
     * Reads a word of data
     *
     * @access public
     *
     * @return 1 word of data from the socket, or trigger error if
     *         not connected.
     *
     */
    public function readWord()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $buf = @fread($this->fp, 2);
        return (ord($buf[0]) + (ord($buf[1]) << 8));
    }

    /**
     *
     * Reads an int of data
     *
     * @access public
     *
     * @return integer  1 int of data from the socket, or a trigger error if
     *                  not connected.
     *
     */
    public function readInt()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $buf = @fread($this->fp, 4);
        return (ord($buf[0]) + (ord($buf[1]) << 8) +
                (ord($buf[2]) << 16) + (ord($buf[3]) << 24));
    }

    /**
     *
     * Reads a zero-terminated string of data
     *
     * @access public
     *
     * @return string, or trigger error if
     *         not connected.
     *
     */
    public function readString()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $string = '';
        while (($char = @fread($this->fp, 1)) != "\x00") {
            $string .= $char;
        }
        return $string;
    }

    /**
     *
     * Reads an IP Address and returns it in a dot formatted string
     *
     * @access public
     *
     * @return Dot formatted string, or trigger error if
     *         not connected.
     *
     */
    public function readIPAddress()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $buf = @fread($this->fp, 4);
        return sprintf('%d.%d.%d.%d', ord($buf[0]), ord($buf[1]),
                       ord($buf[2]), ord($buf[3]));
    }

    /**
     *
     * Read until either the end of the socket or a newline, whichever
     * comes first. Strips the trailing newline from the returned data.
     *
     * @access public
     *
     * @return All available data up to a newline, without that
     *         newline, or until the end of the socket, or trigger error if
     *         not connected.
     *
     */
    public function readLine()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $line = '';

        $timeout = time() + $this->timeout;

        while (!feof($this->fp) && (!$this->timeout || time() < $timeout)) {
            $line .= @fgets($this->fp, $this->line_length);
            if (substr($line, -1) == "\n") {
                return rtrim($line, $this->newline);
            }
        }
        return $line;
    }

    /**
     *
     * Read until the socket closes, or until there is no more data in
     * the inner PHP buffer. If the inner buffer is empty, in blocking
     * mode we wait for at least 1 byte of data. Therefore, in
     * blocking mode, if there is no data at all to be read, this
     * function will never exit (unless the socket is closed on the
     * remote end).
     *
     * @access public
     *
     * @return string  All data until the socket closes, or trigger error if
     *                 not connected.
     *
     */
    public function readAll()
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $data = '';
        while (!feof($this->fp)) {
            $data .= @fread($this->fp, $this->line_length);
        }
        return $data;
    }

    /**
     *
     * Runs the equivalent of the select() system call on the socket
     * with a timeout specified by tv_sec and tv_usec.
     *
     * @param integer $state   Which of read/write/error to check for.
     * @param integer $tv_sec  Number of seconds for timeout.
     * @param integer $tv_usec Number of microseconds for timeout.
     *
     * @access public
     *
     * @return False if select fails, integer describing which of read/write/error
     *         are ready, or trigger error if not connected.
     *
     */
    public function select($state, $tv_sec, $tv_usec = 0)
    {
        if (!is_resource($this->fp)) {
            return $this->raiseError('not connected');
        }

        $read   = null;
        $write  = null;
        $except = null;
        if ($state & self::NET_SOCKET_READ) {
            $read[] = $this->fp;
        }
        if ($state & self::NET_SOCKET_WRITE) {
            $write[] = $this->fp;
        }
        if ($state & self::NET_SOCKET_ERROR) {
            $except[] = $this->fp;
        }
        if (false === ($sr = stream_select($read, $write, $except,
                                          $tv_sec, $tv_usec))) {
            return false;
        }

        $result = 0;
        if (count($read)) {
            $result |= self::NET_SOCKET_READ;
        }
        if (count($write)) {
            $result |= self::NET_SOCKET_WRITE;
        }
        if (count($except)) {
            $result |= self::NET_SOCKET_ERROR;
        }
        return $result;
    }

    /**
     *
     * Turns encryption on/off on a connected socket.
     *
     * @param bool    $enabled Set this parameter to true to enable encryption
     *                         and false to disable encryption.
     *
     * @param integer $type    Type of encryption. See stream_socket_enable_crypto()
     *                         for values.
     *
     * @see    http://se.php.net/manual/en/function.stream-socket-enable-crypto.php
     *
     * @access public
     *
     * @return false on error, true on success and 0 if there isn't enough data
     *         and the user should try again (non-blocking sockets only).
     *         It will trigger error if the socket is not
     *         connected
     *
     */
    public function enableCrypto($enabled, $type)
    {
        if (version_compare(phpversion(), "5.1.0", ">=")) {
            if (!is_resource($this->fp)) {
                return $this->raiseError('not connected');
            }
            return @stream_socket_enable_crypto($this->fp, $enabled, $type);
        } else {
            $msg = 'Bull_Net_Socket::enableCrypto() requires php version >= 5.1.0';
            return $this->raiseError($msg);
        }
    }
    
    /**
     *
     * Error method for this class.
     *
     * @return false
     *
     */
    public function raiseError($error)
    {
        trigger_error($error, E_USER_WARNING);
        return false;
    }
}
