{if $pagination}
    {block name="pagination" assign="content"}
        <ul class="pagination">
            <li class="per-page item">
                <form method="GET" action="">
                    {foreach $query_parameters|normalize_query as $key => $val}
                        {if $key !== "limit" && $key !== "offset"}
                            <input type="hidden" name="{$key|urldecode}" value="{$val}"/>
                        {/if}
                    {/foreach}
                    <label for="pagination-per-page">Per page </label>
                    <input type="number" id="pagination-per-page" min="1" name="limit"
                           value="{$pagination->getCurrentLimit()}"/>
                    <button type="submit">ok</button>
                </form>
            </li>
            {if count($pagination->getList()) > 1}{foreach $pagination->getList() as $page}
                {block name="link-content" assign="link_content"}
                    <span class="page">{$page->getNum()}</span>
                {/block}
                <li class="item{if $page->isCurrent()} current{/if}">
                    {include file="components/link.tpl" content=$link_content class="pagiantion-link{if $page->isCurrent()} current{/if}" href="?"|cat:$page->queryParam($query_parameters)}
                </li>
            {/foreach}{/if}
        </ul>
    {/block}
    {include file="components/section.tpl" class="pagination-section{if $class} {$class}{/if}" level=5 content=$content title="Pagination:"}
{/if}