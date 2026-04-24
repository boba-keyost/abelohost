<?php

namespace DB\Models\Types;

use FastVolt\Helper\Markdown;

class Post
{
    use WithCreated;
    use WithSoftDelete;

    public int $id;
    public string $slug;
    public string $name;
    public string $image;
    public string | Markdown $description;
    public string | Markdown $content;

    public function __construct()
    {
        $this->initCreatedFields();
        $this->initDeletedFields();
        if (is_string($this->content)) {
            $md = new Markdown();
            $md->setContent($this->content);
            $this->content = $md;
        }
        if (empty($this->description)) {
            $content = substr(
                strip_tags($this->content->toHtml()),
                0,
                300,
            );

            $this->description = substr($content, 0, strripos($content, ' '));
        }
    }
}
