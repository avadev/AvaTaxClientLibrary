<?php
/*
 * AvaTax Entity Model Class
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com>
 */
final class TaxOverrideModel extends AbstractEntity
{
    /**
     * @var TaxOverrideType?
     */
    public $type;

    /**
     * @var Decimal?
     */
    public $taxAmount;

    /**
     * @var DateTime?
     */
    public $taxDate;

    /**
     * @var String
     */
    public $reason;

}
