<h1><a href="about.html" class="hlavicka">{$title}</a></h1>

<ul>
{foreach from=$zvirata item=zvire key=htmlfile}
<li><a href="{$htmlfile}">{$zvire.jmeno}</a></li>
{/foreach}
</ul>
