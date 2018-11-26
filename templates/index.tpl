<a href="about.html" class="hlavicka"><h1>{$title}</h1></a>

<ul>
{foreach from=$zvirata item=zvire key=htmlfile}
<li><a href="{$htmlfile}">{$zvire.jmeno}</a></li>
{/foreach}
</ul>
