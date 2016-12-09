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
 * Represents a tax override for a transaction
 */
final class TaxOverrideModel
{
    /**
     * @var TaxOverrideType? Identifies the type of tax override
     */
    public $type;

    /**
     * @var Decimal? Indicates a total override of the calculated tax on the document.  AvaTax will distribute
     *                 the override across all the lines.
     */
    public $taxAmount;

    /**
     * @var DateTime? The override tax date to use
     */
    public $taxDate;

    /**
     * @var String This provides the reason for a tax override for audit purposes.  It is required for types 2-4.
     */
    public $reason;

}
