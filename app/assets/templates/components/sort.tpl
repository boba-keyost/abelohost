{if $sort}
    {append var='title' value='Name' index='name'}
    {append var='title' value='Updated' index='updated_at'}
    {append var='title' value='Created' index='created_at'}
    {append var='title' value='Id' index='id'}
    {append var='title' value='Views' index='views'}
    {block name="sorts" assign="content"}
        <ul class="sort">
            {foreach $sort->getList() as $field}
                {block name="link-content" assign="link_content"}
                    <span class="field">{$title[$field->getField()]|default:$field->getField()}</span>
                    <span class="direction">
                    <span class="asc">↑</span>
                    <span class="desc">↓</span>
                </span>
                {/block}
                <li class="item item-{$field->getField()} {if $field->isCurrent()} current{/if}">
                    {include file="components/link.tpl" content=$link_content class="sort-link order-{$field->getOrder()|lower} {if $field->isCurrent()} current{/if} next-{$field->getQueryOrder()|default:"reset"}" href="?"|cat:$field->queryParam("sort", $query_parameters)}
                </li>
            {/foreach}
        </ul>
    {/block}
    {include file="components/section.tpl" class="sort-section{if $class} {$class}{/if}" level=5 content=$content title="Sort By:"}
{/if}