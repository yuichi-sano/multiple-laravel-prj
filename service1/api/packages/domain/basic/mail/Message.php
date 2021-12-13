<?php

namespace packages\domain\basic\mail;


class Message
{
    private Subject $subject;
    private Content $content;

    public function __construct(Subject $subject, Content $content)
    {
        $this->subject=$subject;
        $this->content=$content;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getContent(): Content
    {
        return $this->content;
    }


}
