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
 */class TaxType extends AvaTaxEnum 
{

    const ConsumerUse = "ConsumerUse";
    const Excise = "Excise";
    const Fee = "Fee";
    const Input = "Input";
    const Nonrecoverable = "Nonrecoverable";
    const Output = "Output";
    const Rental = "Rental";
    const Sales = "Sales";
    const Use = "Use";
}
