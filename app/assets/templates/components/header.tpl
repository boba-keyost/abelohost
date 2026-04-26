<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta
            name="description"
            content="{$pageDesc|default:"AbeloHost Test Task"}"
    />
    <title>{$pageTitle|default:"AbeloHost Test Task"}</title>
    {if $styles}{foreach $styles as $st}
        <link rel="stylesheet" href="/assets/styles/{$st}">
    {/foreach}{/if}
</head>
<body>
<header>
    <section class="content">
        <a href="/"><span class="logo">Abelohost</span></a>
    </section>
</header>
<main>
    <section class="content">
