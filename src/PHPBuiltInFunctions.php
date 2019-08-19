<?php
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpUnused */


namespace HJerichen\ProphecyPHP;


use Prophecy\Prophecy\MethodProphecy;

/**
 * Class PHPBuiltInFunctions
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
trait PHPBuiltInFunctions
{
    /**
     * @param string $functionName
     * @param array $arguments
     * @return MethodProphecy
     */
    abstract public function __call(string $functionName, array $arguments): MethodProphecy;


    /**
     * Reads entire file into a string
     * @link https://php.net/manual/en/function.file-get-contents.php
     * @param string $filename <p>
     * Name of the file to read.
     * </p>
     * @return MethodProphecy The function returns the read data or false on failure.
     * @since 4.3.0
     * @since 5.0
     */
    public function file_get_contents($filename): MethodProphecy
    {
        return $this->__call('file_get_contents', func_get_args());
    }

    /**
     * Write a string to a file
     * @link https://php.net/manual/en/function.file-put-contents.php
     * @param string $filename <p>
     * Path to the file where to write the data.
     * </p>
     * @param string $content
     * @param int $flags [optional] <p>
     * The value of flags can be any combination of
     * the following flags (with some restrictions), joined with the binary OR
     * (|) operator.
     * </p>
     * <p>
     * <table>
     * Available flags
     * <tr valign="top">
     * <td>Flag</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td>
     * FILE_USE_INCLUDE_PATH
     * </td>
     * <td>
     * Search for filename in the include directory.
     * See include_path for more
     * information.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>
     * FILE_APPEND
     * </td>
     * <td>
     * If file filename already exists, append
     * the data to the file instead of overwriting it. Mutually
     * exclusive with LOCK_EX since appends are atomic and thus there
     * is no reason to lock.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>
     * LOCK_EX
     * </td>
     * <td>
     * Acquire an exclusive lock on the file while proceeding to the
     * writing. Mutually exclusive with FILE_APPEND.
     * @return MethodProphecy The function returns the number of bytes that were written to the file, or
     * false on failure.
     * @since 5.1.0
     * </td>
     * </tr>
     * </table>
     * </p>
     * @since 5.0
     */
    public function file_put_contents($filename, $content, $flags = 0): MethodProphecy
    {
        return $this->__call('file_put_contents', func_get_args());
    }

    /**
     * List files and directories inside the specified path
     * @link https://php.net/manual/en/function.scandir.php
     * @param string $directory <p>
     * The directory that will be scanned.
     * </p>
     * @param int $sorting_order [optional] <p>
     * By default, the sorted order is alphabetical in ascending order. If
     * the optional sorting_order is set to non-zero,
     * then the sort order is alphabetical in descending order.
     * </p>
     * @param resource $context [optional] <p>
     * For a description of the context parameter,
     * refer to the streams section of
     * the manual.
     * </p>
     * @return MethodProphecy an array of filenames on success, or false on
     * failure. If directory is not a directory, then
     * boolean false is returned, and an error of level
     * E_WARNING is generated.
     * @since 5.0
     */
    public function scandir($directory, $sorting_order = null, $context = null): MethodProphecy
    {
        return $this->__call('scandir', func_get_args());
    }

    /**
     * Tells whether the filename is a regular file
     * @link https://php.net/manual/en/function.is-file.php
     * @param string $filename <p>
     * Path to the file.
     * </p>
     * @return MethodProphecy true if the filename exists and is a regular file, false
     * otherwise.
     * @since 4.0
     * @since 5.0
     */
    public function is_file($filename): MethodProphecy
    {
        return $this->__call('is_file', func_get_args());
    }

    /**
     * Sets access and modification time of file
     * @link https://php.net/manual/en/function.touch.php
     * @param string $filename <p>
     * The name of the file being touched.
     * </p>
     * @param int $time [optional] <p>
     * The touch time. If time is not supplied,
     * the current system time is used.
     * </p>
     * @param int $atime [optional] <p>
     * If present, the access time of the given filename is set to
     * the value of atime. Otherwise, it is set to
     * time.
     * </p>
     * @return MethodProphecy true on success or false on failure.
     * @since 4.0
     * @since 5.0
     */
    public function touch($filename, $time = null, $atime = null): MethodProphecy
    {
        return $this->__call('touch', func_get_args());
    }

    /**
     * Deletes a file
     * @link https://php.net/manual/en/function.unlink.php
     * @param string $filename <p>
     * Path to the file.
     * </p>
     * @param resource $context [optional] &note.context-support;
     * @return MethodProphecy true on success or false on failure.
     * @since 4.0
     * @since 5.0
     */
    public function unlink($filename, $context = null): MethodProphecy
    {
        return $this->__call('unlink', func_get_args());
    }

    /**
     * Copies file
     * @link https://php.net/manual/en/function.copy.php
     * @param string $source <p>
     * Path to the source file.
     * </p>
     * @param string $dest <p>
     * The destination path. If dest is a URL, the
     * copy operation may fail if the wrapper does not support overwriting of
     * existing files.
     * </p>
     * <p>
     * If the destination file already exists, it will be overwritten.
     * </p>
     * @param resource $context [optional] <p>
     * A valid context resource created with
     * stream_context_create.
     * </p>
     * @return MethodProphecy true on success or false on failure.
     * @since 4.0
     * @since 5.0
     */
    public function copy($source, $dest, $context = null): MethodProphecy
    {
        return $this->__call('copy', func_get_args());
    }

    /**
     * Parse about any English textual datetime description into a Unix timestamp
     * @link https://php.net/manual/en/function.strtotime.php
     * @param string $time <p>
     * The string to parse. Before PHP 5.0.0, microseconds weren't allowed in
     * the time, since PHP 5.0.0 they are allowed but ignored.
     * </p>
     * @param int $now [optional] <p>
     * The timestamp which is used as a base for the calculation of relative
     * dates.
     * </p>
     * @return MethodProphecy a timestamp on success, false otherwise. Previous to PHP 5.1.0,
     * this function would return -1 on failure.
     */
    public function strtotime($time, $now = null): MethodProphecy
    {
        return $this->__call('strtotime', func_get_args());
    }

    /**
     * Return current Unix timestamp
     * @link https://php.net/manual/en/function.time.php
     * @return MethodProphecy <p>Returns the current time measured in the number of seconds since the Unix Epoch (January 1 1970 00:00:00 GMT).</p>
     * @since 4.0
     * @since 5.0
     */
    public function time(): MethodProphecy
    {
        return $this->__call('time', func_get_args());
    }

    /**
     * Returns the peak of memory allocated by PHP
     * @link https://php.net/manual/en/function.memory-get-peak-usage.php
     * @param bool $real_usage [optional] <p>
     * Set this to true to get the real size of memory allocated from
     * system. If not set or false only the memory used by
     * emalloc() is reported.
     * </p>
     * @return MethodProphecy the memory peak in bytes.
     * @since 5.2.0
     */
    public function memory_get_peak_usage($real_usage = null): MethodProphecy
    {
        return $this->__call('memory_get_peak_usage', func_get_args());
    }

    /**
     * Delay execution
     * @link https://php.net/manual/en/function.sleep.php
     * @param int $seconds <p>
     * Halt time in seconds.
     * </p>
     * @return MethodProphecy zero on success, or false on errors. If the call was interrupted
     * by a signal, sleep returns the number of seconds left
     * to sleep.
     * @since 4.0
     * @since 5.0
     */
    public function sleep($seconds): MethodProphecy
    {
        return $this->__call('sleep', func_get_args());
    }

    /**
     * Delay execution in microseconds
     * @link https://php.net/manual/en/function.usleep.php
     * @param int $micro_seconds <p>
     * Halt time in micro seconds. A micro second is one millionth of a
     * second.
     * </p>
     * @return MethodProphecy
     * @since 4.0
     * @since 5.0
     */
    public function usleep($micro_seconds): MethodProphecy
    {
        return $this->__call('usleep', func_get_args());
    }

    /**
     *
     * @param bool $on
     * @return MethodProphecy
     * @since 7.1
     */
    public function pcntl_async_signals($on = null): MethodProphecy
    {
        return $this->__call('pcntl_async_signals', func_get_args());
    }

    /**
     * Installs a signal handler
     * @link https://php.net/manual/en/function.pcntl-signal.php
     * @param int $signo <p>
     * The signal number.
     * </p>
     * @param callable|int $handler <p>
     * The signal handler. This may be either a callable, which
     * will be invoked to handle the signal, or either of the two global
     * constants <b>SIG_IGN</b> or <b>SIG_DFL</b>,
     * which will ignore the signal or restore the default signal handler
     * respectively.
     * </p>
     * <p>
     * If a callable is given, it must implement the following
     * signature:
     * </p>
     * <p>
     * void<b>handler</b>
     * <b>int<i>signo</i></b>
     * <i>signo</i>
     * The signal being handled.
     * @param bool $restart_syscalls [optional] <p>
     * Specifies whether system call restarting should be used when this
     * signal arrives.
     * </p>
     * @return MethodProphecy <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 4.1.0
     * @since 5.0
     */
    public function pcntl_signal($signo, callable $handler, bool $restart_syscalls = null): MethodProphecy
    {
        return $this->__call('pcntl_signal', func_get_args());
    }

    /**
     * Return the current process identifier
     * @link https://php.net/manual/en/function.posix-getpid.php
     * @return MethodProphecy the identifier, as an integer.
     * @since 4.0
     * @since 5.0
     */
    public function posix_getpid(): MethodProphecy
    {
        return $this->__call('posix_getpid', func_get_args());
    }

    /**
     * Make the current process a session leader
     * @link https://php.net/manual/en/function.posix-setsid.php
     * @return MethodProphecy the session id, or -1 on errors.
     * @since 4.0
     * @since 5.0
     */
    public function posix_setsid(): MethodProphecy
    {
        return $this->__call('posix_setsid', func_get_args());
    }

    /**
     * Get the current sid of the process
     * @link https://php.net/manual/en/function.posix-getsid.php
     * @param int $pid <p>
     * The process identifier. If set to 0, the current process is
     * assumed. If an invalid <i>pid</i> is
     * specified, then <b>FALSE</b> is returned and an error is set which
     * can be checked with <b>posix_get_last_error</b>.
     * </p>
     * @return MethodProphecy the identifier, as an integer.
     * @since 4.0
     * @since 5.0
     */
    public function posix_getsid($pid): MethodProphecy
    {
        return $this->__call('posix_getsid', func_get_args());
    }

    /**
     * Gets PHP's process ID
     * @link https://php.net/manual/en/function.getmypid.php
     * @return MethodProphecy the current PHP process ID, or false on error.
     * @since 4.0
     * @since 5.0
     */
    public function getmypid(): MethodProphecy
    {
        return $this->__call('getmypid', func_get_args());
    }

    /**
     * <p>Terminates execution of the script. Shutdown functions and object destructors will always be executed even if exit is called.</p>
     * <p>exit is a language construct and it can be called without parentheses if no status is passed.</p>
     * @link https://php.net/manual/en/function.exit.php
     * @param int|string $status [optional] <p>
     * If status is a string, this function prints the status just before exiting.
     * </p>
     * <p>
     * If status is an integer, that value will be used as the exit status and not printed. Exit statuses should be in the range 0 to 254,
     * the exit status 255 is reserved by PHP and shall not be used. The status 0 is used to terminate the program successfully.
     * </p>
     * <p>
     * Note: PHP >= 4.2.0 does NOT print the status if it is an integer.
     * </p>
     * @return MethodProphecy
     */
    public function exit($status = null): MethodProphecy
    {
        return $this->__call('exit', func_get_args());
    }

    /**
     * Execute an external program
     * @link https://php.net/manual/en/function.exec.php
     * @param string $command <p>
     * The command that will be executed.
     * </p>
     * @param array $output [optional] <p>
     * If the output argument is present, then the
     * specified array will be filled with every line of output from the
     * command. Trailing whitespace, such as \n, is not
     * included in this array. Note that if the array already contains some
     * elements, exec will append to the end of the array.
     * If you do not want the function to append elements, call
     * unset on the array before passing it to
     * exec.
     * </p>
     * @param int $return_var [optional] <p>
     * If the return_var argument is present
     * along with the output argument, then the
     * return status of the executed command will be written to this
     * variable.
     * </p>
     * @return MethodProphecy The last line from the result of the command. If you need to execute a
     * command and have all the data from the command passed directly back without
     * any interference, use the passthru function.
     * </p>
     * <p>
     * To get the output of the executed command, be sure to set and use the
     * output parameter.
     * @since 4.0
     * @since 5.0
     */
    public function exec($command, $output = null, $return_var = null): MethodProphecy
    {
        return $this->__call('exec', func_get_args());
    }

    /**
     * Creates a PHP value from a stored representation
     * @link https://php.net/manual/en/function.unserialize.php
     * @param string $str <p>
     * The serialized string.
     * </p>
     * <p>
     * If the variable being unserialized is an object, after successfully
     * reconstructing the object PHP will automatically attempt to call the
     * __wakeup member function (if it exists).
     * </p>
     * <p>
     * unserialize_callback_func directive
     * <p>
     * It's possible to set a callback-function which will be called,
     * if an undefined class should be instantiated during unserializing.
     * (to prevent getting an incomplete object "__PHP_Incomplete_Class".)
     * Use your &php.ini;, ini_set or &htaccess;
     * to define 'unserialize_callback_func'. Everytime an undefined class
     * should be instantiated, it'll be called. To disable this feature just
     * empty this setting.
     * </p>
     * @param mixed $options [optional]
     * <p>Any options to be provided to unserialize(), as an associative array.</p>
     * <p>
     * Either an array of class names which should be accepted, FALSE to
     * accept no classes, or TRUE to accept all classes. If this option is defined
     * and unserialize() encounters an object of a class that isn't to be accepted,
     * then the object will be instantiated as __PHP_Incomplete_Class instead.
     * Omitting this option is the same as defining it as TRUE: PHP will attempt
     * to instantiate objects of any class.
     * </p>
     * @return MethodProphecy The converted value is returned, and can be a boolean,
     * integer, float, string,
     * array or object.
     * </p>
     * <p>
     * In case the passed string is not unserializeable, false is returned and
     * E_NOTICE is issued.
     * @since 4.0
     * @since 5.0
     * @since 7.0
     */
    public function unserialize($str, $options = null): MethodProphecy
    {
        return $this->__call('unserialize', func_get_args());
    }

    /**
     * Outputs or returns a parsable string representation of a variable
     * @link https://php.net/manual/en/function.var-export.php
     * @param mixed $expression <p>
     * The variable you want to export.
     * </p>
     * @param bool $return [optional] <p>
     * If used and set to true, var_export will return
     * the variable representation instead of outputing it.
     * </p>
     * &note.uses-ob;
     * @return MethodProphecy the variable representation when the return
     * parameter is used and evaluates to true. Otherwise, this function will
     * return &null;.
     * @since 4.2.0
     * @since 5.0
     */
    public function var_export($expression, $return = null): MethodProphecy
    {
        return $this->__call('var_export', func_get_args());
    }

    /**
     * Returns the name of the class of an object
     * @link https://php.net/manual/en/function.get-class.php
     * @param object $object [optional] <p>
     * The tested object. This parameter may be omitted when inside a class.
     * </p>
     * @return MethodProphecy the name of the class of which <i>object</i> is an
     * instance. Returns false if <i>object</i> is not an
     * object.
     * </p>
     * <p>
     * If <i>object</i> is omitted when inside a class, the
     * name of that class is returned.
     * @since 4.0
     * @since 5.0
     */
    public function get_class($object): MethodProphecy
    {
        return $this->__call('get_class', func_get_args());
    }

    /**
     * Fetch DNS Resource Records associated with a hostname
     * @link https://php.net/manual/en/function.dns-get-record.php
     * @param string $hostname <p>
     * hostname should be a valid DNS hostname such
     * as "www.example.com". Reverse lookups can be generated
     * using in-addr.arpa notation, but
     * gethostbyaddr is more suitable for
     * the majority of reverse lookups.
     * </p>
     * <p>
     * Per DNS standards, email addresses are given in user.host format (for
     * example: hostmaster.example.com as opposed to hostmaster@example.com),
     * be sure to check this value and modify if necessary before using it
     * with a functions such as mail.
     * </p>
     * @param int $type [optional] <p>
     * By default, dns_get_record will search for any
     * resource records associated with hostname.
     * To limit the query, specify the optional type
     * parameter. May be any one of the following:
     * DNS_A, DNS_CNAME,
     * DNS_HINFO, DNS_MX,
     * DNS_NS, DNS_PTR,
     * DNS_SOA, DNS_TXT,
     * DNS_AAAA, DNS_SRV,
     * DNS_NAPTR, DNS_A6,
     * DNS_ALL or DNS_ANY.
     * </p>
     * <p>
     * Because of eccentricities in the performance of libresolv
     * between platforms, DNS_ANY will not
     * always return every record, the slower DNS_ALL
     * will collect all records more reliably.
     * </p>
     * @param array $authns [optional] <p>
     * Passed by reference and, if given, will be populated with Resource
     * Records for the Authoritative Name Servers.
     * </p>
     * @param array $addtl [optional] <p>
     * Passed by reference and, if given, will be populated with any
     * Additional Records.
     * </p>
     * @param bool $raw [optional] <p>
     * In case of raw mode, we query only the requested type
     * instead of looping type by type before going with the additional info stuff.
     * </p>
     * @return MethodProphecy This function returns an array of associative arrays. Each associative array contains
     * at minimum the following keys:
     * <table>
     * Basic DNS attributes
     * <tr valign="top">
     * <td>Attribute</td>
     * <td>Meaning</td>
     * </tr>
     * <tr valign="top">
     * <td>host</td>
     * <td>
     * The record in the DNS namespace to which the rest of the associated data refers.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>class</td>
     * <td>
     * dns_get_record only returns Internet class records and as
     * such this parameter will always return IN.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>type</td>
     * <td>
     * String containing the record type. Additional attributes will also be contained
     * in the resulting array dependant on the value of type. See table below.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>ttl</td>
     * <td>
     * "Time To Live" remaining for this record. This will not equal
     * the record's original ttl, but will rather equal the original ttl minus whatever
     * length of time has passed since the authoritative name server was queried.
     * </td>
     * </tr>
     * </table>
     * </p>
     * <p>
     * <table>
     * Other keys in associative arrays dependant on 'type'
     * <tr valign="top">
     * <td>Type</td>
     * <td>Extra Columns</td>
     * </tr>
     * <tr valign="top">
     * <td>A</td>
     * <td>
     * ip: An IPv4 addresses in dotted decimal notation.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>MX</td>
     * <td>
     * pri: Priority of mail exchanger.
     * Lower numbers indicate greater priority.
     * target: FQDN of the mail exchanger.
     * See also dns_get_mx.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>CNAME</td>
     * <td>
     * target: FQDN of location in DNS namespace to which
     * the record is aliased.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>NS</td>
     * <td>
     * target: FQDN of the name server which is authoritative
     * for this hostname.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>PTR</td>
     * <td>
     * target: Location within the DNS namespace to which
     * this record points.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>TXT</td>
     * <td>
     * txt: Arbitrary string data associated with this record.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>HINFO</td>
     * <td>
     * cpu: IANA number designating the CPU of the machine
     * referenced by this record.
     * os: IANA number designating the Operating System on
     * the machine referenced by this record.
     * See IANA's Operating System
     * Names for the meaning of these values.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>SOA</td>
     * <td>
     * mname: FQDN of the machine from which the resource
     * records originated.
     * rname: Email address of the administrative contain
     * for this domain.
     * serial: Serial # of this revision of the requested
     * domain.
     * refresh: Refresh interval (seconds) secondary name
     * servers should use when updating remote copies of this domain.
     * retry: Length of time (seconds) to wait after a
     * failed refresh before making a second attempt.
     * expire: Maximum length of time (seconds) a secondary
     * DNS server should retain remote copies of the zone data without a
     * successful refresh before discarding.
     * minimum-ttl: Minimum length of time (seconds) a
     * client can continue to use a DNS resolution before it should request
     * a new resolution from the server. Can be overridden by individual
     * resource records.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>AAAA</td>
     * <td>
     * ipv6: IPv6 address
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>A6(PHP &gt;= 5.1.0)</td>
     * <td>
     * masklen: Length (in bits) to inherit from the target
     * specified by chain.
     * ipv6: Address for this specific record to merge with
     * chain.
     * chain: Parent record to merge with
     * ipv6 data.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>SRV</td>
     * <td>
     * pri: (Priority) lowest priorities should be used first.
     * weight: Ranking to weight which of commonly prioritized
     * targets should be chosen at random.
     * target and port: hostname and port
     * where the requested service can be found.
     * For additional information see: RFC 2782
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>NAPTR</td>
     * <td>
     * order and pref: Equivalent to
     * pri and weight above.
     * flags, services, regex,
     * and replacement: Parameters as defined by
     * RFC 2915.
     * </td>
     * </tr>
     * </table>
     * @since 5.0
     */
    public function dns_get_record($hostname, $type = DNS_ANY, $authns = null, $addtl = null, $raw = null): MethodProphecy
    {
        return $this->__call('dns_get_record', func_get_args());
    }

    /**
     * Checks whether a file or directory exists
     * @link https://php.net/manual/en/function.file-exists.php
     * @param string $filename <p>
     * Path to the file or directory.
     * </p>
     * <p>
     * On windows, use //computername/share/filename or
     * \\computername\share\filename to check files on
     * network shares.
     * </p>
     * @return MethodProphecy true if the file or directory specified by
     * filename exists; false otherwise.
     * </p>
     * <p>
     * This function will return false for symlinks pointing to non-existing
     * files.
     * </p>
     * <p>
     * This function returns false for files inaccessible due to safe mode restrictions. However these
     * files still can be included if
     * they are located in safe_mode_include_dir.
     * </p>
     * <p>
     * The check is done using the real UID/GID instead of the effective one.
     * @since 4.0
     * @since 5.0
     */
    public function file_exists($filename): MethodProphecy
    {
        return $this->__call('file_exists', func_get_args());
    }

    /**
     * Gets file modification time
     * @link https://php.net/manual/en/function.filemtime.php
     * @param string $filename <p>
     * Path to the file.
     * </p>
     * @return MethodProphecy the time the file was last modified, or false on failure.
     * The time is returned as a Unix timestamp, which is
     * suitable for the date function.
     * @since 4.0
     * @since 5.0
     */
    public function filemtime($filename): MethodProphecy
    {
        return $this->__call('filemtime', func_get_args());
    }

    /**
     * Return current Unix timestamp with microseconds
     * @link https://php.net/manual/en/function.microtime.php
     * @param bool $get_as_float [optional] <p>
     * When called without the optional argument, this function returns the string
     * "msec sec" where sec is the current time measured in the number of
     * seconds since the Unix Epoch (0:00:00 January 1, 1970 GMT), and
     * msec is the microseconds part.
     * Both portions of the string are returned in units of seconds.
     * </p>
     * <p>
     * If the optional get_as_float is set to
     * true then a float (in seconds) is returned.
     * </p>
     * @return MethodProphecy
     * @since 4.0
     * @since 5.0
     */
    public function microtime($get_as_float = false): MethodProphecy
    {
        return $this->__call('microtime', func_get_args());
    }

    /**
     * Format a local time/date
     * @link https://php.net/manual/en/function.date.php
     * @param string $format <p>
     * The format of the outputted date string. See the formatting
     * options below. There are also several
     * predefined date constants
     * that may be used instead, so for example DATE_RSS
     * contains the format string 'D, d M Y H:i:s'.
     * </p>
     * <p>
     * The following characters are recognized in the
     * format parameter string
     * <table>
     * <tr valign="top">
     * <td>format character</td>
     * <td>Description</td>
     * <td>Example returned values</td>
     * </tr>
     * <tr valign="top">
     * Day</td>
     * <td>---</td>
     * <td>---</td>
     * </tr>
     * <tr valign="top">
     * <td>d</td>
     * <td>Day of the month, 2 digits with leading zeros</td>
     * <td>01 to 31</td>
     * </tr>
     * <tr valign="top">
     * <td>D</td>
     * <td>A textual representation of a day, three letters</td>
     * <td>Mon through Sun</td>
     * </tr>
     * <tr valign="top">
     * <td>j</td>
     * <td>Day of the month without leading zeros</td>
     * <td>1 to 31</td>
     * </tr>
     * <tr valign="top">
     * <td>l (lowercase 'L')</td>
     * <td>A full textual representation of the day of the week</td>
     * <td>Sunday through Saturday</td>
     * </tr>
     * <tr valign="top">
     * <td>N</td>
     * <td>ISO-8601 numeric representation of the day of the week (added in
     * PHP 5.1.0)</td>
     * <td>1 (for Monday) through 7 (for Sunday)</td>
     * </tr>
     * <tr valign="top">
     * <td>S</td>
     * <td>English ordinal suffix for the day of the month, 2 characters</td>
     * <td>
     * st, nd, rd or
     * th. Works well with j
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>w</td>
     * <td>Numeric representation of the day of the week</td>
     * <td>0 (for Sunday) through 6 (for Saturday)</td>
     * </tr>
     * <tr valign="top">
     * <td>z</td>
     * <td>The day of the year (starting from 0)</td>
     * <td>0 through 365</td>
     * </tr>
     * <tr valign="top">
     * Week</td>
     * <td>---</td>
     * <td>---</td>
     * </tr>
     * <tr valign="top">
     * <td>W</td>
     * <td>ISO-8601 week number of year, weeks starting on Monday (added in PHP 4.1.0)</td>
     * <td>Example: 42 (the 42nd week in the year)</td>
     * </tr>
     * <tr valign="top">
     * Month</td>
     * <td>---</td>
     * <td>---</td>
     * </tr>
     * <tr valign="top">
     * <td>F</td>
     * <td>A full textual representation of a month, such as January or March</td>
     * <td>January through December</td>
     * </tr>
     * <tr valign="top">
     * <td>m</td>
     * <td>Numeric representation of a month, with leading zeros</td>
     * <td>01 through 12</td>
     * </tr>
     * <tr valign="top">
     * <td>M</td>
     * <td>A short textual representation of a month, three letters</td>
     * <td>Jan through Dec</td>
     * </tr>
     * <tr valign="top">
     * <td>n</td>
     * <td>Numeric representation of a month, without leading zeros</td>
     * <td>1 through 12</td>
     * </tr>
     * <tr valign="top">
     * <td>t</td>
     * <td>Number of days in the given month</td>
     * <td>28 through 31</td>
     * </tr>
     * <tr valign="top">
     * Year</td>
     * <td>---</td>
     * <td>---</td>
     * </tr>
     * <tr valign="top">
     * <td>L</td>
     * <td>Whether it's a leap year</td>
     * <td>1 if it is a leap year, 0 otherwise.</td>
     * </tr>
     * <tr valign="top">
     * <td>o</td>
     * <td>ISO-8601 year number. This has the same value as
     * Y, except that if the ISO week number
     * (W) belongs to the previous or next year, that year
     * is used instead. (added in PHP 5.1.0)</td>
     * <td>Examples: 1999 or 2003</td>
     * </tr>
     * <tr valign="top">
     * <td>Y</td>
     * <td>A full numeric representation of a year, 4 digits</td>
     * <td>Examples: 1999 or 2003</td>
     * </tr>
     * <tr valign="top">
     * <td>y</td>
     * <td>A two digit representation of a year</td>
     * <td>Examples: 99 or 03</td>
     * </tr>
     * <tr valign="top">
     * Time</td>
     * <td>---</td>
     * <td>---</td>
     * </tr>
     * <tr valign="top">
     * <td>a</td>
     * <td>Lowercase Ante meridiem and Post meridiem</td>
     * <td>am or pm</td>
     * </tr>
     * <tr valign="top">
     * <td>A</td>
     * <td>Uppercase Ante meridiem and Post meridiem</td>
     * <td>AM or PM</td>
     * </tr>
     * <tr valign="top">
     * <td>B</td>
     * <td>Swatch Internet time</td>
     * <td>000 through 999</td>
     * </tr>
     * <tr valign="top">
     * <td>g</td>
     * <td>12-hour format of an hour without leading zeros</td>
     * <td>1 through 12</td>
     * </tr>
     * <tr valign="top">
     * <td>G</td>
     * <td>24-hour format of an hour without leading zeros</td>
     * <td>0 through 23</td>
     * </tr>
     * <tr valign="top">
     * <td>h</td>
     * <td>12-hour format of an hour with leading zeros</td>
     * <td>01 through 12</td>
     * </tr>
     * <tr valign="top">
     * <td>H</td>
     * <td>24-hour format of an hour with leading zeros</td>
     * <td>00 through 23</td>
     * </tr>
     * <tr valign="top">
     * <td>i</td>
     * <td>Minutes with leading zeros</td>
     * <td>00 to 59</td>
     * </tr>
     * <tr valign="top">
     * <td>s</td>
     * <td>Seconds, with leading zeros</td>
     * <td>00 through 59</td>
     * </tr>
     * <tr valign="top">
     * <td>u</td>
     * <td>Microseconds (added in PHP 5.2.2)</td>
     * <td>Example: 654321</td>
     * </tr>
     * <tr valign="top">
     * Timezone</td>
     * <td>---</td>
     * <td>---</td>
     * </tr>
     * <tr valign="top">
     * <td>e</td>
     * <td>Timezone identifier (added in PHP 5.1.0)</td>
     * <td>Examples: UTC, GMT, Atlantic/Azores</td>
     * </tr>
     * <tr valign="top">
     * <td>I (capital i)</td>
     * <td>Whether or not the date is in daylight saving time</td>
     * <td>1 if Daylight Saving Time, 0 otherwise.</td>
     * </tr>
     * <tr valign="top">
     * <td>O</td>
     * <td>Difference to Greenwich time (GMT) in hours</td>
     * <td>Example: +0200</td>
     * </tr>
     * <tr valign="top">
     * <td>P</td>
     * <td>Difference to Greenwich time (GMT) with colon between hours and minutes (added in PHP 5.1.3)</td>
     * <td>Example: +02:00</td>
     * </tr>
     * <tr valign="top">
     * <td>T</td>
     * <td>Timezone abbreviation</td>
     * <td>Examples: EST, MDT ...</td>
     * </tr>
     * <tr valign="top">
     * <td>Z</td>
     * <td>Timezone offset in seconds. The offset for timezones west of UTC is always
     * negative, and for those east of UTC is always positive.</td>
     * <td>-43200 through 50400</td>
     * </tr>
     * <tr valign="top">
     * Full Date/Time</td>
     * <td>---</td>
     * <td>---</td>
     * </tr>
     * <tr valign="top">
     * <td>c</td>
     * <td>ISO 8601 date (added in PHP 5)</td>
     * <td>2004-02-12T15:19:21+00:00</td>
     * </tr>
     * <tr valign="top">
     * <td>r</td>
     * <td>RFC 2822 formatted date</td>
     * <td>Example: Thu, 21 Dec 2000 16:01:07 +0200</td>
     * </tr>
     * <tr valign="top">
     * <td>U</td>
     * <td>Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)</td>
     * <td>See also time</td>
     * </tr>
     * </table>
     * </p>
     * <p>
     * Unrecognized characters in the format string will be printed
     * as-is. The Z format will always return
     * 0 when using gmdate.
     * </p>
     * <p>
     * Since this function only accepts integer timestamps the
     * u format character is only useful when using the
     * date_format function with user based timestamps
     * created with date_create.
     * </p>
     * @param int $timestamp [optional] The optional timestamp parameter is an integer Unix timestamp
     * that defaults to the current local time if a timestamp is not given.
     * In other words, it defaults to the value of time().
     * @return MethodProphecy a formatted date string. If a non-numeric value is used for
     * timestamp, false is returned and an
     * E_WARNING level error is emitted.
     * @since 4.0
     * @since 5.0
     */
    public function date($format, $timestamp = null): MethodProphecy
    {
        return $this->__call('date', func_get_args());
    }

    /**
     * Generate a unique ID
     * @link https://php.net/manual/en/function.uniqid.php
     * @param string $prefix [optional] <p>
     * Can be useful, for instance, if you generate identifiers
     * simultaneously on several hosts that might happen to generate the
     * identifier at the same microsecond.
     * </p>
     * <p>
     * With an empty prefix, the returned string will
     * be 13 characters long. If more_entropy is
     * true, it will be 23 characters.
     * </p>
     * @param bool $more_entropy [optional] <p>
     * If set to true, uniqid will add additional
     * entropy (using the combined linear congruential generator) at the end
     * of the return value, which should make the results more unique.
     * </p>
     * @return MethodProphecy the unique identifier, as a string.
     * @since 4.0
     * @since 5.0
     */
    public function uniqid($prefix = '', $more_entropy = null): MethodProphecy
    {
        return $this->__call('uniqid', func_get_args());
    }

    /**
     * Tells whether the filename is a directory
     * @link https://php.net/manual/en/function.is-dir.php
     * @param string $filename <p>
     * Path to the file. If filename is a relative
     * filename, it will be checked relative to the current working
     * directory. If filename is a symbolic or hard link
     * then the link will be resolved and checked.
     * </p>
     * @return MethodProphecy true if the filename exists and is a directory, false
     * otherwise.
     * @since 4.0
     * @since 5.0
     */
    public function is_dir($filename): MethodProphecy
    {
        return $this->__call('is_dir', func_get_args());
    }

    /**
     * Attempts to create the directory specified by pathname.
     * @link https://php.net/manual/en/function.mkdir.php
     * @param string $pathname <p>
     * The directory path.
     * </p>
     * @param int $mode [optional] <p>
     * The mode is 0777 by default, which means the widest possible
     * access. For more information on modes, read the details
     * on the chmod page.
     * </p>
     * <p>
     * mode is ignored on Windows.
     * </p>
     * <p>
     * Note that you probably want to specify the mode as an octal number,
     * which means it should have a leading zero. The mode is also modified
     * by the current umask, which you can change using
     * umask().
     * </p>
     * @param bool $recursive [optional] <p>
     * Allows the creation of nested directories specified in the pathname. Default to false.
     * </p>
     * @param resource $context [optional] &note.context-support;
     * @return MethodProphecy true on success or false on failure.
     * @since 4.0
     * @since 5.0
     */
    public function mkdir($pathname, $mode = 0777, $recursive = false, $context = null): MethodProphecy
    {
        return $this->__call('mkdir', func_get_args());
    }

    /**
     * Deactivates the circular reference collector
     * @link https://php.net/manual/en/function.gc-disable.php
     * @return MethodProphecy
     * @since 5.3.0
     */
    public function gc_disable(): MethodProphecy
    {
        return $this->__call('gc_disable', func_get_args());
    }

    /**
     * Activates the circular reference collector
     * @link https://php.net/manual/en/function.gc-enable.php
     * @return MethodProphecy
     * @since 5.3.0
     */
    public function gc_enable(): MethodProphecy
    {
        return $this->__call('gc_enable', func_get_args());
    }

    /**
     * Binary-safe file write
     * @link https://php.net/manual/en/function.fwrite.php
     * @param resource $handle &fs.file.pointer;
     * @param string $string <p>
     * The string that is to be written.
     * </p>
     * @param int $length [optional] <p>
     * If the length argument is given, writing will
     * stop after length bytes have been written or
     * the end of string is reached, whichever comes
     * first.
     * </p>
     * <p>
     * Note that if the length argument is given,
     * then the magic_quotes_runtime
     * configuration option will be ignored and no slashes will be
     * stripped from string.
     * </p>
     * @return MethodProphecy the number of bytes written, or <b>FALSE</b> on error.
     * @since 4.0
     * @since 5.0
     */
    public function fwrite($handle, $string, $length = null): MethodProphecy
    {
        return $this->__call('fwrite', func_get_args());
    }

    /**
     * Opens file or URL
     * @link https://php.net/manual/en/function.fopen.php
     * @param string $filename <p>
     * If filename is of the form "scheme://...", it
     * is assumed to be a URL and PHP will search for a protocol handler
     * (also known as a wrapper) for that scheme. If no wrappers for that
     * protocol are registered, PHP will emit a notice to help you track
     * potential problems in your script and then continue as though
     * filename specifies a regular file.
     * </p>
     * <p>
     * If PHP has decided that filename specifies
     * a local file, then it will try to open a stream on that file.
     * The file must be accessible to PHP, so you need to ensure that
     * the file access permissions allow this access.
     * If you have enabled &safemode;,
     * or open_basedir further
     * restrictions may apply.
     * </p>
     * <p>
     * If PHP has decided that filename specifies
     * a registered protocol, and that protocol is registered as a
     * network URL, PHP will check to make sure that
     * allow_url_fopen is
     * enabled. If it is switched off, PHP will emit a warning and
     * the fopen call will fail.
     * </p>
     * <p>
     * The list of supported protocols can be found in . Some protocols (also referred to as
     * wrappers) support context
     * and/or &php.ini; options. Refer to the specific page for the
     * protocol in use for a list of options which can be set. (e.g.
     * &php.ini; value user_agent used by the
     * http wrapper).
     * </p>
     * <p>
     * On the Windows platform, be careful to escape any backslashes
     * used in the path to the file, or use forward slashes.
     * ]]>
     * </p>
     * @param string $mode <p>
     * The mode parameter specifies the type of access
     * you require to the stream. It may be any of the following:
     * <table>
     * A list of possible modes for fopen
     * using mode
     * <tr valign="top">
     * <td>mode</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td>'r'</td>
     * <td>
     * Open for reading only; place the file pointer at the
     * beginning of the file.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>'r+'</td>
     * <td>
     * Open for reading and writing; place the file pointer at
     * the beginning of the file.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>'w'</td>
     * <td>
     * Open for writing only; place the file pointer at the
     * beginning of the file and truncate the file to zero length.
     * If the file does not exist, attempt to create it.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>'w+'</td>
     * <td>
     * Open for reading and writing; place the file pointer at
     * the beginning of the file and truncate the file to zero
     * length. If the file does not exist, attempt to create it.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>'a'</td>
     * <td>
     * Open for writing only; place the file pointer at the end of
     * the file. If the file does not exist, attempt to create it.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>'a+'</td>
     * <td>
     * Open for reading and writing; place the file pointer at
     * the end of the file. If the file does not exist, attempt to
     * create it.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>'x'</td>
     * <td>
     * Create and open for writing only; place the file pointer at the
     * beginning of the file. If the file already exists, the
     * fopen call will fail by returning false and
     * generating an error of level E_WARNING. If
     * the file does not exist, attempt to create it. This is equivalent
     * to specifying O_EXCL|O_CREAT flags for the
     * underlying open(2) system call.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>'x+'</td>
     * <td>
     * Create and open for reading and writing; place the file pointer at
     * the beginning of the file. If the file already exists, the
     * fopen call will fail by returning false and
     * generating an error of level E_WARNING. If
     * the file does not exist, attempt to create it. This is equivalent
     * to specifying O_EXCL|O_CREAT flags for the
     * underlying open(2) system call.
     * </td>
     * </tr>
     * </table>
     * </p>
     * <p>
     * Different operating system families have different line-ending
     * conventions. When you write a text file and want to insert a line
     * break, you need to use the correct line-ending character(s) for your
     * operating system. Unix based systems use \n as the
     * line ending character, Windows based systems use \r\n
     * as the line ending characters and Macintosh based systems use
     * \r as the line ending character.
     * </p>
     * <p>
     * If you use the wrong line ending characters when writing your files, you
     * might find that other applications that open those files will "look
     * funny".
     * </p>
     * <p>
     * Windows offers a text-mode translation flag ('t')
     * which will transparently translate \n to
     * \r\n when working with the file. In contrast, you
     * can also use 'b' to force binary mode, which will not
     * translate your data. To use these flags, specify either
     * 'b' or 't' as the last character
     * of the mode parameter.
     * </p>
     * <p>
     * The default translation mode depends on the SAPI and version of PHP that
     * you are using, so you are encouraged to always specify the appropriate
     * flag for portability reasons. You should use the 't'
     * mode if you are working with plain-text files and you use
     * \n to delimit your line endings in your script, but
     * expect your files to be readable with applications such as notepad. You
     * should use the 'b' in all other cases.
     * </p>
     * <p>
     * If you do not specify the 'b' flag when working with binary files, you
     * may experience strange problems with your data, including broken image
     * files and strange problems with \r\n characters.
     * </p>
     * <p>
     * For portability, it is strongly recommended that you always
     * use the 'b' flag when opening files with fopen.
     * </p>
     * <p>
     * Again, for portability, it is also strongly recommended that
     * you re-write code that uses or relies upon the 't'
     * mode so that it uses the correct line endings and
     * 'b' mode instead.
     * </p>
     * @param bool $use_include_path [optional] <p>
     * The optional third use_include_path parameter
     * can be set to '1' or true if you want to search for the file in the
     * include_path, too.
     * </p>
     * @param resource $context [optional] &note.context-support;
     * @return MethodProphecy a file pointer resource on success, or false on error.
     * @since 4.0
     * @since 5.0
     */
    public function fopen($filename, $mode, $use_include_path = null, $context = null): MethodProphecy
    {
        return $this->__call('fopen', func_get_args());
    }

    /**
     * Send mail
     * @link https://php.net/manual/en/function.mail.php
     * @param string $to <p>
     * Receiver, or receivers of the mail.
     * </p>
     * <p>
     * The formatting of this string must comply with
     * RFC 2822. Some examples are:
     * user@example.com
     * user@example.com, anotheruser@example.com
     * User &lt;user@example.com&gt;
     * User &lt;user@example.com&gt;, Another User &lt;anotheruser@example.com&gt;
     * </p>
     * @param string $subject <p>
     * Subject of the email to be sent.
     * </p>
     * <p>
     * Subject must satisfy RFC 2047.
     * </p>
     * @param string $message <p>
     * Message to be sent.
     * </p>
     * <p>
     * Each line should be separated with a LF (\n). Lines should not be larger
     * than 70 characters.
     * </p>
     * <p>
     * (Windows only) When PHP is talking to a SMTP server directly, if a full
     * stop is found on the start of a line, it is removed. To counter-act this,
     * replace these occurrences with a double dot.
     * ]]>
     * </p>
     * @param string $additional_headers [optional] <p>
     * String to be inserted at the end of the email header.
     * </p>
     * <p>
     * This is typically used to add extra headers (From, Cc, and Bcc).
     * Multiple extra headers should be separated with a CRLF (\r\n).
     * </p>
     * <p>
     * When sending mail, the mail must contain
     * a From header. This can be set with the
     * additional_headers parameter, or a default
     * can be set in &php.ini;.
     * </p>
     * <p>
     * Failing to do this will result in an error
     * message similar to Warning: mail(): "sendmail_from" not
     * set in php.ini or custom "From:" header missing.
     * The From header sets also
     * Return-Path under Windows.
     * </p>
     * <p>
     * If messages are not received, try using a LF (\n) only.
     * Some poor quality Unix mail transfer agents replace LF by CRLF
     * automatically (which leads to doubling CR if CRLF is used).
     * This should be a last resort, as it does not comply with
     * RFC 2822.
     * </p>
     * @param string $additional_parameters [optional] <p>
     * The additional_parameters parameter
     * can be used to pass additional flags as command line options to the
     * program configured to be used when sending mail, as defined by the
     * sendmail_path configuration setting. For example,
     * this can be used to set the envelope sender address when using
     * sendmail with the -f sendmail option.
     * </p>
     * <p>
     * The user that the webserver runs as should be added as a trusted user to the
     * sendmail configuration to prevent a 'X-Warning' header from being added
     * to the message when the envelope sender (-f) is set using this method.
     * For sendmail users, this file is /etc/mail/trusted-users.
     * </p>
     * @return MethodProphecy true if the mail was successfully accepted for delivery, false otherwise.
     * </p>
     * <p>
     * It is important to note that just because the mail was accepted for delivery,
     * it does NOT mean the mail will actually reach the intended destination.
     * @since 4.0
     * @since 5.0
     */
    public function mail($to, $subject, $message, $additional_headers = null, $additional_parameters = null): MethodProphecy
    {
        return $this->__call('mail', func_get_args());
    }

    /**
     * Changes file mode
     * @link https://php.net/manual/en/function.chmod.php
     * @param string $filename <p>
     * Path to the file.
     * </p>
     * @param int $mode <p>
     * Note that mode is not automatically
     * assumed to be an octal value, so strings (such as "g+w") will
     * not work properly. To ensure the expected operation,
     * you need to prefix mode with a zero (0):
     * </p>
     * <p>
     * ]]>
     * </p>
     * <p>
     * The mode parameter consists of three octal
     * number components specifying access restrictions for the owner,
     * the user group in which the owner is in, and to everybody else in
     * this order. One component can be computed by adding up the needed
     * permissions for that target user base. Number 1 means that you
     * grant execute rights, number 2 means that you make the file
     * writeable, number 4 means that you make the file readable. Add
     * up these numbers to specify needed rights. You can also read more
     * about modes on Unix systems with 'man 1 chmod'
     * and 'man 2 chmod'.
     * </p>
     * <p>
     * @return MethodProphecy true on success or false on failure.
     * @since 4.0
     * @since 5.0
     */
    public function chmod($filename, $mode): MethodProphecy
    {
        return $this->__call('chmod', func_get_args());
    }

    /**
     * Write session data and end session
     * @link https://php.net/manual/en/function.session-write-close.php
     * @return MethodProphecy
     * @since 4.0.4
     * @since 5.0
     */
    public function session_write_close(): MethodProphecy
    {
        return $this->__call('session_write_close', func_get_args());
    }
}