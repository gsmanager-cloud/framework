<?php

namespace GSManager\Contracts\Mail;

interface Attachable
{
    /**
     * Get an attachment instance for this entity.
     *
     * @return \GSManager\Mail\Attachment
     */
    public function toMailAttachment();
}
