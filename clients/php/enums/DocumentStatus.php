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
 */class DocumentStatus extends AvaTaxEnum 
{

    const Temporary = "Temporary";
    const Saved = "Saved";
    const Posted = "Posted";
    const Committed = "Committed";
    const Cancelled = "Cancelled";
    const Adjusted = "Adjusted";
    const Queued = "Queued";
    const PendingApproval = "PendingApproval";
    const Any = "Any";
}
