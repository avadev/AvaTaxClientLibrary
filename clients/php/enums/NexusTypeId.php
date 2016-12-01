<?php
/*
 * AvaTax Enum Class
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
 */class NexusTypeId extends AvaTaxEnum 
{

    const None = "None";
    const SalesOrSellersUseTax = "SalesOrSellersUseTax";
    const SalesTax = "SalesTax";
    const SSTVolunteer = "SSTVolunteer";
    const SSTNonVolunteer = "SSTNonVolunteer";
}
