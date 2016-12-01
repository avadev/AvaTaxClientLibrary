<?php
/*
 * AvaTax Model
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com
 */
final class VerifyTransactionModel extends AbstractEntity
{
    /**
     * @var DateTime?
     */
    public $verifyTransactionDate;

    /**
     * @var Decimal?
     */
    public $verifyTotalAmount;

    /**
     * @var Decimal?
     */
    public $verifyTotalTax;

}