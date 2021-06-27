<?php

class Repository
{
    public $name = "";
    public $url;

    public function __toString(): string
    {
        return "<repository>\n<id>$this->name</id>\n<url>$this->url</url>\n</repository>\n";
    }

    public function __construct(string $id, string $url)
    {
        $this->name = $id;
        $this->url = $url;
    }

}