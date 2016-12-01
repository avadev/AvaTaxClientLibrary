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
 */class AdjustmentReason extends AvaTaxEnum 
{

    const NotAdjusted = "NotAdjusted";
    const SourcingIssue = "SourcingIssue";
    const ReconciledWithGeneralLedger = "ReconciledWithGeneralLedger";
    const ExemptCertApplied = "ExemptCertApplied";
    const PriceAdjusted = "PriceAdjusted";
    const ProductReturned = "ProductReturned";
    const ProductExchanged = "ProductExchanged";
    const BadDebt = "BadDebt";
    const Other = "Other";
    const Offline = "Offline";
}
