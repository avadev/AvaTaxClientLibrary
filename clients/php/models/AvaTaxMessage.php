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
 * Informational or warning messages returned by AvaTax with a transaction
 */
final class AvaTaxMessage
{
    /**
     * @var String A brief summary of what this message tells us
     */
    public $summary;

    /**
     * @var String Detailed information that explains what the summary provided
     */
    public $details;

    /**
     * @var String Information about what object in your request this message refers to
     */
    public $refersTo;

    /**
     * @var String A category that indicates how severely this message affects the results
     */
    public $severity;

    /**
     * @var String The name of the code or service that generated this message
     */
    public $source;

}
