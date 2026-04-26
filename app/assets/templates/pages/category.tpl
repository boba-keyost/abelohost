{include file="components/header.tpl"}

{include file="components/code-section.tpl" class="category-sort" content=$error title="Error"}

{include file="components/pagiantion.tpl" class="category-pagination" pagination=$pagination query_parameters=$query_parameters assign="pagination_comp"}

{include file="components/sort.tpl" class="category-sort" sort=$sort query_parameters=$query_parameters}
{$pagination_comp}
{include file="components/short-category.tpl" class="item" category=$category posts=$posts}
{$pagination_comp}

{include file="components/footer.tpl"}