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
 * Settle this transaction with your ledger by executing one or many actions against that transaction.  
            You may use this endpoint to verify the transaction, change the transaction's code, and commit the transaction for reporting purposes.
            This endpoint may be used to execute any or all of these actions at once.
 */
final class SettleTransactionModel
{
    /**
     * @var VerifyTransactionModel To use the "Settle" endpoint to verify a transaction, fill out this value.
     */
    public $verify;

    /**
     * @var ChangeTransactionCodeModel To use the "Settle" endpoint to change a transaction's code, fill out this value.
     */
    public $changeCode;

    /**
     * @var CommitTransactionModel To use the "Settle" endpoint to commit a transaction for reporting purposes, fill out this value.
     *                 If you use Avalara Returns, committing a transaction will cause that transaction to be filed.
     */
    public $commit;

}
