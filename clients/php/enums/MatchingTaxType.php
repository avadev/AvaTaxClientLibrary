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
 */class MatchingTaxType extends AvaTaxEnum 
{

    const All = "All";
    const BothSalesAndUseTax = "BothSalesAndUseTax";
    const ConsumerUseTax = "ConsumerUseTax";
    const MedicalExcise = "MedicalExcise";
    const Fee = "Fee";
    const VATInputTax = "VATInputTax";
    const VATNonrecoverableInputTax = "VATNonrecoverableInputTax";
    const VATOutputTax = "VATOutputTax";
    const Rental = "Rental";
    const SalesTax = "SalesTax";
    const UseTax = "UseTax";
}
