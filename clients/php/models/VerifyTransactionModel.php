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
 * Verify this transaction by matching it to values in your accounting system.
 */
final class VerifyTransactionModel
{
    /**
     * @var DateTime? Transaction Date - The date on the invoice, purchase order, etc.
     */
    public $verifyTransactionDate;

    /**
     * @var Decimal? Total Amount - The total amount (not including tax) for the document.
     */
    public $verifyTotalAmount;

    /**
     * @var Decimal? Total Tax - The total tax for the document.
     */
    public $verifyTotalTax;

}
