<?php
class DocumentStatus extends AvaTaxEnum 
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
?>
