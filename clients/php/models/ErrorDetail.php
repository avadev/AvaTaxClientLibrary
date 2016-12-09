<?php 
namespace Avalara;
/*
 * AvaTax API Client Library
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   AvaTax client libraries
 * @package    Avalara.AvaTaxClient
 * @author     Ted Spence <ted.spence@avalara.com>
 * @author     Bob Maidens <bob.maidens@avalara.com>
 * @copyright  2004-2016 Avalara, Inc.
 * @license    https://www.apache.org/licenses/LICENSE-2.0
 * @version    2.16.12-30
 * @link       https://github.com/avadev/AvaTaxClientLibrary
 */


/**
 * Message object
 */
final class ErrorDetail
{
    /**
     * @var ErrorCodeId? Name of the error.
     */
    public $code;

    /**
     * @var Int32? Error message identifier
     */
    public $number;

    /**
     * @var String Concise summary of the message, suitable for display in the caption of an alert box.
     */
    public $message;

    /**
     * @var String A more detailed description of the problem referenced by this error message, suitable for display in the contents area of an alert box.
     */
    public $description;

    /**
     * @var String Indicates the SoapFault code
     */
    public $faultCode;

    /**
     * @var String URL to help for this message
     */
    public $helpLink;

    /**
     * @var String Item the message refers to, if applicable.  This is used to indicate a missing or incorrect value.
     */
    public $refersTo;

    /**
     * @var SeverityLevel? Severity of the message
     */
    public $severity;

}
