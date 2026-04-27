<?php

namespace DB\Models\Types;

use FastVolt\Helper\Markdown;
use Features\Functions;

class Post extends BaseType
{
    use WithCreated;
    use WithSoftDelete;

    public int $id;
    public string $slug;
    public string $name;
    public string $image;
    public string | Markdown $description;
    public string | Markdown $content;

    protected bool $hasDescription;

    public function __construct()
    {
        $this->initCreatedFields();
        $this->initDeletedFields();
        if (is_string($this->content)) {
            $md = new Markdown();
            $md->setContent($this->content);
            $this->content = $md;
        }
        $this->hasDescription = !empty($this->description);
        if (!$this->hasDescription) {
            $this->description = Functions::cutText($this->content->toHtml(), 300);
        }
    }

    public function hasDescription(): bool
    {
        return $this->hasDescription;
    }
}
