{assign var="date_format" value=$date_format|default:"%b %e, %Y"}
<div class="date{if $class} {$class}{/if}">{$date|date_format:$date_format}</div>