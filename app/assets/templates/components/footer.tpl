</section>
</main>
<footer>
    <section class="content">
        <div class="copyright">Copyright @{$smarty.now|date_format:"%Y"}. All Rights Reserved.</div>
    </section>
</footer>
{if $scripts}{foreach $scripts as $sc}
    <script async type="application/javascript" src="/assets/js/{$sc}"></script>
{/foreach}{/if}
</body>
</html>