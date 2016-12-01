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
 */class BatchStatus extends AvaTaxEnum 
{

    const Waiting = "Waiting";
    const SystemErrors = "SystemErrors";
    const Cancelled = "Cancelled";
    const Completed = "Completed";
    const Creating = "Creating";
    const Deleted = "Deleted";
    const Errors = "Errors";
    const Paused = "Paused";
    const Processing = "Processing";
}
