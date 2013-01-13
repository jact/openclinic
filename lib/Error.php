<?php
/**
 * Error.php
 *
 * Contains the class Error
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Error.php,v 1.16 2013/01/13 14:22:36 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

if ( !defined("PHP_EOL") ) // PHP >= 4.3.10
{
  define("PHP_EOL", "\n");
}

/**
 * Error set of show error and debug functions
 *
 * Methods:
 *  void query(Query $query, bool $goOut = true)
 *  void connection(DbConnection $conn, bool $goOut = true)
 *  void fetch(Query $query, bool $goOut = true)
 *  void message(string $errorMsg, int $errorType = E_USER_WARNING)
 *  string backTrace(array $context)
 *  string getSourceContext(string $file, int $line, array $context)
 *  array _getVariables(string $code)
 *  void customHandler(int $number, string $message, string $file, int $line, array $context)
 *  void debug(mixed $expression, string $message = "", bool $goOut = false)
 *  void trace(mixed $expression, string $message = "", bool $goOut = false)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Error
{
  /**
   * void query(Query $query, bool $goOut = true)
   *
   * Displays the query error page
   *
   * @param Query $query Query object containing query error parameters.
   * @param bool $goOut if true, execute an exit()
   * @return void
   * @access public
   */
  public static function query($query, $goOut = true)
  {
    self::message($query->getError() . " " . $query->getDbErrno()
      . " - " . $query->getDbError() . "." . $query->getSQL()
    );

    if (defined("OPEN_DEBUG") && OPEN_DEBUG)
    {
      echo PHP_EOL . "<!-- _dbErrno = " . $query->getDbErrno() . "-->" . PHP_EOL;
      echo "<!-- _dbError = " . $query->getDbError() . "-->" . PHP_EOL;
      if ($query->getSQL() != "")
      {
        echo "<!-- _SQL = " . $query->getSQL() . "-->" . PHP_EOL;
      }
    }

    if ($query->getDbErrno() == 1049) // Unable to connect to database
    {
      echo '<p><a href="../install.html">' . "Install instructions" . '</a></p>' . PHP_EOL;
    }

    if ($goOut)
    {
      exit($query->getError());
    }
  }

  /**
   * void connection(DbConnection $conn, bool $goOut = true)
   *
   * Displays the connection error page
   *
   * @param DbConnection $conn DbConnection object containing connection error parameters.
   * @param bool $goOut if true, execute an exit()
   * @return void
   * @access public
   */
  function connection($conn, $goOut = true)
  {
    self::message($conn->getError() . " " . $conn->getDbErrno()
      . " - " . $conn->getDbError() . "." . $conn->getSQL()
    );

    if (defined("OPEN_DEBUG") && OPEN_DEBUG)
    {
      echo PHP_EOL . "<!-- _dbErrno = " . $conn->getDbErrno() . "-->" . PHP_EOL;
      echo "<!-- _dbError = " . $conn->getDbError() . "-->" . PHP_EOL;
      echo "<!-- _error = " . $conn->getError() . "-->" . PHP_EOL;
      if ($conn->getSQL() != "")
      {
        echo "<!-- _SQL = " . $conn->getSQL() . "-->" . PHP_EOL;
      }
    }

    if ($conn->getDbErrno() == 1049) // Unable to connect to database
    {
      echo '<p><a href="../install.html">' . "Install instructions" . '</a></p>' . PHP_EOL;
    }

    if ($goOut)
    {
      exit($conn->getError());
    }
  }

  /**
   * void fetch(Query $query, bool $goOut = true)
   *
   * Displays the fetch error page
   *
   * @param bool $goOut if true, execute an exit()
   * @return void
   * @access public
   * @since 0.7
   */
  function fetch($query, $goOut = true)
  {
    if ($query->getSQL() != "")
    {
      echo PHP_EOL . "<!-- _SQL = " . $query->getSQL() . "-->" . PHP_EOL;
    }

    if ($goOut)
    {
      exit($query->getError());
    }
  }

  /**
   * void message(string $errorMsg, int $errorType = E_USER_WARNING)
   *
   * Displays an error message
   *
   * @param string $errorMsg
   * @param int $errorType (optional) E_USER_WARNING by default
   * @return void
   * @access public
   */
  public static function message($errorMsg, $errorType = E_USER_WARNING)
  {
    trigger_error($errorMsg, $errorType);
  }

  /**
   * string backTrace(array $context)
   *
   * Returns information about backtracing of a function
   *
   * @param array $context
   * @return string information about backtracing
   * @access public
   * @since 0.7
   */
  public static function backTrace($context)
  {
    $calls = PHP_EOL . "Backtrace:";
    $trace = (function_exists("debug_backtrace") ? debug_backtrace() : null); // SF.net DEMO version PHP 4.1.2

    // Start at 2 -- ignore this function (0) and the customHandler() (1)
    for ($x = 2; $x < count($trace); $x++)
    {
      $callNo = $x - 2;
      $calls .= PHP_EOL . " " . $callNo . ": ";
      $calls .= (isset($trace[$x]["class"]) ? $trace[$x]["class"] . $trace[$x]["type"] : '');
      $calls .= $trace[$x]["function"];
      $calls .= (isset($trace[$x]["args"]) && is_array($trace[$x]["args"]) && count($trace[$x]["args"]) > 0)
        ? '(' . implode(', ', $trace[$x]["args"]) . ')'
        : '';
      $calls .= " (line " . $trace[$x]["line"] . " in " . $trace[$x]["file"] . ")";
    }

    $calls .= PHP_EOL . "Variables in ";
    $calls .= (isset($trace[2]["class"]) ? $trace[2]["class"] . $trace[2]["type"] : '');
    $calls .= (isset($trace[2]["function"]) ? $trace[2]["function"] : "UNKNOWN") . "():";

    // Use the $context to get variable information for the function with the error
    foreach ($context as $key => $value)
    {
      $calls .= PHP_EOL . " " . $key . " is " . (( !empty($value) ) ? serialize($value) : "NULL");
    }

    return $calls;
  }

  /**
   * string getSourceContext(string $file, int $line, array $context)
   *
   * Returns information about source context of an error
   *
   * @param string $file
   * @param int $line
   * @param array $context
   * @return string information about source
   * @access public
   * @since 0.8
   */
  public static function getSourceContext($file, $line, $context)
  {
    // check that file exists
    if ( !file_exists($file) )
    {
      return sprintf("Context cannot be shown: %s does not exist", $file);
    }

    // check that the line number is valid
    if ( !is_int($line) || $line <= 0 )
    {
      return sprintf("Context cannot be shown: %s is an invalid line number", $line);
    }

    $source = highlight_file($file, true);
    $source = preg_split("/<br \/>/", $source);
    $lines = file($file);

    // get line numbers
    $start = $line - 5; //$this->context_options['source_lines'] - 1;
    $finish = $line + 5; //$this->context_options['source_lines'];

    // get lines
    if ($start < 0)
    {
      $start = 0;
    }
    if ($start >= count($lines))
    {
      $start = count($lines) - 1;
    }

    if ($finish >= count($lines))
    {
      $finish = count($lines) - 1;
    }

    $contextLines = null;
    for ($i = $start; $i < $finish; $i++)
    {
      // highlight line in question
      if ($i == ($line - 1))
      {
        $contextLines[] = '<font color="red"><b>' . ($i + 1) . "\t" . strip_tags($source[$line - 1]) . '</b></font>';
      }
      else
      {
        $contextLines[] = '<font color="black"><b>' . ($i + 1) . "</b></font>\t" . $source[$i] . '</font>';
      }
    }

    $length = $finish - $start;
    $code = implode("", array_slice($lines, $start, $length));

    $variables = self::_getVariables($code);

    // remove all but the 'relevant' variables
    $relevant = null;
    foreach ($context as $key => $value)
    {
      if (isset($variables[$key]))
      {
        $relevant[$key] = $value;
      }
    }

    ob_start();
    $output = trim(implode(PHP_EOL, $contextLines)) . '<br>' . PHP_EOL;
    print_r($relevant);
    $output .= ob_get_contents();
    ob_end_clean();

    return $output;
  }

  /**
   * array _getVariables(string $code)
   *
   * Parses the code for variable tokens, and returns an unique list
   *
   * @param string $code
   * @return array with context variables
   * @access private
   * @since 0.8
   */
  private static function _getVariables($code)
  {
    $tokens = token_get_all('<?php {$code} ?>');

    $variables = array();
    foreach ($tokens as $index => $value)
    {
      if (is_array($value) && ($value[0] == T_VARIABLE))
      {
        $name = str_replace('$', '', $value[1]);
        $variables[$name] = $name;
      }
    }

    return $variables;
  }

  /**
   * void customHandler(int $number, string $message, string $file, int $line, array $context)
   *
   * Custom error handler
   *
   * @param int $number type of error
   * @param string $message
   * @param string $file
   * @param int $line
   * @param array $context
   * @return void
   * @access public
   * @since 0.7
   */
  public static function customHandler($number, $message, $file, $line, $context)
  {
    $goOut = false;

    switch ($number)
    {
      case E_USER_ERROR:
        $prepend = "Error";
        $error = PHP_EOL . "ERROR";
        $goOut = true;
        break;

      case E_WARNING:
      case E_USER_WARNING:
        $prepend = "Warning";
        $error = PHP_EOL . "WARNING";
        break;

      case E_NOTICE:
        if (defined("OPEN_DEBUG") && !OPEN_DEBUG)
        {
          return; // do nothing
        }
        //break; // don't remove comment mark

      case E_USER_NOTICE:
        $prepend = "Notice";
        $error = PHP_EOL . "NOTICE";
        break;

      default:
        $prepend = "Error";
        $error = PHP_EOL . "UNHANDLED ERROR";
        break;
    }

    // for not repeat variables
    unset(
      $context['HTTP_POST_VARS'],
      $context['HTTP_GET_VARS'],
      $context['HTTP_SERVER_VARS'],
      $context['HTTP_COOKIE_VARS'],
      $context['HTTP_ENV_VARS'],
      $context['HTTP_POST_FILES'],
      $context['translation'] // does not add info
    );

    $error .= " on line " . $line . " in " . $file . "." . PHP_EOL;
    $error .= PHP_EOL . "Message: " . $message;
    $error .= PHP_EOL . "Source:" . PHP_EOL;
    $error .= self::getSourceContext($file, $line, $context);
    $error .= self::backTrace($context);
    $error .= PHP_EOL . "Client IP: " . $_SERVER["REMOTE_ADDR"];

    $prepend = PHP_EOL . "[PHP " . $prepend . " " . date("Y-m-d H:i:s") . "]";
    //$error = preg_replace('/' . PHP_EOL . '/', $prepend, $error);
    $error = $prepend . $error;
    $error .= PHP_EOL . str_repeat("_", 78); // separator line

    if ( !defined("OPEN_SCREEN_ERRORS") || OPEN_SCREEN_ERRORS )
    {
      echo '<pre style="background: #fff; color: #000; border: 2px solid #f00;">';
      echo wordwrap($error, 78, PHP_EOL/*, true*/) . '</pre>' . PHP_EOL;
    }

    if (defined("OPEN_LOG_ERRORS") && OPEN_LOG_ERRORS)
    {
      $logDir = substr(OPEN_LOG_FILE, 0, strrpos(OPEN_LOG_FILE, "/"));
      if (is_dir($logDir))
      {
        $error = strtr(strip_tags($error), array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
        error_log($error, 3, OPEN_LOG_FILE);
      }
    }

    // @todo by argument or by default
    /*if ($number == E_USER_ERROR)
    {
      mail("xxx@example.com", "Critical User Error", $error);
    }*/

    if ($goOut)
    {
      $_SESSION = array();
      session_destroy();
      exit();
    }
  }

  /**
   * void debug(mixed $expression, string $message = "", bool $goOut = false)
   *
   * Displays the content of $expression (depends of OPEN_DEBUG == true)
   *
   * @param mixed $expression
   * @param string $message (optional)
   * @param bool $goOut (optional) if true, execute an exit()
   * @return void
   * @access public
   * @since 0.7
   */
  public static function debug($expression, $message = "", $goOut = false)
  {
    if ( defined("OPEN_DEBUG") && !OPEN_DEBUG )
    {
      return;
    }

    self::trace($expression, isset($message) ? $message : "", isset($goOut) ? $goOut : false);
  }

  /**
   * void trace(mixed $expression, string $message = "", bool $goOut = false)
   *
   * Displays the content of $expression
   *
   * @param mixed $expression
   * @param string $message (optional)
   * @param bool $goOut (optional) if true, execute an exit()
   * @return void
   * @access public
   * @since 0.8
   */
  public static function trace($expression, $message = "", $goOut = false)
  {
    if (PHP_SAPI == 'cli')
    {
      $echo = PHP_EOL . '### Trace ###' . PHP_EOL;
      if ( !empty($message) )
      {
        $echo .= $message . PHP_EOL;
      }
      $echo .= var_export($expression, true) . PHP_EOL;
      $echo .= '### End trace ###' . PHP_EOL;
    }
    else
    {
      $echo = PHP_EOL . '<!-- trace -->' . PHP_EOL;
      $echo .= '<pre style="background: #fff; color: #000; border: 2px solid #fc0;">' . PHP_EOL;
      if ( !empty($message) )
      {
        $echo .= htmlspecialchars($message) . PHP_EOL;
      }
      $echo .= htmlspecialchars(var_export($expression, true));
      $echo .= '</pre>' . PHP_EOL;
      $echo .= '<!-- end trace -->' . PHP_EOL;
    }
    echo $echo;

    if ($goOut)
    {
      exit();
    }
  }
} // end class
?>
